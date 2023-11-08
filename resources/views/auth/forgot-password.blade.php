@extends('auth.master')
@section('title')
    Forget Password
@endsection
@section('content')
<div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
        <form method="POST" action="{{ route('password.email') }}">
         @csrf
          <div class="input-group mb-3">
            <input type="email" class="form-control" id="email" value="{{ old('email') }}"  name="email" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block ">Request new password</button>
            </div>
            <!-- /.col -->
        </form>
        <p class="mt-3 mb-1">
          <a href="{{ route('login') }}">Login</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
@endsection
