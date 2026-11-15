@props(['activity', 'label'])

<ol class="dashboard-chart" aria-label="{{ $label }}">
    @foreach ($activity as $day)
        <li>
            <span>{{ $day['count'] }}</span>
            <span class="dashboard-chart-track" aria-hidden="true">
                <span style="--bar-height: {{ $day['height'] }}%"></span>
            </span>
            <time datetime="{{ $day['date'] }}" title="{{ $day['date'] }}">{{ $day['label'] }}</time>
        </li>
    @endforeach
</ol>
