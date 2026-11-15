<div class="modal fade bd-example-modal-sm" data-backdrop="static" tabindex="-1" id="loginOrRegister" role="dialog" aria-labelledby="loginOrRegisterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="loginOrRegisterTitle">@lang('homepage.loginOrRegister')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">@lang('homepage.signin_to_shorten')</div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">@lang('homepage.maybeLater')</button>
        @if ($settings->login)
          <a href="{{ route('login') }}" class="btn btn-success border">@lang('homepage.login')</a>
        @endif
        @if ($settings->register)
          <a href="{{ route('register') }}" class="btn btn-primary border">@lang('homepage.register')</a>
        @endif
      </div>
    </div>
  </div>
</div>
