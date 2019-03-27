@extends('layouts.master')

@section('content')


<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">List of {{ $title }}s</h3>
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addDepartment"><i class="far fa-calendar-plus mr-2"> </i>   Add {{ $title }}</button>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">

                  @if($deps)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px"><i class="fas fa-landmark"></i></th>
                                <th>Faculty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>



                        @foreach($deps as $dp)

                          <tr class="align-middle">
                            <td class="align-middle">{{ $dp->id }}</td>
                            <td class="align-middle">{{ $dp->name }}</td>
                            <td>
                                <a href="{{ route($eroute,$dp->id) }}" class="btn btn-block btn-primary btn-sm">Edit</a> 
                                {{-- <a data-href="{{ route($droute,$post->id) }}" class="cat-delete text-danger"> Delete</a> --}}
                            </td>
                          </tr>

                        @endforeach
                        </tbody>
                    </table>

                    @else
                      <div class="aligne-midle">No data Found!</div>
                    @endif
                </div>
            </div>
              <!-- /.card-body -->
            <div class="card-footer clearfix">
               {{ $deps->links() }} 

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
<div class="modal fade" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New {{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" method="post" action="{{ route($route) }}">
        @csrf
        <div class="modal-body">
            <div class="card-body">
              <div class="form-group">
                <label for="name">Department Name</label>
                <input type="text" class="form-control" id="name" placeholder="EEE" name="name" value="{{ old('name') }}">
              </div> 
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add {{ $title }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
