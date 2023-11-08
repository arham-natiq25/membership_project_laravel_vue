@extends('auth.master')

@section('title')
Login
@endsection
@section('content')

<div class="login-box">
    <div class="login-logo">
      <a href=""><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form  method="POST" action="{{ route('login') }}">
            @csrf
          <div class="input-group my-2">
              <!-- Email Address -->
            <input type="email" class="form-control"  name="email" value="{{old('email') }}" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
        </div>
        @if ($errors->has('email'))
               <code>{{ $errors->first('email') }}</code>
        @endif
        <!-- Password -->
          <div class="input-group my-2">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
        </div>
        @if ($errors->has('password'))
               <code>{{ $errors->first('password') }}</code>
        @endif

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox"  id="remember_me" name="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>

            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>



        <p class="mb-1">
            @if (Route::has('password.request'))
                <a  href="{{ route('password.request') }}">
                    I forget My Password
                </a>
            @endif
        </p>
        <p class="mb-0">
          <a href="{{route('register')}}" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
@endsection
