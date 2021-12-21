<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('assign_shift.store') }}/store" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                @if(!isset(Auth::user()->school_id))
                    <div class="form-group">
                        <select class="form-control select2" name="school_id" id="school_id" required="">
                            <option value="0">Select School</option>
                            <?php
                            foreach ($schools as $value) {
                                echo "<option value='$value->id'>$value->school_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <select class="form-control select2" name="class_id" id="class_id" required="">
                        <option value="0">Select Class</option>
                        <?php
                        foreach ($classes as $value) {
                            echo "<option value='$value->id'>$value->name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assign_class">Assign Shift <span style="color:red;">*</span> </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>SL</th>
                            <th>shift Name</th>
                            <th>Select</th>
                        </tr>
                        <?php
                        $serial_no = 1;
                        $selected_classes="";
                        ?>

                        @foreach($shifts as $value)
                            <tr>
                                <td>  {{ $serial_no++ }} </td>
                                <td>{{ $value->name  }}</th>
                                <td><input type="checkbox" id="{{ $value->id  }}" name="shift_name[]" value="{{ $value->id  }}"></td>
                            </tr>
                            {{--                                <?php
                                                            array_push($selected_classes, );
                                                            ?>--}}


                        @endforeach



                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->

        </form>
        <!-- /.card -->
    </div>
</div>
