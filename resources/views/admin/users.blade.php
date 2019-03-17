@extends('layouts.master')


@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.css"/>
@endsection

@section('content')


<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">List of {{ $title }}s</h3>
                <a href="{{ route('add-user') }}" class="btn btn-primary float-right"><i class="far fa-calendar-plus mr-2"> </i>   Add {{ $title }}</a>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                  @if($users)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>



                        @foreach($users as $user)

                          <tr class="align-middle">
                            <td class="align-middle">{{ $user->id }}</td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->dep->name }}</td>
                            <td class="align-middle">

                              @if($user->role == 5)

                              <span class="badge bg-success">Admin</span>

                              @elseif($user->role == 4)

                              <span class="badge bg-warning">Marketing Manage</span>

                              @elseif($user->role == 3)

                              <span class="badge bg-primary">Marketing Coordinator</span>

                              @elseif($user->role == 2)

                              <span class="badge bg-primary">Student</span>

                              @else

                              <span class="badge bg-danger">Faculty</span>
                              @endif</td>
                            <td>
                                <a href="{{ route($eroute,$user->id) }}" class="btn btn-block btn-primary btn-sm">Edit</a> 
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
              <!-- /.card-body -->
            <div class="card-footer clearfix">
               {{ $users->links() }} 

               {{-- {!! $ays->render() !!} --}}
            </div>
        </div>
          </div>
      </div>


      <!-- Modal -->
{{-- <div class="modal fade" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
 --}}
@endsection


@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script>
  $(function () {
    $("#example1").addClass( 'nowrap' ).DataTable({
      "responsive": true
    });
  });
</script>
@endsection