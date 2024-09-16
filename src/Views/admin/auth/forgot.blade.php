@extends($vncore_templatePathAdmin.'layout_portable')

@section('main')
@include($vncore_templatePathAdmin.'component.css_login')
    <div class="container-login100">
      <div class="wrap-login100 main-login">

          <div class="card-header text-center">
            <a href="{{ vncore_route_admin('home') }}" class="h1">
              <img src="{{ vncore_file(vncore_store('logo')) }}" alt="logo" class="logo">
            </a>
          </div>
          <div class="login-title-des col-md-12 p-b-41">
            <a><b>{{vncore_language_render('admin.password_forgot')}}</b></a>
          </div>
          <div class="card-body">
          <form action="{{ vncore_route_admin('admin.post_forgot') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-form {!! !$errors->has('email') ?: 'text-red' !!}">
              <div class="input-group mb-3">
                <input class="input100 form-control form-control-sm" type="email" placeholder="{{ vncore_language_render('admin.user.email') }}"
                name="email" value="{{ old('email') }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              @if($errors->has('email'))
              @foreach($errors->get('email') as $message)
              <i class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</i>
              @endforeach
              @endif
            </div>

            <div class="input-form">
              <div class="col-12">
                <div class="container-login-btn">
                  <button class="login-btn" type="submit">
                    {{ vncore_language_render('action.submit') }}
                  </button>
                </div>
              </div>
            </div>
          </form>
          <p class="mt-3 mb-1">
          <a href="{{ vncore_route_admin('admin.login') }}"><b>{{ vncore_language_render('admin.user.login') }}</b></a>
          </p>
          @if (session('status'))
              <p style="color:green">{{ session('status') }}</p>
          @endif
          </div>
      </div>
    </div>

    @endsection


    @push('styles')
    <style type="text/css">
      .container-login100 {
        background-image: url({!! vncore_file('Vncore/admin/images/bg-system.jpg') !!});
      }
    </style>
    @endpush

    @push('scripts')
    @endpush