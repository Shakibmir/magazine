@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />

<style>
.ekko-lightbox.modal{z-index: 1199;}
.ekko-lightbox-nav-overlay a span {
filter: brightness(80%);
}

.hovereffect {
max-height: 150px;
min-height: 150px;
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
max-height: 150px;
min-height: 150px;
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
    <div class="col-md-8 mx-auto">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title d-inline">Update {{ $title }}</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
               <form role="form" method="post" action="{{ route($uroute, $ay->id) }}" id="submit" enctype="multipart/form-data">
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


              @if($isDep == 2)



              <div class="card-body">
                  <div class="form-group">
                    <label for="year">Academic Year</label>


                    <select id="academic_year" class="form-control" disabled>

                    @foreach($ays as $aye)
                    <option value="{{ $aye->id }}" {{ $aye->id == $ay->academic_year ? 'selected="selected"' : '' }}>{{ $aye->year }}</option>

                    @endforeach

                    </select>
                    
                  </div>
                  <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Some Awesome Title" name="title" value="{{ $ay->title }}">
                  </div>
                    @php

                    if ($ay->status >2){
                    $filepath = 'upload/approved/'.$ay->acyear->year.'/con_'.$ay->id.'_user_'.$ay->user_id.'/';
                    }else{
                    $filepath = 'upload/'.$ay->acyear->year.'/con_'.$ay->id.'_user_'.$ay->user_id.'/';
                    }
                    if($ay->file_name){
                      $filetype = mime_content_type($filepath.$ay->file_name);
                      $filesize = filesize($filepath.$ay->file_name);
                      $filesize = round($filesize / 1024, 2);
                    }

                    
                    @endphp

                    @if ($ay->file_name && mime_content_type($filepath.$ay->file_name) == 'application/pdf')
                      {{-- expr --}}
                    <ul class="mailbox-attachments clearfix">
                    
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $ay->file_name }}" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {{ $ay->file_name }}</a>
                            <span class="mailbox-attachment-size">
                              {{ $filesize }} KB
                              <a href="{{ route('home') }}/{{ $filepath }}{{ $ay->file_name }}" class="btn btn-default btn-sm float-right" style="word-break: break-all;"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                  </ul>
                    @elseif($ay->file_name && ($filetype == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $filetype == 'application/msword'))
                    <ul class="mailbox-attachments clearfix">
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="{{ route('home') }}/{{ $filepath }}{{ $ay->file_name }}" class="mailbox-attachment-name" style="word-break: break-all;"><i class="fas fa-paperclip"></i> {{ $ay->file_name }}</a>
                            <span class="mailbox-attachment-size">
                              {{ $filesize }} KB
                              <a href="{{ route('home') }}/{{ $filepath }}{{ $ay->file_name }}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    </ul>
                    @else
                    <div class="alert alert-dark" role="alert">
                        No Previously Uploaded File Found!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="form-group">
                      <label for="file">File <small>(File will not be changed if left blank)</small></label>
                      <input type="file" class="form-control" id="file" placeholder="save-the-tree.doc" name="doc" value="{{ $ay->doc }}">
                    </div>
                    @if ($conimgs && !($conimgs->isEmpty()))
                    <ul class="mailbox-attachments clearfix">

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
                              <button type="button" class="btn btn-success btn-sm float-right"><input type="checkbox" class="form-check-input btnkeep ml-0 " id="tc{{ $conimg->id }}" placeholder="" name="exfile[]" value="{{ $conimg->id }}" checked="">
                              <label class="form-check-label pl-3" for="tc{{ $conimg->id }}"> Keep File</label></button>
                              
                            </span>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                    @else
                    <div class="alert alert-dark" role="alert">
                        No Previous Uploaded Image Found!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                  
                  <div class="form-group">
                    <label for="photo">Photos <small>( Files will not be changed if left blank )</small></label>
                    <input type="file" class="form-control" id="photo" placeholder="" name="file[]" multiple="">
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

            @endif
            <a href="{{ route($route) }}" class="btn btn-secondary" >Back</a>
            <button type="submit" class="btn btn-primary" id="update">Update {{ $title }}</button>
      </form>
            </div>
              <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
          </div>
      </div>


     

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>

<script>
  $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox();
  });

  $('#update').on('click',function(el){
                event.preventDefault();
                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Uploading new files will Replace old files!",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, update!'
                    }).then((result) => {
                      if (result.value) {
                        $('#submit').submit();
                        Swal.fire({
                          title: 'Updating!',
                           text: 'Your Contribution is being updated.',
                          timer: 2000,
                          onOpen: () => {
                            swal.showLoading()
                            timerInterval = setInterval(() => {
                              swal.getContent().querySelector('strong')
                                .textContent = swal.getTimerLeft()
                            }, 100)
                          },
                          onClose: () => {
                            clearInterval(timerInterval)
                          }
                      }

                        )
                      }
                    })
            });

  $(".btnkeep").change(function(){
    if($(this).is(":checked")){
      $(this).parent().removeClass("btn-danger");
        $(this).parent().addClass("btn-success"); 
    }else{
        $(this).parent().removeClass("btn-success");  
        $(this).parent().addClass("btn-danger"); 
    }
});
</script>
@endsection