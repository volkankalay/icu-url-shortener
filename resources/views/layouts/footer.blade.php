
@hasSection('body-class')
<footer class="landing-footer">
  <div class="landing-shell">
    <a href="{{ route('home') }}">
      <img src="{{ $settings->logoUrl() }}" width="28" height="28" alt="">
      {{ $settings->title }}
    </a>
    <small>© {{ date('Y') }} {{ $settings->title }}. @lang('homepage.rights').</small>
  </div>
</footer>
@else
<!-- Footer -->
<footer class="footer bg-light py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 h-100 text-center mx-auto my-auto">
        <ul class="list-inline mb-2">
          <li class="list-inline-item">
            <a href="#">@lang('homepage.about')</a>
          </li>
          <li class="list-inline-item">&sdot;</li>
          <li class="list-inline-item">
            <a href="#">@lang('homepage.contact')</a>
          </li>
          <li class="list-inline-item">&sdot;</li>
          <li class="list-inline-item">
            <a href="#">@lang('homepage.terms')</a>
          </li>
          <li class="list-inline-item">&sdot;</li>
          <li class="list-inline-item">
            <a href="#">@lang('homepage.privacy')</a>
          </li>
        </ul>
        <p class="text-muted small mb-4 mb-lg-0"><a href="https://www.vlkn.icu/" target="_blank" class="text-danger text-sm text-decoration-none" rel="nofollow">vooky</a> &copy; {{$settings->title}} 2020. @lang('homepage.rights').</p>
      </div>
    </div>
  </div>
</footer>
@endif

@if (auth()->check() || request()->routeIs('home'))
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endif
@yield('js')
</body>

</html>
