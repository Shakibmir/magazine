@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as 
                @if(Auth::user()->role == 5)
                Admin
                @elseif(Auth::user()->role == 5)
                    Manager
                @elseif(Auth::user()->role == 5)
                    Coordinator
                @elseif(Auth::user()->role == 5)
                    Student
                @else
                    Faculty
                @endif


                    !
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
