<!DOCTYPE html>
<html lang="en">
  @include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">
  @include('common.preloader')

  <!-- Top navigation bar start-->
    @include('common.top-navbar')
  <!-- Top navigation bar end-->

  <!-- left navigationbar start -->
    @include('common.left-navbar')
  <!-- left navigationbar end -->

  <div class="content-wrapper">

  <div id="page-content" style="margin-top: 20px;margin-left: 20px">

       <!-- Alert part -->
      <div id="page-content" style="margin-top: 20px;margin-left: 20px">
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

      <!-- ./ alert part -->

   <div id="page-content" style="margin-top: 20px;margin-left: 20px">
       <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Payment Gateway</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Payment Gateway</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
        <div class="input-group input-group-sm">
         <a href="{{ route('payment_gateway') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-plus" aria-hidden="true"></i> Add
    </a>
        &nbsp;&nbsp;&nbsp;
         <a href="{{ route('list') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View
    </a>

         &nbsp;&nbsp;&nbsp;
         <a href="{{ route('list', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> Trash
    </a>
        </div>
  </div>

<!-- <div style="clear:both; height:10px;"></div> -->

  <div class="row">
    <div class="col-md-12">
       <form method="POST" action="{{ route('p_store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <!-- general form elements -->
        <div class="card card-danger mb-0">
            <div class="card-header">
              <h3 class="card-title">Payment Gateway Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
              <div class="row">
                  <div class="col-sm-3">
                      <div class="form-group">
                          <label for="school_id"> School Name <span style="color:red;">*</span></label>
                          <select class="form-control select2" name="school_id" id="school_id" required="">
                              <option value="">Select School</option>
                              <?php
                              foreach ($schools as $value) {

                                  echo "<option value='$value[id]'>$value[school_name]($value[school_ein])</option>";


                              }
                              ?>
                          </select>
                      </div>
                  </div>

                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="student_id">User Name <span style="color:red;">*</span> </label>
                      <input type="text" class="form-control" id="user" name="user" required>
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="name"> Password <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="pass" name="pass" required>
                    </div>
                  </div>

                   <div class="col-sm-3">
                    <div class="form-group">
                      <label for="mobile_number1">Short code <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="s_code" name="s_code" required>
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="ipn">IPN </label>
                      <input type="text" class="form-control" id="ipn" name="ipn">
                    </div>
                  </div>

                  <div class="col-sm-3">
                      <div class="form-group">
                          <label for="email_address">Success Url</label>
                          <input type="text" class="form-control" id="success" name="success">
                      </div>
                  </div>


                   <div class="col-sm-3">
                    <div class="form-group">
                      <label for="father_name">Fail Url</label>
                      <input type="text" class="form-control" id="fail" name="fail">
                    </div>
                  </div>

                  <div class="col-sm-3">
                      <div class="form-group">
                          <label for="father_name">Cancel Url</label>
                          <input type="text" class="form-control" id="cancel" name="cancel" >
                      </div>
                  </div>


              </div>

            </div>
            <!-- /.card-body -->
        </div>


         <div class="card-footer">
              <button type="submit" class="btn btn-danger">Submit</button>
            </div>
          </form>

  </div>

</div>
</div>
   @include('common.page-script')
   @yield('custom-script')

</body>
</html>
