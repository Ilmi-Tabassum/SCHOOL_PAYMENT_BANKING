<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('school_accounts_panel-assign_session.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">


                <div class="form-group">
                    <label for="assign_class">Assign Session <span style="color:red;">*</span> </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>SL</th>
                            <th>Session Name</th>
                            <th>Select</th>
                        </tr>
                        <?php
                        $serial_no = 1;
                        $selected_classes="";
                        ?>

                        @foreach($sessions as $value)
                            <tr>
                                <td>  {{ $serial_no++ }} </td>
                                <td>{{ $value->name  }}</th>
                                <td><input type="checkbox" id="{{ $value->id  }}" name="session_name[]" value="{{ $value->id  }}"></td>
                            </tr>


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
