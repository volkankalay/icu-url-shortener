<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use UA;

/** @implements Arrayable<string, string> */
final readonly class VisitorOptions implements Arrayable
{
    public function __construct(
        public ?string $ipv4 = null,
        public ?string $ipv6 = null,
        public ?string $browser = null,
        public ?string $browserVersion = null,
        public ?string $os = null,
        public ?string $osVersion = null,
        public ?string $device = null,
        public ?string $referrerHost = null,
        public ?string $language = null,
    ) {}

    public static function fromRequest(Request $request): ?self
    {
        $ip = $request->ip();
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }

        $packedIp = inet_pton($ip);
        if ($packedIp === false) {
            return null;
        }

        if (strlen($packedIp) === 16 && str_starts_with($packedIp, str_repeat("\x00", 10) . "\xff\xff")) {
            $packedIp = substr($packedIp, 12);
        }

        $agent = UA::parse($request->userAgent() ?? '');

        $normalizedIp = inet_ntop($packedIp);
        $isIpv4 = strlen($packedIp) === 4;

        return new self(
            ipv4: $isIpv4 ? $normalizedIp : null,
            ipv6: $isIpv4 ? null : $normalizedIp,
            browser: $agent->ua->family,
            browserVersion: $agent->ua->toVersion() ?: null,
            os: $agent->os->family,
            osVersion: $agent->os->toVersion() ?: null,
            device: $agent->device->family,
            referrerHost: self::referrerHost($request),
            language: self::preferredLanguage($request),
        );
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $string = static fn (string $key): ?string => is_string($data[$key] ?? null) ? $data[$key] : null;
        $legacyIp = $string('ip');

        return new self(
            ipv4: $string('ipv4') ?? (filter_var($legacyIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ?: null),
            ipv6: $string('ipv6') ?? (filter_var($legacyIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ?: null),
            browser: $string('browser'),
            browserVersion: $string('browser_version'),
            os: $string('os'),
            osVersion: $string('os_version'),
            device: $string('device'),
            referrerHost: $string('referrer_host'),
            language: $string('language'),
        );
    }

    public function ip(): ?string
    {
        return $this->ipv4 ?? $this->ipv6;
    }

    public function toArray(): array
    {
        return array_filter([
            'ipv4'            => $this->ipv4,
            'ipv6'            => $this->ipv6,
            'browser'         => $this->browser,
            'browser_version' => $this->browserVersion,
            'os'              => $this->os,
            'os_version'      => $this->osVersion,
            'device'          => $this->device,
            'referrer_host'   => $this->referrerHost,
            'language'        => $this->language,
        ], static fn (?string $value): bool => $value !== null);
    }

    private static function referrerHost(Request $request): ?string
    {
        $referrer = parse_url($request->headers->get('referer') ?? '');
        $scheme = strtolower($referrer['scheme'] ?? '');
        $host = strtolower($referrer['host'] ?? '');

        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        return filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)
            || filter_var(trim($host, '[]'), FILTER_VALIDATE_IP) ? $host : null;
    }

    private static function preferredLanguage(Request $request): ?string
    {
        $language = str_replace('_', '-', $request->getLanguages()[0] ?? '');

        return strlen($language) <= 35 && preg_match('/\A[a-z]{2,8}(?:-[a-z0-9]{1,8})*\z/i', $language)
            ? $language
            : null;
    }
}
