<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('myclass_info.store') }}/store" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">


                <div class="form-group">
                    <label for="assign_class">Assign Class <span style="color:red;">*</span> </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>SL</th>
                            <th>Class Name</th>
                            <th>Select</th>
                        </tr>
                       <?php
                       $serial_no = 1;
                       $selected_classes="";
                       ?>

                            @foreach($classes as $class)
                                <tr>
                                    <td>  {{ $serial_no++ }} </td>
                                    <td>{{ $class->name  }}</th>
                                    <td><input type="checkbox" id="{{ $class->id  }}" name="class_name[]" value="{{ $class->id  }}"></td>
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
