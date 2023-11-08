@extends('auth.master')
@section('title')
    Registration
@endsection
@section('content')
<div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
          <div class="input-group ">
            <input type="text" class="form-control" name="name" value="{{ old('name') }}"  id="name" placeholder="Full name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('name'))
          <code>{{ $errors->first('name') }}</code>
         @endif
         <br>
          <div class="input-group ">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('email'))
          <code>{{ $errors->first('email') }}</code>
          @endif
          <br>
          <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('password'))
          <code>{{ $errors->first('password') }}</code>
          @endif
          <br>
          <div class="input-group ">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Retype password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('password_confirmation'))
          <code>{{ $errors->first('password_confirmation') }}</code>
          @endif
          <br>
          <div class="row">

            <!-- /.col -->
            <div class="col-4 offset-8">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <a href="{{route('login')}}" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
@endsection
