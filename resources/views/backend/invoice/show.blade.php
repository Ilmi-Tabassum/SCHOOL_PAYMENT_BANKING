@extends('master')

@section('title','Student List')

@section('page_specific_css')
    <!-- distinct page css will be here -->
@endsection


@section('content')
<style>

    table, td, th {
        border: 1px solid black;

    }

    p {
        margin: 4px;
    }

    table {
        border-collapse: collapse;

    }
    *{
        /*overflow: hidden;*/
        white-space: nowrap;

    }

    th {

        text-align: center;
    }
    .table{
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000000;
        height: 225px;
        margin: 5px 0 5px;
    }
    .table>tbody>tr:first-child:last-child>th,.table>tbody>tr:first-child:last-child>th{
        border:1px solid #000000;
    }
    .table>tbody>tr:not(:first-child):not(:last-child)>th,.table>tbody>tr:not(:first-child):not(:last-child)>td{
/*        border-top:none !important;
        border-bottom:none !important;*/
    }
    .body{
        width: 100%;
        font-size: 21px;
    }
    @media print {
        .body{
            width: 100% !important;
            margin: 0 auto;
        }
    }
    .main{
        width: 43%;
        margin-left: 27%;
        margin-right: 3%;
        margin-bottom: 25px;
        margin-top: 10px;
    }
    .bolder_text{
        font-weight: 600;
    }

</style>
<body>

@foreach($data as $data)
    <div>
        <div class="pull-right" >
            <a class="btn btn-primary" href="{{route('users.index',['download'=>'pdf','id'=>$data->invoice])}}">Download PDF</a>
{{--
            <a class="btn btn-warning" href="{{ route('export') }}">Export Bulk Data</a>
--}}

        </div>

    </div>
    <div class="main">


        <div id="image" style="text-align: center;position: absolute;">
            @if($data->status == 1)
                <img src="{{asset('dist/img/paid.png')}}" style="    position: absolute;
    width: 261px;
    top: 366px;
    left: 170px;">
            @endif
        </div>


            <img src="{{$path}}" style="width: 105px;text-align: center; margin-left: 44%;">

        <div style="clear:both; height:10px;"></div>
                <h1 style="margin: 0 0 5px 0;font-size: 22px;text-align: center; text-transform: uppercase;">{{$data->school}}</h1>

        <h1 style="font-size: 10px;width: 60%;margin: 0 auto;border-bottom: 1px solid #000000;overflow: visible;margin-bottom: 5px; white-space: nowrap; text-align: center;">MONEY RECEIPT <span style="font-size: 12px">(Students Copy)</span></h1>

        <div class="body">
            <div style="line-height: 2">
                <span class="bolder_text">Money receipt no :- </span> {{$data->invoice}}
                <span style="float: right;" class="bolder_text">Month  : {{$data->pmonth}} {{--{{$data->pyear}}--}}</span>
            </div>
            <div style="text-align: right;float: right; ">Date:  {{$data->pdate}}</div>
            <div style="line-height: 2"><span class="bolder_text">Name :&nbsp;</span>{{$data->name}}</div>
            <div><span class="bolder_text">Roll no:{{$data->roll}} </span> </div>
            <div><span class="bolder_text ">ID no: </span>{{$data->stuid}} </div>
            <div><span class="bolder_text">Class: </span>{{$data->class}} </div>
            <div><span class="bolder_text">Section: </span>{{$data->sec}} </div>
            <table class="table" style="    width: 100%;
    border-collapse: collapse;
    border: 2px solid #000000;
    height: 225px;
    margin: 5px 0 5px;">
                <tbody>
                <tr>
                    <th>SL No</th>
                    <th>Particulars Of Fees</th>
                    <th>Taka</th>
                </tr>
                @php $i=0;@endphp
                @foreach($feesheeads as $d)
                    <tr id="row_$d->id">
                        @php $i++;@endphp
                        <td style="text-align: center;">{{$i}}</td>
                        <td>{{$d->title}}</td>
                        <td style="text-align: center;">{{$d->samount}}</td>
                    </tr>
                @endforeach


                <tr>
                    <th style="text-align: right;" colspan="2">Total</th>
                    <th>{{$data->amount}}</th>
                </tr>
                @if($data->due > 0)

                <tr>
                    <th style="text-align: right;" colspan="2">Paid</th>
                    <th style="color: green">{{$data->amount-$data->due}}</th>
                </tr>
                <tr>
                    <th style="text-align: right;" colspan="2">Due</th>
                    <th style="color: red">{{$data->due}}</th>
                </tr>

                @endif


            </tbody></table>


{{--            <div>
                <div style="float: left;width: 50%">The AB Bank Ltd <br> Dhaka  Branch </div>
                <div style="float: right;width: 50%">Name  <br> Contact No</div>
                <div style="clear: both;"></div>
            </div>
        </div>--}}
    </div>
    </div>
{{--
        <div class="main">
                <div id="image" style="text-align: center;position: absolute;">
                    @if($data->status ==1)
        <img src="{{asset('dist/img/paid.png')}}" style="position: absolute;width: 149px;top: 148px;left: 170px;">
                    @endif
                </div>
                <h1 style="margin: 0 0 5px 0;font-size: 12px;text-align: center; text-transform: uppercase;">{{$data->school}}</h1>

        <h1 style="font-size: 10px;width: 60%;margin: 0 auto;border-bottom: 1px solid #000000;overflow: visible;margin-bottom: 5px; white-space: nowrap; text-align: center;">MONEY RECEIPT <span style="font-size: 9px">(School Accounts Copy)</span></h1>

            <div class="body">
                <div style="line-height: 2">
                    <span class="bolder_text">Money receipt no :- </span> {{$data->invoice}}
                    <span style="float: right;" class="bolder_text">Fees of : {{$data->pmonth}} {{$data->pyear}}</span>
                </div>
                <div style="text-align: right; ">Date:  {{$data->pdate}}</div>
                <div style="line-height: 2"><span class="bolder_text">Name of the student:&nbsp;</span>{{$data->name}}</div>
                <div style="line-height: 2"><span class="bolder_text">Roll no:{{$data->roll}} </span>
                    <span class="bolder_text">ID no: </span>{{$data->stuid}}</div>
                <div><span class="bolder_text">Class: </span>{{$data->class}} <span class="bolder_text">Section: </span>{{$data->sec}}</div>
                <table class="table">
                    <tbody>
                    <tr>
                        <th>SL No</th>
                        <th>Particulars Of Fees</th>
                        <th>Taka</th>
                    </tr>
                    @php $i=0;@endphp
                    @foreach($feesheeads as $d)
                        <tr id="row_$d->id">
                            @php $i++;@endphp
                            <td style="text-align: center;">{{$i}}</td>
                            <td>{{$d->title}}</td>
                            <td style="text-align: center;">{{$d->samount}}</td>
                        </tr>
                    @endforeach


                    <tr>
                        <th style="text-align: right;" colspan="2">Total</th>
                        <th>{{$data->amount}}</th>
                    </tr>
                    </tbody></table>
                <div style="margin-top: 20px;">
                    <div style="float: left;width: 50%"><span class="bolder_text">Signature of Recipient </span></div>
                    <div style="float: right;width: 50%"><span class="bolder_text">Signature of Depositor</span> </div>
                    <div style="clear: both;"></div>
                </div>

                <div>
                    <div style="float: left;width: 50%">The AB Bank Ltd <br> Dhaka  Branch </div>
                    <div style="float: right;width: 50%">Name  <br> Contact No</div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>

        <div class="main">
                <div id="image" style="text-align: center;position: absolute;">
                    @if($data->status ==1)
                        <img src="{{asset('dist/img/paid.png')}}" style="position: absolute;width: 149px;top: 148px;left: 170px;">
                    @endif
        </div>
                <h1 style="margin: 0 0 5px 0;font-size: 12px;text-align: center; text-transform: uppercase;">{{$data->school}}</h1>

        <h1 style="font-size: 10px;width: 60%;margin: 0 auto;border-bottom: 1px solid #000000;overflow: visible;margin-bottom: 5px; white-space: nowrap; text-align: center;">MONEY RECEIPT <span style="font-size: 9px">(Bank Copy)</span></h1>

            <div class="body">
                <div style="line-height: 2">
                    <span class="bolder_text">Money receipt no :- </span> {{$data->invoice}}
                    <span style="float: right;" class="bolder_text">Fees of : {{$data->pmonth}} {{$data->pyear}}</span>
                </div>
                <div style="text-align: right; ">Date:  {{$data->pdate}}</div>
                <div style="line-height: 2"><span class="bolder_text">Name of the student:&nbsp;</span>{{$data->name}}</div>
                <div style="line-height: 2"><span class="bolder_text">Roll no:{{$data->roll}} </span>
                    <span class="bolder_text">ID no: </span>{{$data->stuid}}</div>
                <div><span class="bolder_text">Class: </span>{{$data->class}} <span class="bolder_text">Section: </span>{{$data->sec}}</div>
                <table class="table">
                    <tbody>
                    <tr>
                        <th>SL No</th>
                        <th>Particulars Of Fees</th>
                        <th>Taka</th>
                    </tr>
                    @php $i=0;@endphp
                    @foreach($feesheeads as $d)
                        <tr id="row_$d->id">
                            @php $i++;@endphp
                            <td style="text-align: center;">{{$i}}</td>
                            <td>{{$d->title}}</td>
                            <td style="text-align: center;">{{$d->samount}}</td>
                        </tr>
                    @endforeach


                    <tr>
                        <th style="text-align: right;" colspan="2">Total</th>
                        <th>{{$data->amount}}</th>
                    </tr>
                    </tbody></table>
                <div style="margin-top: 20px;">
                    <div style="float: left;width: 50%"><span class="bolder_text">Signature of Recipient </span></div>
                    <div style="float: right;width: 50%"><span class="bolder_text">Signature of Depositor</span> </div>
                    <div style="clear: both;"></div>
                </div>

                <div>
                    <div style="float: left;width: 50%">The AB Bank Ltd <br> Dhaka  Branch </div>
                    <div style="float: right;width: 50%">Name  <br> Contact No</div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>--}}
    <img src="{{asset('dist/img/logos.png')}}" style=" width: 43%; text-align: center;margin-left: 27%;">
</body>

@break
@endforeach

<aside class="control-sidebar control-sidebar-dark"> </aside>
@endsection



@section('page_specific_script')

    <script type="text/javascript">

        function AddYourStudent() {
            $('#modal-default').modal('show');
        }

        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var port = window.location.port;
        var pathname = window.location.pathname;
        pathname = pathname.split("/");
        var domainName = pathname[1];

        if(port){
            var globalURL = protocol + "//" + hostname + ":" + port + "/";
        }else{
            var globalURL = protocol + "//" + hostname + "/";
        }


        /*Fetch corresponding school student ID*/
        $("#schoolID").change(function(){
            var id = $(this).val();
            if(id !== "" ){
                var url = globalURL + "school-wise-students/"+id;
                $('#studentIDSelect').empty();
                $('#studentIDSelect').append('<option value="">Fetching Student ID...</option>');

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function(response){
                        if (response.hasData==1) {
                            $('#studentIDSelect').empty();
                            $('#studentIDSelect').append('<option value="">Select Student ID</option>');

                            response.studentData.forEach(row =>{
                                $('#studentIDSelect').append('<option value="'+row.id+'">'+row.real_id+'</option>');
                            });

                        }
                        else{
                            $('#studentIDSelect').empty();
                            $('#studentIDSelect').append('<option value="">No Students Available</option>');
                        }

                    },

                });
            }
        });


        $("#studentIDSelect").change(function(){
            var id = $(this).val();
            $('#ConfirmOTP').modal('show');
        });


    </script>

@endsection
