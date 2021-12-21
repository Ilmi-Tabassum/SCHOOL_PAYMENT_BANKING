@extends('master')

@section('title','Collections')

@section('page_specific_css')

@endsection


@section('content')

    <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Todays Collections</h3>
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
            <form method="POST" action="{{route('serachTCollectionA')}}">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <select class="form-control select2" name="schoolid" id="schoolid" required="">
                                <option selected disabled>Select School</option>
                                <?php
                                foreach ($school_info as $key => $value) {

                                        echo "<option value='$value->id'>$value->school_name</option>";

                                }
                                ?>
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
