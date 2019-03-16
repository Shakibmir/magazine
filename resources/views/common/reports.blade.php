@extends('layouts.master')
@section('styles')

@endsection
@section('content')
@if( (Auth::user()->role == 1 || Auth::user()->role > 4 )  && ($rptype==2 || $rptype==0))
<!-- SELECT2 EXAMPLE -->
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Select Academic and Department for the report</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <form role="form" method="post" action="{{ route($route) }}" >
      @csrf
    <div class="card-body">
      <div class="row">
      
        <div class="col-md-4">
          <div class="form-group">
            {{-- <label>Academic Year</label> --}}
            {{-- <select class="form-control select2" style="width: 100%;" name="academic_year">
              <option selected="selected">Choose Academic Year</option>
              @foreach($ays as $ay)
              <option value="{{ $ay->id }}">{{ $ay->year }}</option>
              @endforeach
            </select> --}}

            <select class="form-control" name="academic_year">
              <option selected="selected">Choose Academic Year</option>
              @foreach($ays as $ay)
              <option value="{{ $ay->id }}" @if ($rptype==2)
                {{ $ay->id == $pay->id ? 'selected="selected"' : '' }}
              @endif >{{ $ay->year }}</option>
              @endforeach
            </select>
          </div>
          <!-- /.form-group -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <div class="form-group">
            {{-- <label>Department</label> --}}
            {{-- <select class="form-control select2" style="width: 100%;" name="department">
              <option selected="selected">Choose a Department</option>
              @foreach($deps as $dp)
              <option value="{{ $dp->id }}">{{ $dp->name }}</option>
              @endforeach   
            </select> --}}

            <select class="form-control" name="department">
              <option selected="selected">Choose a Department</option>
                @foreach($deps as $dp)
                <option value="{{ $dp->id }}" @if ($rptype==2)
                {{ $dp->id == $pdp->id ? 'selected="selected"' : '' }}
              @endif>{{ $dp->name }}</option>
                @endforeach  
            </select>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary">Submit</button>
          </div>
        </div>
        <!-- /.col -->
      
      </div>
      <!-- /.row -->
    </div>
    </form>
  </div>
  <!-- /.card -->

@endif

  @if($rptype==0)

  @else

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
            <h3 class="card-title d-inline">List of {{ $title }} in each department</h3>
        </div>
          <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">


              

              @if($rptype==1)
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Percentage of Contributions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($reps as $rp => $contributions)
                      <tr class="align-middle">
                        <td class="align-middle">
                          @foreach($deps as $dep)
                            @if($dep->id == $rp)
                              {{ $dep->name }}
                            @endif
                          @endforeach
                        </td>
                        <td class="align-middle">{{ count($contributions) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @elseif($rptype==2)

                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Department Name</th>
                        <th>Number of Contributors</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="align-middle">
                      <td class="align-middle">
                        {{ $pay->year }}
                      </td>
                      <td class="align-middle">
                        {{ $pdp->name }}
                      </td>
                      <td class="align-middle">{{ $ureps }}</td>
                    </tr>
                  </tbody>
                </table>

              @elseif($rptype==3)
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Number of Contributions</th>
                    </tr>
                  </thead>
                  <tbody>

                    @php

                    $totalcon = 0;
                    foreach($reps as $rp => $contributions){

                      $totalcon = $totalcon + $contributions->count();

                    }

                    $scper = (100/$totalcon);
                      
                    @endphp
                    @foreach($reps as $rp => $contributions)
                      <tr class="align-middle">
                        <td class="align-middle">
                          @foreach($deps as $dep)
                            @if($dep->id == $rp)
                              {{ $dep->name }}
                            @endif
                          @endforeach
                        </td>
                        <td class="align-middle">{{ round((count($contributions))*$scper, 2) }} %</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

              @elseif($rptype==4)
              {{ $comcons }}
              {{ $nocoms }}


              @endif

            </div>
        </div>
          <!-- /.card-body -->
      </div>
    </div>
  </div>

  @endif


  @if($rptype==3)

  <div class="card card-danger">
    <div class="card-header">
      <h3 class="card-title">Percentage of Contributions in Chart</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <canvas id="pieChart" style="height: 306px; width: 612px;" width="765" height="382"></canvas>
    </div>
    <!-- /.card-body -->
  </div>

  @endif
@endsection

@section('scripts')

 @if($rptype==3)


 <!-- ChartJS 1.0.1 -->
<script src="{{ asset('assets/plugins/chartjs-old/Chart.min.js') }}"></script>


<script>

  function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
//-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [

      @foreach($reps as $rp => $contributions)
      {
        value    : {{ round((count($contributions))*$scper, 2) }},
        color    : getRandomColor(),
        highlight: getRandomColor(),
        label    : @foreach($deps as $dep)
                      @if($dep->id == $rp)
                        '{{ $dep->name }}'
                      @endif
                    @endforeach
      },
                      
      @endforeach
      // {
      //   value    : 700,
      //   color    : getRandomColor(),
      //   highlight: getRandomColor(),
      //   label    : 'Chrome'
      // },
      // {
      //   value    : 500,
      //   color    : getRandomColor(),
      //   highlight: getRandomColor(),
      //   label    : 'IE'
      // },
      // {
      //   value    : 400,
      //   color    : getRandomColor(),
      //   highlight: getRandomColor(),
      //   label    : 'FireFox'
      // },
      // {
      //   value    : 600,
      //   color    : '#00c0ef',
      //   highlight: '#00c0ef',
      //   label    : 'Safari'
      // },
      // {
      //   value    : 300,
      //   color    : '#3c8dbc',
      //   highlight: '#3c8dbc',
      //   label    : 'Opera'
      // },
      // {
      //   value    : 100,
      //   color    : '#d2d6de',
      //   highlight: '#d2d6de',
      //   label    : 'Navigator'
      // }
    ]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 200,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)

</script>
 @endif
@endsection
