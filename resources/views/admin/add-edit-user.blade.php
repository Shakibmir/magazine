@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-header">
          <h3 class="card-title d-inline">{{ $title }}</h3>
      </div>
        <!-- /.card-header -->
      <div class="card-body">
        <form role="form" method="post" action="{{ route($route) }}" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group row">
              <label for="name">{{ __('User Full Name') }}</label>
              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

              @if ($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif
            </div>

            <div class="form-group row">
              <label for="email">{{ __('E-Mail Address') }}</label>
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

              @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>

            <div class="row">
          <div class="col-md-6">

            <div class="form-group">
              <label for="role">Role</label>
              <select name="role" class="form-control" >
                  <option value="2">Student</option>
                  <option value="1">Faculty</option>
                  <option value="3">Coordinator</option>
                  <option value="4">Manager</option>
                  <option value="5">Admin</option>
              </select> 
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="department_id">Department</label>
              <select name="department_id" class="form-control" >
                @foreach($deps as $dp)
                  <option value="{{ $dp->id }}">{{ $dp->name }}</option>
                @endforeach
              </select> 
            </div>
          </div>
        </div>

            <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="password">{{ __('Password') }}</label>
              <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

              @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="password-confirm">{{ __('Confirm Password') }}</label>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
          </div>
          </div>
          </div>
     
          <a href="{{ route('users') }}" class="btn btn-secondary" >Back</a>
          <button type="submit" class="btn btn-primary float-right">{{ $title }}</button>
        {{-- </div> --}}
        </form>
      </div>
          <!-- /.card-body -->
      <div class="card-footer clearfix">
      </div>
    </div>
  </div>
</div>
@endsection