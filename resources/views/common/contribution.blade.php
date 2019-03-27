@extends('layouts.master')

@section('styles')

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" integrity="sha256-e47xOkXs1JXFbjjpoRr1/LhVcqSzRmGmPqsrUQeVs+g=" crossorigin="anonymous" />




<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js" integrity="sha256-cs4thShDfjkqFGk5s2Lxj35sgSRr4MRcyccmi0WKqCM=" crossorigin="anonymous"></script> --}}

{{-- <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/extensions/responsive/css/dataTables.responsive.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.css')}}"> --}}

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.css"/>

@endsection
@section('content')


<div class="row">
  @if(Auth::user()->role > 3)
    <div class="col-md-12">
      <ul class="nav nav-pills nav-justified">
        <li class="nav-item">
          <a class="nav-link {{Request::route()->getName() == 'contributions' ? 'active' : '' }}" href="{{ route('contributions') }}">All ({{ $allcons }})</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{Request::route()->getName() == 'approved-contributions' ? 'active' : '' }}" href="{{ route('approved-contributions') }}">Approved ({{ $apvcons }})</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{Request::route()->getName() == 'commented-contributions' ? 'active' : '' }}" href="{{ route('commented-contributions') }}">Commented ({{ $comcons }})</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{Request::route()->getName() == 'pending-contributions' ? 'active' : '' }}" href="{{ route('pending-contributions') }}">Pending ( {{ $pencons }})</a>
        </li>
      </ul>
      <br>
    </div>
    @endif

  @if(Auth::user()->role == 2)

    @if ($cayf)
      @php
        $codiff = Carbon\Carbon::today()->diffInDays($cayf->opening_date, false);
        $ccdiff = Carbon\Carbon::today()->diffInDays($cayf->closing_date, false);
        $cfdiff = Carbon\Carbon::today()->diffInDays($cayf->final_date, false);
      @endphp
      <div class="col-md-12">

        @if($codiff>0)
          <div class="alert alert-primary" role="alert">
            Submission for Academic Year {{ $cayf->year }}  Starts in  {{ $codiff }}  Days!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

        @elseif($ccdiff>=0)
        <div class="alert alert-success" role="alert">
            New Submission for Academic Year {{ $cayf->year }} Has Started. You have {{ $ccdiff }} Days left to Contribute!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @elseif($cfdiff>=0)
        <div class="alert alert-warning" role="alert">
            New Submission for Academic Year {{ $cayf->year }} Has Ended. For Existing Submission(s) You have {{ $cfdiff }} Days left to update!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @else
        <div class="alert alert-danger" role="alert">
            Submission for Academic Year {{ $cayf->year }} Has Ended. You can't upload or Edit Contributions after the Final closure date!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @endif
      </div>
    @else
      <div class="alert alert-secondary" role="alert">
          Sorry there is no Active academic year!
      </div>
    @endif
  @endif
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">List of {{ $title }}s from Academic Year {{ $cay->year }}</h3>

                @if(Auth::user()->role == 2)
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addContribution"><i class="far fa-calendar-plus mr-2"> </i>   Add Contribution</button>
                @endif

                <form id="year-change" method="post" action="{{ route(Request::route()->getName()) }}">
                  @csrf

                <div class="form-group mt-2">

                  

                  <label>Select Academic Year</label>

                  <select class="form-control float-right" id="academic-year" name="year" onchange="event.preventDefault(); document.getElementById('year-change').submit();">
                    @foreach ($ays as $ay)
                        <option value="{{ $ay->year }}" {{ $ay->id == $cay->id ? 'selected="selected"' : '' }}>{{ $ay->year }}</option>
                    @endforeach
                  </select>

                  
                  
                </div>
                </form>
            </div>
              <!-- /.card-header -->
        @if(Auth::user()->role > 4)
          <form 
          @if (Request::route()->getName() == 'approved-contributions')
          action="{{ route('download-approved') }}"
          @else 
          action="{{ route('approve-contributions') }}" 
          @endif

          method="post">
            @csrf
        @endif
            <div class="card-body">
                {{-- <div class="table-responsive"> --}}
                     <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                              @if(Auth::user()->role > 4)
                                <th><input type="checkbox" id="select-all"></th>
                              @endif
                                <th style="width: 10px"><i class="nav-icon fas fa-file-contract"></i></th>
                                <th>Title</th>
                                <th style="width: 40px">Academic Year</th>
                                <th>Date Submitted</th>
                                <th style="width: 40px">Status</th>
                                @if(Auth::user()->role > 4)
                                <th>Submitted By</th>
                                <th>Department</th>
                                @endif
                                <th>Time Remaining</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          

                        @foreach($cons as $con)


                          <tr class="align-middle">
                            @if(Auth::user()->role > 4)
                            <td class="align-middle"><input type="checkbox" class="checkthis" id="exampleCheck{{ $con->id }}" name="id[]" value="{{ $con->id }}"></td>
                            @endif
                            <td class="align-middle"><a href="{{ route($sroute,$con->id) }}">{{ $con->id }}</a></td>
                            <td class="align-middle"><a href="{{ route($sroute,$con->id) }}">{{ $con->title }}</a></td>
                            <td class="align-middle">{{ $con->acyear->year }}</td>
                            <td class="align-middle">{{ $con->created_at }}</td>
                            <td class="align-middle">

                              @if($con->status == 1)

                              <span class="badge bg-primary">Submitted</span>

                              @elseif($con->status == 2)

                              <span class="badge bg-warning">Commented</span>

                              @elseif($con->status == 3)

                              <span class="badge bg-success">Accepted</span>

                              @elseif($con->status == 4)

                              <span class="badge bg-success">Commented + Accepted</span>

                              @else

                              <span class="badge bg-danger">Rejected</span>
                              @endif

                            </td>
                            @if(Auth::user()->role > 4)
                            <td class="align-middle">{{ $con->user->name }}</td>
                            <td class="align-middle">{{ $con->user->dep->name }}</td>
                            @endif

                            @php
                              $odiff = Carbon\Carbon::today()->diffInDays($ay->opening_date, false);
                              $cdiff = Carbon\Carbon::today()->diffInDays($con->acyear->closing_date, false);
                              $fdiff = Carbon\Carbon::today()->diffInDays($con->acyear->final_date, false);
                            @endphp
                            <td class="align-middle">

                                
                                <span class="badge
                                @if($cdiff>=0)

                                bg-success
                                    
                                @elseif($fdiff>=0)

                                bg-warning

                                @else
                                 bg-danger

                                @endif

                                 ">
                                 {{ $fdiff }} days
                                </span></td>

                            <td>
                              @if(Auth::user()->role == 2)
                                @if($fdiff>=0 || $odiff < 0)
                                  <a href="{{ route($eroute,$con->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                @else
                                  <button type="button" class="btn btn-primary btn-sm" disabled="">Edit</button>
                                @endif
                                @if($con->status == 2 || $con->status == 4)
                                  <a href="{{ route($sroute,$con->id) }}" class="btn btn-warning btn-sm">Interact</a>
                                @else
                                  <button type="button" class="btn btn-warning btn-sm" disabled="">Interact</button>
                                @endif
                              @endif

                              @if(Auth::user()->role > 4)
                              <a href="{{ route($sroute,$con->id) }}" class="btn btn-primary btn-sm">Comment</a>
                                @if($con->status > 2)
                                  <button type="button" class="btn btn-success btn-sm" disabled="">Approved</button>
                                @else
                                  <a href="{{ route($aroute,$con->id) }}" class="btn btn-success btn-sm">Approve</a>
                                @endif
                              @endif
                                {{-- <a data-href="{{ route($droute,$post->id) }}" class="cat-delete text-danger"> Delete</a> --}}
                            </td>
                          </tr>

                        @endforeach
                        </tbody>
                    </table>
                {{-- </div> --}}
            </div>

            
              <!-- /.card-body -->
            <div class="card-footer clearfix">
              @if(Auth::user()->role > 4)

              @if (Request::route()->getName() == 'approved-contributions')
                <button type="submit" class="btn btn-success btn-sm" id="approvebtn" disabled="">Download Selected Contributions</button>
              @else 
                <button type="submit" class="btn btn-success btn-sm" id="approvebtn" disabled="">Approve Selected Contributions</button>
              @endif

               @if ($apvcons > 0)
                  <a href="{{ route('zip') }}"  class="btn btn-primary btn-sm ml-2" id="download"><i class="fas fa-file-archive"></i> Download Approved Contributions</a>
                @else
                <button type="button" class="btn btn-primary btn-sm ml-2" id="disdownload" disabled=""><i class="fas fa-file-archive"></i> Download Approved Contributions</button>
              @endif 
                
              @endif

              <div class="float-right">{{ $cons->links() }}</div>
               
               {{-- {!! $cons->render() !!} --}}
            </div>
        @if(Auth::user()->role > 4)
          </form>
        @endif
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
              @if(Auth::user()->role == 2)

                @if ($cayf)
                  <div class="col-md-12">

                    @if($codiff>0)
                      <div class="alert alert-primary" role="alert">
                        Submission for Academic Year {{ $cayf->year }}  Starts in  {{ $codiff }}  Days!
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> --}}
                      </div>  
                    @elseif($ccdiff>=0)
                    <div class="alert alert-success" role="alert">
                        New Submission for Academic Year {{ $cayf->year }} Has Started. You have {{ $ccdiff }} Days left to Submit New Contribution!
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>

                    @elseif($cfdiff>=0)
                    <div class="alert alert-warning" role="alert">
                        New Submission for Academic Year {{ $cayf->year }} Has Ended. For Existing Submission(s) You have {{ $cfdiff }} Days left to update!
                       {{--  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>

                    @else
                    <div class="alert alert-danger" role="alert">
                        Submission for Academic Year {{ $cayf->year }} Has Ended. You can't upload or Edit Contributions after the Final closure date!
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>

                    @endif
                  </div>
                @else
                  <div class="alert alert-secondary" role="alert">
                      Sorry there is no Active academic year To allow Submission!
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                @endif
              @endif
              <div class="form-group">
                <label for="academic_year">Choose Academic Year</label>
                <select name="academic_year" id="academic_year" class="form-control">

                  @foreach($ays as $ay)

                  @php
                   $nodiff = Carbon\Carbon::today()->diffInDays($ay->opening_date, false);
                   $ncdiff = Carbon\Carbon::today()->diffInDays($ay->closing_date, false);
                   $nfdiff = Carbon\Carbon::today()->diffInDays($ay->final_date, false);
                  @endphp
                  <option value="{{ $ay->id }}" {{ $ncdiff < 0 || $nodiff > 0  ? 'disabled' : '' }}>{{ $ay->year }}</option>

                  

                  @endforeach

                </select>
              </div>
              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Some Awesome Title" name="title" value="{{ old('title') }}">
              </div>
              <div class="form-group">
               <label for="file">File</label>
                <input type="file" class="form-control" id="file" placeholder="something-awesome.doc" name="doc" value="{{ old('doc') }}">
              </div>
              <div class="form-group">
                <label for="photo">Photos</label>
                <input type="file" class="form-control" id="photo" placeholder="" name="file[]" multiple="">
              </div>

              <div class="form-group">
                <input type="checkbox" class="form-check-input" id="tc" placeholder="" name="Terms_and_Conditions">
                <label class="form-check-label" for="tc">Agree to our Terms and Conditions</label>
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

@section('scripts')

{{-- <!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.js')}}"></script> --}}


<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script>
  $(function () {
    $("#example1").addClass( 'nowrap' ).DataTable({
      "responsive": true
    });
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false
    // });
  });
</script>

<script>

@if (Auth::user()->role == 2)
  {{-- expr --}}

{{-- $(document).ready(function() {
  $("#academic-year").change(function(){
    if ($(this).val()!='') {
      var year = $(this).val();
      var url = '{{ route("stdcontributions-year", ":year") }}';

      url = url.replace(':year', year);

      window.location.href=url;
    }
  });
});

--}}

@elseif(Auth::user()->role == 5)

{{-- $(document).ready(function() {
  $("#academic-year").change(function(){
    if ($(this).val()!='') {
      var year = $(this).val();
      var url = '{{ route("contributions-year", ":year") }}';

      url = url.replace(':year', year);

      window.location.href=url;
    }
  });
});

--}}

@endif

var checkBoxes = $('tbody .checkthis');
checkBoxes.change(function () {
    $('#approvebtn').prop('disabled', checkBoxes.filter(':checked').length < 1);
});
$('tbody .checkthis').change();

// Listen for click on toggle checkbox
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $('.checkthis').each(function() {
            this.checked = true;                        
        });

        $('#approvebtn').prop('disabled', false);

    } else {
        $('.checkthis').each(function() {
            this.checked = false;                       
        });

        $('#approvebtn').prop('disabled', true);
    }
});

</script>


@endsection
