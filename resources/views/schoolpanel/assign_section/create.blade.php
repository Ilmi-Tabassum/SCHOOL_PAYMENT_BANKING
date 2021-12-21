<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('assign_section.store') }}/store" enctype="multipart/form-data" autocomplete="off">
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
                    <label for="assign_class">Assign Section <span style="color:red;">*</span> </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>SL</th>
                            <th>Section Name</th>
                            <th>Select</th>
                        </tr>
                        <?php
                        $serial_no = 1;
                        $selected_classes="";
                        ?>
                    @if(!empty($sections))
                        @foreach($sections as $value)
                            <tr>
                                <td>  {{ $serial_no++ }} </td>
                                <td>{{ $value->name  }}</th>
                                <td><input type="checkbox" id="{{ $value->id  }}" name="section_name[]" value="{{ $value->id  }}"></td>
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
{{--<script>
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
    $("#class_id").change(function(){
        var id = $(this).val();
        let url = globalURL+'assign_section/section/'+id;
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                response.forEach(row =>{
                    //$('#student_data_fw').append('<option value="'+row.id+'">'+row.student_id+'</option>');
                    $('table tbody').prepend('<tr>'
                        '<td>' row '</td>'
                        '<td>' row.name '</td>'
/*
                    <td><input type="checkbox" id="'row.name'" name="section_name[]" value="' row.name' "></td>
*/
                        </tr>')
                });
            }
        });
    });


</script>--}}
