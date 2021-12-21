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

        <div id="page-content" style="margin-top: 0px;margin-left: 20px">

            <!-- Alert part -->
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

            <div id="page-content" style="margin-top: 0px;margin-left: 20px">

                <section class="content-header" style="margin-right: 1%;height: 50px">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Class Wise Fees</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Class Wise Fees</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <form method="post" action="{{route('school_accounts_panel-myclass_wise_fees.store')}} \store">
                    {{csrf_field()}}



                    <div class="form-group">
                        <div class="col-md-4 col-sm-12">
                            <select class="form-control" name="year" required="" id="year233">
                                <option value="">Select Year</option>
                                @foreach($years as $value)
                                    <option value="{{$value->id}}">{{$value->year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-sm-12">
                            <select class="form-control" name="class" required="" id="class233" onchange="hideShowDiv()">
                                <option value="">Select Class</option>
                                @foreach($classes as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div style="clear:both; height:10px;"></div>


   @php $i=0; @endphp

                @foreach($sub_head as $value)
                    @php $i++; @endphp





   @endforeach


                    <div class="card" style="margin-right:1%;margin-left:1%;display: none" id="hideshowMe" >
                        <div class="card-body table-responsive p-0" style="height: 400px;">
                            <table class="table table-hover table-condensed table-striped table-head-fixed text-nowrap table-bordered table-sm">
                                <thead style="background-color:#f1eeee">
                                <tr >
                                    <th style="text-align: center">SL</th>
                                    <th style="text-align: center">Check</th>
                                    <th>Fees Head</th>
                                    <th>Fees Amount</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $i=0; @endphp
                                @foreach($sub_head as $value)
                                    @php $i++; @endphp

                                    <tr>
                                        <td style="text-align: center">{{$i}}</td>
                                        <td style="text-align: center">
                                            <input type="checkbox" name="check[]" id="{{$i}}" class="enabletext" value="{{$i}}" style="width: 10%">
                                        </td>
                                        <td>{{$value->fees_subhead_name}}</td>
                                        <td style="text-align: center;">
                                            <input type="hidden" name="fees_id[{{$value->id}}]" value="{{$value->id}}">
                                            <input type="text" name="amount_{{$value->id}}" disabled="" id="amount{{$i}}" value="" class="" style="width:30%"
                                                   maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            >
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="text-align: center;margin-bottom: 10px;display: none" id="hideSubmitBtn">
                        <input type="submit" class="btn btn-primary" name="" style="background-color: red;border-color: red">
                    </div>

                </form>
            </div>

            <aside class="control-sidebar control-sidebar-dark">

            </aside>

        </div>
        @include('common.page-script')
        @yield('custom-script')

        <script type="text/javascript">

            $(document).on('change','.enabletext',function(event)
            {
                var textid = "amount"+this.id;
                var valuee = document.getElementById(textid).value;
                $("#"+textid).prop("disabled",  !this.checked);
                $("#"+textid).val(valuee,  !this.checked);
            });


            function hideShowDiv() {
                var class_id = document.getElementById("class233").value;
                var year_id = document.getElementById("year233").value;
                if (year_id !== "") {
                    $("#hideshowMe").show();
                    $("#hideSubmitBtn").show();
                }
                if (year_id === "") {

                    alert("Please select a year from dropdown list");
                }

            }

        </script>
</body>
</html>
