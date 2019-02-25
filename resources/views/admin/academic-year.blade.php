@extends('layouts.master')

@section('content')


<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">List of {{ $title }}s</h3>
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addYear"><i class="far fa-calendar-plus mr-2"> </i>   Add Year</button>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Year</th>
                                <th>Opening Date</th>
                                <th>Closing Date</th>
                                <th>Final Date</th>
                                <th>Progress</th>
                                <th style="width: 40px">Timeleft</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($ays as $ay)

                          <tr class="align-middle">
                            <td class="align-middle">{{ $ay->id }}</td>
                            <td class="align-middle">{{ $ay->year }}</td>
                            <td class="align-middle">{{ $ay->opening_date }}</td>
                            <td class="align-middle">{{ $ay->closing_date }}</td>
                            <td class="align-middle">{{ $ay->final_date }}</td>
                            <td class="align-middle">
                              <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                              </div>
                            </td>
                            <td class="align-middle"><span class="badge bg-danger">55 days</span></td>

                            <td>
                                <a href="{{ route($eroute,$ay->id) }}" class="btn btn-block btn-primary btn-sm">Edit</a> 
                                {{-- <a data-href="{{ route($droute,$post->id) }}" class="cat-delete text-danger"> Delete</a> --}}
                            </td>
                          </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
              <!-- /.card-body -->
            <div class="card-footer clearfix">
               {{ $ays->links() }} 

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


      <!-- Modal -->
<div class="modal fade" id="addYear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New {{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" method="post" action="{{ route('post-academic-year') }}">
        @csrf
        <div class="modal-body">
            <div class="card-body">
              <div class="form-group">
                <label for="year">Academic Year</label>
                <input type="text" class="form-control" id="year" placeholder="2019" name="year" value="{{ old('year') }}">
              </div>
              <div class="form-group">
               <label for="opening-date">Opening date</label>
                <input type="date" class="form-control" id="opening-date" placeholder="DD/MM" name="opening_date" value="{{ old('opening_date') }}">
              </div>
              <div class="form-group">
                <label for="closing-date">Closing date</label>
                <input type="date" class="form-control" id="closing-date" placeholder="DD/MM" name="closing_date" value="{{ old('closing_date') }}">
              </div>
              <div class="form-group">
                <label for="final-date">Final date</label>
                <input type="date" class="form-control" id="final-date" placeholder="DD/MM" name="final_date" value="{{ old('final_date') }}">
              </div>
            </div>
            <!-- /.card-body -->

            {{-- <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>  --}}   
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add {{ $title }}</button>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection
