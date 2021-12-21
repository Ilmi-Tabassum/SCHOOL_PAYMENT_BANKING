@extends('master')

@section('title','Collections')

@section('page_specific_css')

@endsection


@section('content')

    <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Monthly Collections</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div style="clear:both; height:10px;"></div>

    <div class="card"  style="margin-right:1%">
        <div class="card-header">

            <form method="POST" action="{{route('serachCollectionA')}}">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <div>
                            <select class="form-control" name="month" required  id="month" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
                                <option value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div>
                            <select class="form-control" name="year" required  id="year" oninvalid="this.setCustomValidity('Please select a year in the list')" oninput="setCustomValidity('')">
                                <option value="">Select Year</option>
                                <option value="2021" selected>2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm">
                        <button type="submit" class="btn btn-danger">Search</button>
                    </div>
                </div>
            </form>
        </div>


        <table class="table table-hover table-condensed table-striped table-bordered">

            <thead >
            <tr style="background-color:#fff">
                <th style="width:5%">SL</th>
                <th>School Name</th>
                <th>Total Amount</th>
            </tr>
            </thead>

            <tbody>
            @php $i=1; @endphp
            @foreach($data as $d)

                <tr>
                    <td>{{$i}}</td>
                    <td>
                        <?php
                        $school_id = $d->school_id;
                        $school = DB::select(DB::raw("SELECT school_name FROM school_infos WHERE id = $school_id"));
                        echo $school[0]->school_name;
                        ?>
                    </td>
                    <td>{{$d->paid_total_amount}}</td>
                </tr>

                @php $i++; @endphp
            @endforeach

            </tbody>

        </table>


        <div class="d-flex">
            <div class="mx-auto">
                {{$data->links("pagination::bootstrap-4")}}
            </div>
        </div>

    </div>




    <aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

    <script type="text/javascript">

    </script>

@endsection
