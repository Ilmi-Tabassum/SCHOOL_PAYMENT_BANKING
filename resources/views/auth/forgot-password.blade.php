<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password </title>

  <link rel="stylesheet" href="{{asset('custom/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('custom/css/login.css')}}">
</head>

<body class="hold-transition login-page" style="background-image: url('/dist/img/bg.svg')">

<div class="login-box">
  <div class="card login_style">
    <!-- logo of the bank/school -->
    <div class="card-header text-center login_img">
       <img src="{{asset('/dist/img/ab-logo.png')}}" width="221px" height="59px">
    </div>

    <div class="card-body">
      <p class="login-box-msg">Enter your Login mobile number to reset password</p>

      <form action="" method="">
        <div class="input-group mb-3">
          <input 
            type="text"
            class="form-control"
            placeholder="Mobile number"
            autocomplete="off"
            name="mobile"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
            maxlength="11"
            onkeyup="mobile_format()"
            id="mobile"
            value="" 
            autofocus 
            >
          <div class="input-group-append">
            <div class="input-group-text" style="color: #a9a8a8">
              <i class="fa fa-mobile" aria-hidden="true" style="font-size: 24px"></i>
            </div>
          </div>
        </div>

         <div class="row">

          <div class="col-4" >
            <div style="margin-top:10px;">
            <a href="{{url('/') }}"style="color:#000;font-size: 15px;">
              <i class="fa fa-chevron-left" aria-hidden="true"></i>
            Back
            </a>
            
            </div>
          </div>

          <div class="col-8">
            <div>
              <button type="submit" class="btn btn-primary btn-block" style="background-color: red;border-color: red;font-weight: bold;font-size:15px">Reset Password</button>
            </div>
          </div>

         
        </div>


      </form>
    
    </div>
    
  </div>
</div>

 <footer align="center" style="margin-top:50px;font-size: 12px; font-weight: normal;">
    Copyright &copy; <?php echo date("Y"); ?> 
      <a href="https://abbl.com" style="color:#ee1b22" target="_blank"><b>AB Bank.</b></a>
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
    
  }
</script>
</body>
</html>

