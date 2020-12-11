<div class="modal fade bd-example-modal-sm" data-backdrop="static" tabindex="-1" id="loginOrRegister" role="dialog" aria-labelledby="loginOrRegister" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">@lang('homepage.loginOrRegister')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        Bu özelliği kullanabilmek için oturum açmanız gerekmektedir.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">@lang('homepage.maybeLater')</button>
        <a href="{{route('login')}}" type="button" class="btn btn-success border btn-lg">@lang('homepage.login')</a>
        <a href="{{route('register')}}" type="button" class="btn btn-primary border btn-lg">@lang('homepage.register')</a>
      </div>
    </div>
  </div>
</div>
