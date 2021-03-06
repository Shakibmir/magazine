@extends('layouts.master')
@section('styles')

  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css')}}">

@endsection
@section('content')
@if( (Auth::user()->role > 3 ))
<!-- SELECT2 EXAMPLE -->
  <div class="card card-default">
    <div class="card-header">

      @if ($rptype==1 || $rptype==3 || $rptype==4)
        <h3 class="card-title">Change Academic</h3>
      @else
      <h3 class="card-title">Select Academic Year and Department to genarate a report</h3>
      @endif
      

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <form role="form" method="post" action="{{ route(Request::route()->getName()) }}" >
      @csrf
    <div class="card-body">
      <div class="row">
      @if ($rptype==1 || $rptype==3 || $rptype==4)

       <div class="col-md-8">
          <div class="form-group">
            <select class="form-control" name="academic_year">
              <option selected="selected">Choose Academic Year</option>
              @foreach($ays as $ay)
              <option value="{{ $ay->id }}"
                @if ($cay)
                {{ $ay->id == $cay->id ? 'selected="selected"' : '' }}
              @endif >{{ $ay->year }}</option>
              @endforeach
            </select>
          </div>
          <!-- /.form-group -->
        </div>
      @else
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control" name="academic_year">
              <option selected="selected">Choose Academic Year</option>
              @foreach($ays as $ay)
              <option value="{{ $ay->id }}" @if ($rptype==2)
                {{ $ay->id == $pay->id ? 'selected="selected"' : '' }}
                @elseif ($cay)
                {{ $ay->id == $cay->id ? 'selected="selected"' : '' }}
              @endif >{{ $ay->year }}</option>
              @endforeach
            </select>
          </div>
          <!-- /.form-group -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <div class="form-group">
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
      @endif
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
          @if ($rptype==4)
            <h3 class="card-title d-inline">{{ $title }} for {{ $cay->year }}</h3>
          @else
            <h3 class="card-title d-inline">List of {{ $title }} in each department for {{ $cay->year }}</h3>
          @endif
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

                    if ($totalcon){
                      $scper = (100/$totalcon);
                    }else{
                    $scper = 0;
                    }

                    
                      
                    @endphp
                    @foreach($reps as $rp => $contributions)
                    @if (Auth::user()->role == 1 || Auth::user()->role == 3)

                    @if (Auth::user()->department_id == $rp)
                     <tr class="align-middle">
                        <td class="align-middle">
                      {{ Auth::user()->dep->name }}
                      </td>
                        <td class="align-middle">{{ round((count($contributions))*$scper, 2) }} %</td>
                      </tr>
                    @endif


                    @else
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
                    @endif
                    @endforeach
                  </tbody>
                </table>

              @elseif($rptype==4)
              <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>Totals Number of Contribution(s) without comments</th>
                        <th>Number of Contribution(s) without comments after 14 days</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="align-middle">
                      <td class="align-middle">
                        {{ $comcons }}
                      </td>
                      <td class="align-middle">{{ $nocoms }}</td>
                    </tr>
                  </tbody>
                </table>
              @endif

            </div>
        </div>
          <!-- /.card-body -->
      </div>
    </div>
  </div>

  @endif


  @if($rptype==3 || $rptype==4)

  <div class="card card-danger">
    <div class="card-header">
       @if($rptype==3)
      <h3 class="card-title">Percentage of Contributions in Chart</h3>
      @elseif($rptype==4)
         <h3 class="card-title">Contributions Comment statistics </h3>
      @endif

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">

      @if($rptype==3)
        <canvas id="pieChart" style="height: 306px; width: 612px;" width="765" height="382"></canvas>
      @elseif($rptype==4)
        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
      @endif
    </div>
    <!-- /.card-body -->
  </div>

  @endif


  @if ($rptype==1)
    <!-- Donut chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-bar-chart-o"></i>
                  Department wise contributions
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="bar-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
  @endif
@endsection

@section('scripts')


@if ($rptype==1)
<!-- FLOT CHARTS -->
<script src="{{ asset('assets/plugins/flot/jquery.flot.min.js') }}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{ asset('assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{ asset('assets/plugins/flot/jquery.flot.pie.min.js') }}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{ asset('assets/plugins/flot/jquery.flot.categories.min.js') }}"></script>

<script>

  function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
  /*
     * BAR CHART
     * ---------
     */

    var bar_data = {
      data : [

      @foreach($reps as $rp => $contributions) ['@foreach($deps as $dep) @if($dep->id == $rp) {{ $dep->name }} @endif @endforeach ',{{ count($contributions) }}], @endforeach ],
      color: '#007bff'
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
    /* END BAR CHART */

      /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }

</script>
@endif

 @if($rptype==3 || $rptype==4 )


 <!-- ChartJS 1.0.1 -->
<script src="{{ asset('assets/plugins/chartjs-old/Chart.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>


<script>

  function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}



@if($rptype==4)
// Donut Chart
  var donut = new Morris.Donut({
    element  : 'sales-chart',
    resize   : true,
    colors   : ['#dc3545', '#007bff'],
    data     : [
      { label: '14 days Late', value: {{ $nocoms }} },
      { label: 'No Comment', value: {{ $comcons-$nocoms }} }
    ],
    hideHover: 'auto'
  })

  // Fix for charts under tabs
  $('.box ul.nav a').on('shown.bs.tab', function () {
    area.redraw()
    donut.redraw()
    line.redraw()
  })
@endif

@if($rptype==3)
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

@endif

</script>
 @endif
@endsection
