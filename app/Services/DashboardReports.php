<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;
use App\Models\Visitor;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

final class DashboardReports
{
    public function overview(User $user): array
    {
        $recentLogins = $user->logins()->latest('id')->limit(2)->get();
        $links = $user->links();
        $totals = (clone $links)->selectRaw('COUNT(*) as link_count, COALESCE(SUM(total_clicks), 0) as total_clicks')->first();
        $now = now()->toImmutable();
        $weekStart = $now->subDays(6)->startOfDay();
        $monthStart = $now->startOfMonth();
        $dailyCounts = Visitor::query()
            ->whereIn('link_id', (clone $links)->select('links.id'))
            ->whereBetween('created_at', [$weekStart->min($monthStart), $now])
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as visits')
            ->groupByRaw('DATE(created_at)')
            ->pluck('visits', 'visit_date');

        return [
            'lastLogin'   => $recentLogins->get(1) ?? $recentLogins->first(),
            'linkCount'   => (int) $totals->link_count,
            'totalClicks' => (int) $totals->total_clicks,
            'recentLinks' => $links->latest('id')->limit(5)->get(),
            'todayVisits' => (int) $dailyCounts->get($now->toDateString(), 0),
            'monthVisits' => (int) $dailyCounts->filter(fn ($count, $date) => $date >= $monthStart->toDateString())->sum(),
            'activity'    => $this->weeklyActivity($weekStart, $dailyCounts->all()),
        ];
    }

    public function linkDetails(Link $link): array
    {
        $visitors = $link->visitors()->latest()->get();
        $now = now()->toImmutable();
        $weekStart = $now->subDays(6)->startOfDay();
        $weeklyCounts = $visitors->whereBetween('created_at', [$weekStart, $now])
            ->groupBy(fn (Visitor $visit): string => $visit->created_at->toDateString())
            ->map(fn (Collection $visits): int => $this->visitTotals($visits)['uniqueIps'])
            ->all();

        return [
            'url'        => $link,
            'visitors'   => $visitors,
            'favBrowser' => $this->mostFrequent($visitors, 'options.browser'),
            'favOS'      => $this->mostFrequent($visitors, 'options.os'),
            'activity'   => $this->weeklyActivity($weekStart, $weeklyCounts),
            'periods'    => [
                'Bugün'        => $this->visitTotals($visitors->whereBetween('created_at', [$now->startOfDay(), $now])),
                'Bu ay'        => $this->visitTotals($visitors->whereBetween('created_at', [$now->startOfMonth(), $now])),
                'Tüm zamanlar' => $this->visitTotals($visitors),
            ],
        ];
    }

    /**
     * @param  array<string, int|numeric-string>                                       $dailyCounts
     * @return list<array{date: string, label: string, count: int, height: float|int}>
     */
    private function weeklyActivity(CarbonImmutable $weekStart, array $dailyCounts): array
    {
        $days = [];
        for ($offset = 0; $offset < 7; $offset++) {
            $day = $weekStart->addDays($offset);
            $date = $day->toDateString();
            $days[] = [
                'date'  => $date,
                'label' => $day->locale(app()->getLocale())->isoFormat('dd'),
                'count' => (int) ($dailyCounts[$date] ?? 0),
            ];
        }

        $peak = max(1, max(array_column($days, 'count')));

        return array_map(static fn (array $day): array => $day + ['height' => $day['count'] / $peak * 100], $days);
    }

    /**
     * @param  Collection<int, Visitor>           $visitors
     * @return array{visits: int, uniqueIps: int}
     */
    private function visitTotals(Collection $visitors): array
    {
        return [
            'visits'    => $visitors->count(),
            'uniqueIps' => $visitors->map(static fn (Visitor $visit): ?string => $visit->options?->ip())
                ->filter()->uniqueStrict()->count(),
        ];
    }

    /** @param Collection<int, Visitor> $visitors */
    private function mostFrequent(Collection $visitors, string $attribute): string
    {
        return $visitors->pluck($attribute)->filter()->countBy()->sortDesc()->keys()->first() ?? '—';
    }
}
