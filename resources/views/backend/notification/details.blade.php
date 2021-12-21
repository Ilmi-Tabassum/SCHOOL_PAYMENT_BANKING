<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover table-striped table-condensed">
            <tr>
                <td>Title</td>
                <td>{{ $details->notification_title }}</td>
            </tr>
             <tr>
                <td>Description</td>
                <td>{{ $details->notification_body }}</td>
            </tr>

            <tr>
                <td>Create Date</td>
                <td>{{ $details->created_at }}</td>
            </tr>
           
            <tr>
                <td>Notice for</td>
                @if($details->for_all===1)<td>All School</td>
                @else
                    <td>
                        <ul>
                            @foreach($schools as $school)
                            <li>{{ $school->school_name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    </td>
                @endif
            </tr>
        </table>
    </div>
</div>
