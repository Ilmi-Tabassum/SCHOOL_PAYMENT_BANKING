<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title> 
    <?php
      $user_type = auth()->user()->user_type_id;
      if ($user_type==1) {
        echo "Bank Panel";
      }

      if ($user_type==2) {
        echo "School Panel";
      }

      if ($user_type==3) {
        echo "Guardian Panel";
      }

      if ($user_type==4) {
        echo "Bank Teller Panel";
      }

      if ($user_type==5) {
        echo "Bank Agent Panel";
      }

      if ($user_type==6) {
        echo "Bank Officer Panel";
      }


     ?>
     
  </title>
  <link rel="shortcut icon" href="{{asset('dist/img/favicon.ico')}}" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/bootstrap-datepicker.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/chart.js/Chart.min.css')}}">
  <link rel="stylesheet" href="{{asset('/dist/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('custom/css/bootstrap-multiselect.css')}}">
  <link rel="stylesheet" href="{{asset('custom/css/custom_color.css')}}">
   <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
 <!--  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}"> -->
</head>





<!-- Modal Design -->
<div class="modal fade" id="modal-custom" data-backdrop="static" >
  <div class="modal-dialog">
    <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
      <div class="modal-header ab_bank_modal_background_color">
        <h4 class="modal-title white-color">Title</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="Loader flex-column justify-content-center align-items-center">
          <img class="animation__shake" src="{{asset('dist/img/logo.png')}}" alt="logo" height="60" width="60">
       </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btnSubmit" style="background-color: #ee1b22;border-color: #ee1b22">Submit</button>
     </div>

    </div>

  </div>
</div>

<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: red;color:#ffffff;">
                <h4 class="modal-title">Extra Large Modal</h4>
                <button type="button" style="color:#ffffff;" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

