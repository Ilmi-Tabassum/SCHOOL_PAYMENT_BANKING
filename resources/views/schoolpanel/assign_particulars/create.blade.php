<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('assign_particulars.store') }}/store" enctype="multipart/form-data" autocomplete="off">
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
                    <label for="assign_class">Assign particulars <span style="color:red;">*</span> </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>SL</th>
                            <th>particulars Name</th>
                            <th>Select</th>
                        </tr>
                        <?php
                        $serial_no = 1;

                        ?>
                    @if(!empty($particulars))
                        @foreach($particulars as $value)
                            <tr>
                                <td>  {{ $serial_no++ }} </td>
                                <td>{{ $value->fees_head_name  }}</th>
                                <td><input type="checkbox" id="{{ $value->id  }}" name="particulars_name[]" value="{{ $value->id  }}"></td>
                            </tr>


                        @endforeach
                    @endif



                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->

        </form>
        <!-- /.card -->
    </div>
</div>
