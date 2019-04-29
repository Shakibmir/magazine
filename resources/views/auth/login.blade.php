
<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ config('app.name', 'Laravel') }}</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" type="image/png" href="{{ asset('assets/plugins/vendor/images/icons/favicon.ico')}}" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/bootstrap/css/bootstrap.min.css')}}">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/animate/animate.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/css-hamburgers/hamburgers.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/select2/select2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/css/util.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/vendor/css/main.css')}}">

</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('assets/plugins/vendor/images/img-01.png')}}" alt="IMG">
                </div>
            <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                        @csrf
                <span class="login100-form-title">
                {{ __('Magazine Portal Login') }}
                </span>
                <!--  ==================================SESSION MESSAGES==================================  -->
                    @if (session()->has('message'))
                        <div class="alert alert-{!! session()->get('type')  !!} alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('message')  !!}
                        </div>
                    @endif
                <!--  ==================================SESSION MESSAGES==================================  -->


                <!--  ==================================VALIDATION ERRORS==================================  -->
                    @if($errors->any())
                        @foreach ($errors->all() as $error)

                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {!!  $error !!}
                            </div>
                        @endforeach
                    @endif
                <!--  ==================================SESSION MESSAGES==================================  -->
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100 {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100 {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Password" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="wrap-input100 mt-4 mb-0">
                    <input class="form-check-input ml-3" style="margin-top: 6px;" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label pl-5" style="margin-top: -5px;" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">Login</button>
                </div>
                @if (Route::has('password.request'))

                <div class="text-center p-t-12">
                    <span class="txt1">Forgot</span>
                    <a class="txt2" href="{{ route('password.request') }}">Username / Password?</a>
                </div>
                @endif
                
                <div class="text-center p-t-136">
                    {{-- <a class="txt2" href="#">Create your Account <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a> --}}
                </div>
            </form>
            </div>
        </div>
    </div>

<script src="{{ asset('assets/plugins/vendor/jquery/jquery-3.2.1.min.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>

<script src="{{ asset('assets/plugins/vendor/bootstrap/js/popper.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>
<script src="{{ asset('assets/plugins/vendor/bootstrap/js/bootstrap.min.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>

<script src="{{ asset('assets/plugins/vendor/select2/select2.min.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>

<script src="{{ asset('assets/plugins/vendor/tilt/tilt.jquery.min.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>
<script type="3ca54541b2f00c71b6ce8f9f-text/javascript">
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>

    <script src="{{ asset('assets/plugins/vendor/js/main.js')}}" type="3ca54541b2f00c71b6ce8f9f-text/javascript"></script>

<script src="https://ajax.cloudflare.com/cdn-cgi/scripts/a2bd7673/cloudflare-static/rocket-loader.min.js" data-cf-settings="3ca54541b2f00c71b6ce8f9f-|49" defer=""></script></body>
</body>
</html>
