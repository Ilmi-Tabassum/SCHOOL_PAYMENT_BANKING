<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AB Bank EMS</title>
  <link rel="shortcut icon" href="{{asset('dist/img/favicon.ico')}}" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
</head>


<body class="hold-transition login-page">
<div class="login-box">


  <div class="card" style="border-top: 3px solid red">

    <div class="card-header text-center">
      <img src="{{asset('/dist/img/ab_bank.png')}}" alt="logo" width="100%">
    </div>

    <div class="card-body">
     <!--  <p class="login-box-msg">Sign in to start your session</p> -->

      <form action="" method="post">

        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Mobile number" autocomplete="off" name="mobile_number"
           oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa fa-mobile"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password/OTP" autocomplete="off" name="password_otp">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>

         
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" style="background-color: red;border-radius:none">Sign In</button>
          </div>
         
        </div>
      </form>

     

      <p class="mb-1">
        <a href="" style="color: #000">Forgot password ?</a>
      </p>
     
    </div>
    
  </div>
 
</div>


<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
</body>
</html>
