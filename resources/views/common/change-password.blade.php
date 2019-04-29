@extends('layouts.master')

@section('content')


<div class="row">
    <div class="col-md-8 mx-auto">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">Change {{ $title }}</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
               <form role="form" method="post" action="{{ route('change-user-password') }}" enctype="multipart/form-data">
                @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="current_password">Current Password </label>
                  <input id="current_password" type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" required>

                  @if ($errors->has('password'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
                </div>
                <hr>
                <div class="form-group">
                 <label for="new_password">New Password</label>
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                  @if ($errors->has('password'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="confirm_password">Confirm Password</label>
                  <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                </div>
              </div>
           
            <a href="{{ route('home') }}" class="btn btn-secondary" >Back</a>
            <button type="submit" class="btn btn-primary">Update {{ $title }}</button>
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
