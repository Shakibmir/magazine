@extends('layouts.master')

@section('styles')

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" integrity="sha256-e47xOkXs1JXFbjjpoRr1/LhVcqSzRmGmPqsrUQeVs+g=" crossorigin="anonymous" />


<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js" integrity="sha256-cs4thShDfjkqFGk5s2Lxj35sgSRr4MRcyccmi0WKqCM=" crossorigin="anonymous"></script> --}}

@endsection
@section('content')


<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">List of {{ $title }}s</h3>

                @if(Auth::user()->role == 2)
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addContribution"><i class="far fa-calendar-plus mr-2"> </i>   Add Contribution</button>
                @endif
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Title</th>
                                <th>Academic Year</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                @if(Auth::user()->role > 4)
                                <th>Submited By</th>
                                <th>Department</th>
                                @endif
                                <th>Timeleft to Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($cons as $con)

                          <tr class="align-middle">
                            <td class="align-middle">{{ $con->id }}</td>
                            <td class="align-middle">{{ $con->title }}</td>
                            <td class="align-middle">{{ $con->academic_year }}</td>
                            <td class="align-middle">{{ $con->created_at }}</td>
                            <td class="align-middle">

                              @if($con->status == 1)

                              <span class="badge bg-primary">Submitted</span>

                              @elseif($con->status == 2)

                              <span class="badge bg-warning">Commented</span>

                              @elseif($con->status == 3)

                              <span class="badge bg-success">Accepted</span>
                              @else

                              <span class="badge bg-danger">Rejected</span>
                              @endif

                            </td>
                            @if(Auth::user()->role > 4)
                            <td class="align-middle">{{ $con->user->name }}</td>
                            <td class="align-middle">{{ $con->user->dep->name }}</td>
                            @endif

                            @php
                                   $cdiff = Carbon\Carbon::today()->diffInDays($con->acyear->closing_date, false);
                                   $fdiff = Carbon\Carbon::today()->diffInDays($con->acyear->final_date, false);
                            @endphp
                            <td class="align-middle">

                                
                                <span class="badge
                                @if($cdiff>0)

                                bg-success
                                    
                                @elseif($fdiff>0)

                                bg-warning

                                @else
                                 bg-danger

                                @endif

                                 ">
                                 {{ $fdiff }} days
                                </span></td>

                            <td>
                              @if(Auth::user()->role == 2)
                                <a href="{{ route($eroute,$con->id) }}" class="btn btn-block btn-primary btn-sm">Edit</a>
                              @endif

                              @if(Auth::user()->role > 4)
                              <a href="#" class="btn btn-block btn-primary btn-sm">Comment</a>
                              @endif
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
               {{ $cons->links() }} 

               {{-- {!! $cons->render() !!} --}}
            </div>
        </div>

            {{-- {{ $cons }} --}}



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
@if(Auth::user()->role == 2)

      <!-- Modal -->
<div class="modal fade" id="addContribution" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New {{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" method="post" action="{{ route($route) }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="card-body">
              <div class="form-group">
                <label for="academic_year">Choose Academic Year</label>
                <select name="academic_year" id="academic_year" class="form-control">

                  @foreach($ays as $ay)
                  <option value="{{ $ay->year }}">{{ $ay->year }}</option>

                  @endforeach

                </select>
              </div>
              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Save the Tree" name="title" value="{{ old('title') }}">
              </div>
              <div class="form-group">
               <label for="file">File</label>
                <input type="file" class="form-control" id="file" placeholder="save-the-tree.doc" name="doc" value="{{ old('doc') }}">
              </div>
              <div class="form-group">
                <label for="photo">Photos</label>
                <input type="file" class="form-control" id="photo" placeholder="" name="file[]" multiple="">
              </div>
              
            </div>
            <!-- /.card-body -->  
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add {{ $title }}</button>
          </div>
      </form>
    </div>
  </div>
</div>

@endif

@endsection
