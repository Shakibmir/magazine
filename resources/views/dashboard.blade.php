@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="alert alert-success">
      Statistic shown in the Dashboard is for the current Academic year <strong>({{ $cay->year }})!</strong> for Department: <strong> @isset ($dep) {{ Auth::user()->dep->name }}  @endisset</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</div>


@if(Auth::user()->role >= 3)
<div class="row">
  {{-- <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $totalcons }}</h3>

        <p>Total Number of Contributions</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
      </div>
      <a href="{{ route('contributions') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col --> --}}
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $allcons }}</h3>

        <p>Contributions in {{ $cay->year }}</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file-contract"></i>
      </div>
      <a href="{{ route($rcon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-success" style="filter: brightness(90%);">
      <div class="inner">
        <h3>{{ $apvcons }}</h3>

        <p>Approved Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-clipboard-check"></i>
      </div>
      <a href="{{ route($racon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $comcons }}</h3>

        <p>Commented Contributions</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
        {{-- <i class="fas fa-file-invoice"></i> --}}
      </div>
      <a href="{{ route($rccon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $pencons }}</h3>

        <p>Pending Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file"></i>
      </div>
      <a href="{{ route($rpcon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $userc }}</h3>

        <p>Total Students</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <a href="{{ route($rcon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $nocoms }}</h3>

        <p>Not commented</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
      </div>
      <a href="{{ route($rcon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $nocomsl }}</h3>

        <p>Not commented after 14 days</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
      </div>
      <a href="{{ route($rcon) }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-dark">
      <div class="inner">
        <h3>{{ $ureps }}</h3>

        <p>Total Contributors</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-edit"></i>
      </div>
      <a href="{{ route('contributor-number') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>
@elseif(Auth::user()->role == 4)

@elseif(Auth::user()->role == 3)

@elseif(Auth::user()->role == 2)
  <div class="row">
    @if ($cay)
      @php
        $codiff = Carbon\Carbon::today()->diffInDays($cay->opening_date, false);
        $ccdiff = Carbon\Carbon::today()->diffInDays($cay->closing_date, false);
        $cfdiff = Carbon\Carbon::today()->diffInDays($cay->final_date, false);
      @endphp
      <div class="col-md-12">

        @if($codiff>0)
          <div class="alert alert-primary" role="alert">
            Submission for Academic Year {{ $cay->year }}  Starts in  {{ $codiff }}  Days!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>  
        @elseif($ccdiff>=0)
        <div class="alert alert-success" role="alert">
            New Submission for Academic Year {{ $cay->year }} Has Started. You have {{ $ccdiff }} Days left to Contribute!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @elseif($cfdiff>=0)
        <div class="alert alert-warning" role="alert">
            New Submission for Academic Year {{ $cay->year }} Has Ended. For Existing Submission(s) You have {{ $cfdiff }} Days left to update!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @else
        <div class="alert alert-danger" role="alert">
            Submission for Academic Year {{ $cay->year }} Has Ended. You can't upload or Edit Contributions after the Final closure date!
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
  </div>
  <div class="row"> 
    <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $allcons }}</h3>

        <p>Contributions in {{ $cay->year }}</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file-contract"></i>
      </div>
      <a href="{{ route('stdcontributions') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-success" style="filter: brightness(90%);">
      <div class="inner">
        <h3>{{ $apvcons }}</h3>

        <p>Approved Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-clipboard-check"></i>
      </div>
      <a href="{{ route('stdcontributions') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $comcons }}</h3>

        <p>Commented Contributions</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
        {{-- <i class="fas fa-file-invoice"></i> --}}
      </div>
      <a href="{{ route('stdcontributions') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $pencons }}</h3>

        <p>Pending Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file"></i>
      </div>
      <a href="{{ route('stdcontributions') }}" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div> 
@else
<div class="row">
    <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $allcons }}</h3>

        <p>Contributions in {{ $cay->year }} in @isset ($dep) {{ Auth::user()->dep->name }}  @endisset Department</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file-contract"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-success" style="filter: brightness(90%);">
      <div class="inner">
        <h3>{{ $apvcons }}</h3>

        <p>Approved Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-clipboard-check"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $comcons }}</h3>

        <p>Commented Contributions</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
        {{-- <i class="fas fa-file-invoice"></i> --}}
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $pencons }}</h3>

        <p>Pending Contributions</p>
      </div>
      <div class="icon">
        {{-- <i class="fas fa-file-signature"></i> --}}
        <i class="fas fa-file"></i>
      </div>
      <a href="$" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $userc }}</h3>

        <p>Total Students in @isset ($dep) {{ Auth::user()->dep->name }}  @endisset Department</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $nocoms }}</h3>

        <p>Not commented</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $nocomsl }}</h3>

        <p>Not commented after 14 days</p>
      </div>
      <div class="icon">
        <i class="fas fa-file-signature"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box bg-dark">
      <div class="inner">
        <h3>{{ $ureps }}</h3>

        <p>Total Contributors</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-edit"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>
@endif


                  
@endsection
