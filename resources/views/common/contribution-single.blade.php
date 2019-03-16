@extends('layouts.master')

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
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  @if (Auth::user()->role == 2)
                    <a href="{{ route($eroute,$con->id) }}" class="btn btn-tool"><i class="fas fa-edit"></i>
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
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> Sep2014-report.pdf</a>
                            <span class="mailbox-attachment-size">
                              1,245 KB
                              <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    <li>
                      <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                      <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> App Description.docx</a>
                            <span class="mailbox-attachment-size">
                              1,245 KB
                              <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    <li>
                      <span class="mailbox-attachment-icon has-img"><img src="https://adminlte.io/themes/dev/AdminLTE/dist/img/photo1.png" alt="Attachment"></span>

                      <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera-retro"></i> photo1.png</a>
                            <span class="mailbox-attachment-size">
                              2.67 MB
                              <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
                    <li>
                      <span class="mailbox-attachment-icon has-img"><img src="https://adminlte.io/themes/dev/AdminLTE/dist/img/photo2.png" alt="Attachment"></span>

                      <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera-retro"></i> photo2.png</a>
                            <span class="mailbox-attachment-size">
                              1.9 MB
                              <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                            </span>
                      </div>
                    </li>
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

                    @if ($com->user->role > 3)
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user1-128x128.jpg"
                    @else
                      src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user3-128x128.jpg"
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
              <div class="card-footer">
                <form action="{{ route($route, $con->id) }}" method="post">
                  @csrf
                  <img class="img-fluid img-circle img-sm" src="https://adminlte.io/themes/dev/AdminLTE/dist/img/user4-128x128.jpg" alt="Alt Text">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push form-group">
                    <textarea class="form-control form-control-lg" rows="4" name="comment">Press enter to post comment</textarea>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary btn-lg">Add comment</button>
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->


   </div>
     

@endsection
