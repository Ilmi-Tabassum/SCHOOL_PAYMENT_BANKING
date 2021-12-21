<div class="row">

    <div class="col-md-12">
        <form method="post" id="PostForm" action="{{ route('tellerpanel.store') }} " enctype="multipart/form-data" autocomplete="on">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class_id }}">
            <input type="hidden" name="school_id" value="{{ $school_id }}">
            <input type="hidden" name="student_id" value="{{ $student_id }}">
            <div class="card-body">


                <div class="form-group">
                    <label for="assign_class">Payment  </label>
                    <table class="table table-hover table-condensed table-striped">
                        <tbody>
                        <tr>
                            <th>Select</th>
                            <th>Fees Sub Head</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Dues</th>
                            <th>Pay Now</th>
                        </tr>

                        @for($i=0;$i<sizeof($subheads);$i=$i+1)
                            <?php
                                $received="";
                               /* print_r($received_amount[$i][0]->received_amount);*/
                                if(empty($received_amount[$i][0]->received_amount))
                                    {
                                        $received=0;
                                    }
                                else
                                    {
                                        $received=$received_amount[$i][0]->received_amount;
                                    }
                            $due = $subheads[$i]->amount - $received;
                               /* print_r($subheads);*/
                            ?>
                            <tr>
                                @if($due ==0)

                                    <td><input type="checkbox" id="{{ $subheads[$i]->id  }}" name="fees_id[]" value="{{ $subheads[$i]->id  }} " disabled></td>


                                @else

                                    <td><input type="checkbox" id="{{ $subheads[$i]->id  }}" name="fees_id[]" value="{{ $subheads[$i]->id  }}"></td>


                                @endif
                                <td>{{ $subheads[$i]->fees_subhead_name}}</td>
                                <td>{{ $subheads[$i]->amount}}</td>
                                <td>{{ $received}}</td>
                                <td>{{ $due}}</td>
                                @if($due !=0)

                                    <td> <input type="text" class="form-control" placeholder="Pay " name="pay_box[]" value='' autocomplete="off"></td>


                                @else

                                    <td> <input type="text" class="form-control" placeholder="Paid " name="pay_box[]" autocomplete="off" disabled></td>


                                @endif


                            </tr>


                        @endfor



                        </tbody>
                    </table>

            </div>
                <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnSubmit"  style="background-color: #ee1b22;border-color: #ee1b22">Pay</button>
                </div>

            </div>
            <!-- /.card-body -->

        </form>
        <!-- /.card -->
    </div>
</div>
