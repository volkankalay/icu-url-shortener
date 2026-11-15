@extends('layouts.themed')

@push('theme-css')
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
@endpush

@section('content')
    <main class="landing-shell">
        <section class="landing-hero" aria-labelledby="landing-title">
            <div class="landing-intro">
                <h1 id="landing-title">@lang('homepage.slogan')</h1>
                <p>@lang('homepage.intro')</p>

                <form id="shorten-form" class="shorten-form"
                      @auth method="post" action="{{ route('url.create.post') }}"
                      @else action="{{ route('login') }}" @endauth>
                    @auth
                        @csrf
                    @endauth
                    <label for="shorten-url">@lang('homepage.linklabel')</label>
                    <div class="shorten-control">
                        <i class="fas fa-link" aria-hidden="true"></i>
                        <input id="shorten-url" @auth name="url" @endauth type="url" inputmode="url" placeholder="https://example.com/…" maxlength="191" required
                               @error('url') aria-invalid="true" aria-describedby="shorten-url-error" @enderror>
                        <button type="submit">@lang('homepage.shortbtn') <span aria-hidden="true">↗</span></button>
                    </div>
                    @error('url')
                        <p id="shorten-url-error" role="alert">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            <div class="landing-preview">
                <span class="preview-label">@lang('homepage.example')</span>
                <div class="preview-flow">
                    <div class="preview-url preview-url-source">
                        <i class="fas fa-globe" aria-hidden="true"></i>
                        <span>example.com/blog/how-to-share-a-link</span>
                    </div>
                    <div class="preview-connector" aria-hidden="true">
                        <span></span>
                        <img src="{{ $settings->logoUrl() }}" width="68" height="68" alt="">
                        <span></span>
                    </div>
                    <div class="preview-url preview-url-result">
                        <i class="fas fa-link" aria-hidden="true"></i>
                        <span>{{ parse_url($settings->site ?: config('app.url'), PHP_URL_HOST) ?: request()->getHost() }}/k7m</span>
                        <span class="preview-arrow" aria-hidden="true">↗</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="landing-features" aria-labelledby="features-title">
            <h2 id="features-title">@lang('homepage.features')</h2>
            <div class="landing-feature-grid">
                <article>
                    <i class="fas fa-chart-bar" aria-hidden="true"></i>
                    <h3>@lang('homepage.details')</h3>
                    <p>@lang('homepage.detailstxt')</p>
                </article>
                <article>
                    <i class="fas fa-layer-group" aria-hidden="true"></i>
                    <h3>@lang('homepage.layer')</h3>
                    <p>@lang('homepage.layertxt')</p>
                </article>
                <article>
                    <i class="fas fa-link" aria-hidden="true"></i>
                    <h3>@lang('homepage.easy')</h3>
                    <p>@lang('homepage.easytxt')</p>
                </article>
            </div>
        </section>
    </main>

    @guest
        @include('widgets.loginOrRegister')
    @endguest
@endsection

@section('js')
    @guest
        <script>
            document.getElementById('shorten-form').addEventListener('submit', function (event) {
                event.preventDefault();
                $('#loginOrRegister').modal('show');
            });
        </script>
    @endguest
@endsection
