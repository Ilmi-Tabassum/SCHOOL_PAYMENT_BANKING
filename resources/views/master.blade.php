<!DOCTYPE html>
<html lang="en">
<head>
  @include('component.head')
  <title> @yield('title','Title')</title>

  <!-- Page specific css will be injected from here-->
    <style type="text/css">
        @yield('page_specific_css')
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">

  @include('component.preloader')
  @include('component.topnavbar')
  @include('component.leftnavbar')

  <div class="content-wrapper">

  <div id="page-content" style="margin-top: 0px;margin-left: 20px">


      <div id="page-content" style="margin-top: 0px;margin-left: 20px;margin-right: 20px">
          @if(Session::has('success'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-success collapse">
                       <i class="nav-icon fas fa-info-circle"></i> {{ Session::get('success') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-times"></i></a>
                  </div>
              </div>
          </div>
          @endif


          @if(Session::has('error'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-danger collapse">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>  {{ Session::get('error') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-trash-alt"></i></a>
                  </div>
              </div>
          </div>
          @endif
      </div>

  <div id="page-content" style="margin-top: 0px;margin-left: 20px">
    @yield('content')
  </div>



  </div>

  </div>

    @include('component.footer')
    <!-- @include('component.script') -->
    @yield('page_specific_script')

 

  </div>
</body>
</html>
