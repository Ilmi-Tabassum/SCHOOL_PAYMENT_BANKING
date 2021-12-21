<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
 
  <link rel="stylesheet" href="{{asset('custom/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('custom/css/login.css')}}">

  <style type="text/css">
    .login_error{
      color: #ee1b22;
    }
    .icheck-primary > input:first-child:checked + label::before,
    .icheck-primary > input:first-child:checked + input[type="hidden"] + label::before {
      background-color: #ee1b22;
      border-color: #ee1b22;
    }

    [class*=icheck-]>input:first-child+input[type=hidden]+label::before,
    [class*=icheck-]>input:first-child+label::before {
      border:1px solid #ee1b22;
 
    }
    .icheck-primary>input:first-child:not(:checked):not(:disabled):hover+input[type=hidden]+label::before,
    .icheck-primary>input:first-child:not(:checked):not(:disabled):hover+label::before {
       border-color:#ee1b22
     }

    .icheck-default>input:first-child:checked+input[type=hidden]+label::before,
    .icheck-default>input:first-child:checked+label::before {
       background-color:#ee1b22;
      border-color:#ee1b22
    }
  </style>
</head>

<body class="hold-transition login-page" style="background-image: url('/dist/img/bg.svg')">



<div class="login-box">
  <div class="card login_style">

    <!-- logo of the bank/school -->
    <div class="card-header text-center login_img">
      <img src="{{asset('/dist/img/ab-logo.png')}}" alt="logo">
     <!--  <img src="https://upload.wikimedia.org/wikipedia/en/thumb/8/84/Logo_of_Notre_Dame_College%2C_Dhaka.svg/1200px-Logo_of_Notre_Dame_College%2C_Dhaka.svg.png" alt="logo" style="max-width: 100%;max-height: 100px;padding: 10px 0px 18px 0px;"> -->
    </div>

    <!-- login card body start -->
    <div class="card-body">

      <form method="POST" action="{{ route('login') }}" name="login">
        @csrf
        
        <!-- show error message -->
        @error('mobile_number')
          <div class="login_error_msg">
            <span class="login_error" role="alert">
                {{ $message }}
            </span>
          </div>
        @enderror

        <!-- login form -->
        <div class="input-group mb-3">
          <input 
            type="text"
            class="form-control"
            placeholder="Mobile Number/Email"
            autocomplete="off"
            name="mobile_number"
            id="mobile"
            value="" 
            autofocus 
            >

          <div class="input-group-append">
            <div class="input-group-text append_icon_color" >
              <i class="fa fa-mobile" aria-hidden="true" style="font-size: 24px"></i>
            </div>
          </div>

        </div>

        
        <div class="input-group mb-3">
          <input 
            type="password"
            class="form-control"
            placeholder="Password / OTP"
            autocomplete="off"
            name="password"
            id="password-field"
            maxlength="50"
            value="" 
            >

          <div class="input-group-append">
            <div class="input-group-text append_icon_color" style="margin-left:-10px;cursor: pointer;">
             <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember" style="font-weight: normal;font-size:15px">Remember Me</label>
            </div>
          </div>

          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block" style="background-color: red;border-color: red;font-weight: bold">Login</button>
          </div>
         
        </div>

         <div class="row" style="margin-top: 18px">
           <div class="col-12" align="center">
              <a href="{{url('/forgot-password')}}" style="background-color:#fff; color:#000;font-size:15px">Forgot password ?</</a>
            </div>
        </div>

      </form>

    </div>
  </div>
</div>


 <footer align="center" style="margin-top:50px;font-size: 12px; font-weight: normal;">
         <a href="" style="color:#ee1b22;"> <span style="color: black">Copyright &copy; <?php echo date("Y"); ?></span>  <b>AB Bank.</b></a>
    All rights reserved.
 </footer>



<!-- prescribed script start -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<!-- custom script start -->
<script type="text/javascript">

  function mobile_format() 
  {
   var val = document.getElementById("mobile").value;
   var length = val.length;
    if(length == 2){
      if(val != '01'){
        document.getElementById("mobile").value = "01";
      }
    }
    if(length==11){
      document.getElementById("password-field").focus();
    }

  }

    $(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
    });

   

  function submit_using_enter() {
    document.getElementById('body').onkeyup = function(e) {
    if (e.keyCode === 13) {
      document.getElementById('login').submit();
    }
    return true;
   }
     
  }

</script>
</body>
</html>
