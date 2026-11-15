@props(['title', 'description', 'action', 'logo', 'wide' => false])

<main class="auth-page landing-shell">
    <section @class(['auth-card', 'auth-card-wide' => $wide]) aria-labelledby="auth-title">
        <div class="auth-brand-panel" aria-hidden="true">
            <div class="auth-brand-mark">
                <img src="{{ $logo }}" width="104" height="104" alt="">
            </div>
        </div>

        <div class="auth-card-content">
            <header class="auth-heading">
                <h1 id="auth-title">{{ $title }}</h1>
                <p>{{ $description }}</p>
            </header>
            <form class="auth-form" method="post" action="{{ $action }}">
                @csrf
                {{ $slot }}
                <button class="auth-submit" type="submit">{{ $title }} <span aria-hidden="true">→</span></button>
            </form>

            @isset($footer)
                <div class="auth-card-footer">{{ $footer }}</div>
            @endisset
        </div>
    </section>
</main>
