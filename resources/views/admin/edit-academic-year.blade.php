@extends('layouts.master')

@section('content')


<div class="row">
    <div class="col-md-8 mx-auto">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">Update {{ $title }}</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
               <form role="form" method="post" action="{{ route($uroute, $ay->id) }}">
        @csrf
        {{-- <div class="modal-body"> --}}

          @if(!$isDep)
            <div class="card-body">
              <div class="form-group">
                <label for="year">Academic Year</label>
                <input type="text" class="form-control" id="year" placeholder="2019" name="year" value="{{ $ay->year }}">
              </div>
              <div class="form-group">
               <label for="opening-date">Opening date</label>
                <input type="date" class="form-control" id="opening-date" placeholder="DD/MM" name="opening_date" value="{{ $ay->opening_date }}">
              </div>
              <div class="form-group">
                <label for="closing-date">Closing date</label>
                <input type="date" class="form-control" id="closing-date" placeholder="DD/MM" name="closing_date" value="{{ $ay->closing_date }}">
              </div>
              <div class="form-group">
                <label for="final-date">Final date</label>
                <input type="date" class="form-control" id="final-date" placeholder="DD/MM" name="final_date" value="{{ $ay->final_date }}">
              </div>
            </div>

            @else

            <div class="card-body">
              <div class="form-group">
                <label for="year">Department Name</label>
                <input type="text" class="form-control" id="year" placeholder="EEE" name="name" value="{{ $ay->name }}">
              </div>
            </div>

            @endif
            <!-- /.card-body -->

            {{-- <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>  --}}   
        {{-- </div> --}}
          {{-- <div class="modal-footer"> --}}
            <a href="{{ route($route) }}" class="btn btn-secondary" >Back</a>
            <button type="submit" class="btn btn-primary">Update {{ $title }}</button>
          {{-- </div> --}}
      </form>
            </div>
              <!-- /.card-body -->
            <div class="card-footer clearfix">
               {{-- {{ $ays->links() }}  --}}

               {{-- {!! $ays->render() !!} --}}
            </div>
        </div>

            {{-- {{ $ays }} --}}



            <!-- general form elements -->
            {{-- <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Quick Example</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              
            </div> --}}
            <!-- /.card -->


          </div>
      </div>


     

@endsection
