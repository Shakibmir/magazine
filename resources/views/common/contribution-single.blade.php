@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
<style>
.ekko-lightbox.modal{z-index: 1199;}
.ekko-lightbox-nav-overlay a span {
filter: brightness(80%);
}

.hovereffect {
width:100%;
height:100%;
float:left;
overflow:hidden;
position:relative;
text-align:center;
cursor:default;
}

.hovereffect .overlay {
width:100%;
height:100%;
position:absolute;
overflow:hidden;
top:0;
left:0;
opacity:0;
background-color:rgba(0,0,0,0.5);
-webkit-transition:all .4s ease-in-out;
transition:all .4s ease-in-out
}

.hovereffect img {
display:block;
position:relative;
-webkit-transition:all .4s linear;
transition:all .4s linear;
}

.hovereffect h2 {
text-transform:uppercase;
color:#fff;
text-align:center;
position:relative;
font-size:17px;
background:rgba(0,0,0,0.6);
-webkit-transform:translatey(-100px);
-ms-transform:translatey(-100px);
transform:translatey(-100px);
-webkit-transition:all .2s ease-in-out;
transition:all .2s ease-in-out;
padding:10px;
}

.hovereffect div.info {
text-decoration:none;
display:inline-block;
text-transform:uppercase;
color:#fff;
border:1px solid #fff;
background-color:transparent;
opacity:0;
filter:alpha(opacity=0);
-webkit-transition:all .2s ease-in-out;
transition:all .2s ease-in-out;
margin:50px 0 0;
padding:7px 14px;
}

.hovereffect div.info:hover {
box-shadow:0 0 5px #fff;
}

.hovereffect:hover img {
-ms-transform:scale(1.2);
-webkit-transform:scale(1.2);
transform:scale(1.2);
}

.hovereffect:hover .overlay {
opacity:1;
filter:alpha(opacity=100);
}

.hovereffect:hover h2,.hovereffect:hover div.info {
opacity:1;
filter:alpha(opacity=100);
-ms-transform:translatey(0);
-webkit-transform:translatey(0);
transform:translatey(0);
}

.hovereffect:hover div.info {
-webkit-transition-delay:.2s;
transition-delay:.2s;
}

</style>
@endsection
@section('content')


<div class="row">


  <div class="col-md-9 mx-auto">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user1-128x128.jpg" alt="User Image">
                  <span class="username"><a href="#">{{ $con->user->name }} #{{ $con->id }}</a></span>
                  <span class="description">Shared publicly - 7:30 PM Today</span>
                </div>
                <!-- /.user-block -->
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Mark as read">
                    <i class="fa fa-circle-o"></i></button>
                  {{-- <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> --}}
                  @if (Auth::user()->role == 2)
                    <a href="{{ route($eroute,$con->id) }}" class="btn btn-tool"><i class="fas fa-edit"></i>
                  @elseif (Auth::user()->role > 2)

                    @if($con->status > 2)
                      <button type="button" class="btn btn-success btn-sm" disabled="">Approved</button>
                    @else
                      <a href="{{ route($aroute,$con->id) }}" class="btn btn-success btn-sm">Approve</a>
                    @endif

                  @endif
                  
                  </a>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- post text -->
                <div class="row">
                <div class="col-md-6">
                <p><b>Title: </b> {{ $con->title }}</p>
                <p><b>Department: </b> {{ $con->user->dep->name }}</p>
                <p><b>Submitted At: </b> {{ $con->created_at }}</p>
                </div>

                <div class="col-md-6">
                  <p><b>Academic Year: </b> {{ $con->acyear->year }}
                  <p><b>Contribution ID: </b> {{ $con->id }}</p>
                  <p><b>Last Modified At: </b> {{ $con->updated_at }}</p>
                </div>

                </div>

                <!-- Social sharing buttons -->
               {{--  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i> Share</button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-thumbs-o-up"></i> Like</button> --}}
                <span class="float-right text-muted">{{-- 45 likes -  --}}{{ $comcount }} comments</span>
              </div>
                  <div class="card-footer bg-white">
                  <ul class="mailbox-attachments clearfix">

                    @php
                    if ($con->status >2){
                      $filepath = 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/';
                    }else{
                      $filepath = 'upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/';
                    }
                    $hasfile = 0;
                    if(File::exists($filepath)){
                      if($con->file_name){
                        $filetype = mime_content_type($filepath.$con->file_name);
                        $filesize = filesize($filepath.$con->file_name);
                        $filesize = round($filesize / 1024, 2);
                      }
                      $hasfile = 1;
                    }
                    @endphp

                    @if ( $hasfile && $con->file_name && mime_content_type($filepath.$con->file_name) == 'application/pdf')
                      {{-- expr --}}
                    
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $con->file_name }}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {{ $con->file_name }}</a>
                            <span class="mailbox-attachment-size">
                              {{ $filesize }} KB
                              <a href="{{ route('home') }}/{{ $filepath }}{{ $con->file_name }}" class="btn btn-default btn-sm float-right" style="word-break: break-all;"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    @elseif( $hasfile && $con->file_name && ($filetype == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $filetype == 'application/msword'))
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $con->file_name }}" class="mailbox-attachment-name" style="word-break: break-all;"><i class="fas fa-paperclip"></i> {{ $con->file_name }}</a>
                            <span class="mailbox-attachment-size">
                              {{ $filesize }} KB
                              <a href="{{ route('home') }}/{{ $filepath }}{{ $con->file_name }}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>

                    @endif
                    @if ($conimgs)

                    @foreach ($conimgs as $conimg)
                      @php
                        $imgsize = filesize($filepath.$conimg->name);
                        $imgsize = round($imgsize / 1024 / 1024, 2);
                      @endphp

                      <li>
                        <div class="hovereffect">
                      <span class="mailbox-attachment-icon has-img">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $conimg->name }}" data-toggle="lightbox">
                          <img src="{{ route('home') }}/{{ $filepath }}{{ $conimg->name }}" alt="{{ $conimg->name }}" class="img-fluid">
                        </a>
                      </span>
                      <a href="{{ route('home') }}/{{ $filepath }}{{ $conimg->name }}" data-toggle="lightbox" data-gallery="example-gallery" class="overlay">
                             {{-- <h2>Hover effect 1</h2> --}}
                             <div class="info" href="#">View Image</div>
                          </a>
                      </div>

                      <div class="mailbox-attachment-info">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $conimg->name }}" class="mailbox-attachment-name" style="word-break: break-all;"><i class="fas fa-camera-retro"></i> {{ $conimg->name }}</a>
                            <span class="mailbox-attachment-size">
                              {{ $imgsize }} MB 
                              <a href="{{ route('home') }}/{{ $filepath }}{{ $conimg->name }}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    @endforeach
                    @endif
                  </ul>
                </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments direct-chat-primary">
                <div class="direct-chat-messages" style="height: 100%;">
                  @foreach($comments as $com)

                  <!-- Message. Default to the left -->
                  <!-- Message to the right for the user-->
                  <div class="direct-chat-msg {{ $com->user_id == Auth::user()->id ? 'right' : '' }} ">
                    <div class="direct-chat-info clearfix">
                      <span class="direct-chat-name {{ $com->user_id == Auth::user()->id ? 'float-right' : 'float-left' }}">{{ $com->user->name }}</span>
                      <span class="direct-chat-timestamp {{ $com->user_id == Auth::user()->id ? 'float-left' : 'float-right' }}">23 Jan 2:05 pm</span>
                    </div>
                    <!-- /.direct-chat-info -->
                    <img class="direct-chat-img" 

                    @if ($com->user->role > 2)
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user3-128x128.jpg"
                    @else
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user1-128x128.jpg"
                    @endif

                     alt="Message User Image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      {{ $com->comment }}
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  @endforeach

                </div>
                <div class="float-right">
                 {{ $comments }}
                </div>
              </div>
              <!-- /.card-footer -->

              @if (Auth::user()->role != 4)
              <div class="card-footer">

                 @if (Auth::user()->role == 2 && ($con->status == 3 || $con->status == 1)) 
                  <div class="alert alert-danger">
                    Comments are enabled only after review.
                  </div>
                 @endif
                <form action="{{ route($route, $con->id) }}" method="post">
                  @csrf
                  <img class="img-fluid img-circle img-sm" @if (Auth::user()->role > 2)
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user3-128x128.jpg"
                    @else
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user1-128x128.jpg"
                    @endif alt="Alt Text">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push form-group">
                    <textarea class="form-control form-control-lg" rows="4" name="comment" placeholder="Type something and hit send to post comments..." @if (Auth::user()->role == 2 && ($con->status == 3 || $con->status == 1)) disabled @endif></textarea>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary btn-lg" @if (Auth::user()->role == 2 && ($con->status == 3 || $con->status == 1)) disabled @endif>Add comment</button>
                  </div>
                </form>
              </div>
              @endif
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->


   </div>
     

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script>
  $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox();
  });
</script>
@endsection