<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover table-striped table-condensed">
            <tr>
                <td>User Name</td>
                <td>{{ $details->payment_user_name }}</td>
            </tr>

            <tr>
                <td>URL</td>
                <td>{{ $details->payment_url }}</td>
            </tr>
            <tr>
                <td>Returned URL</td>
                <td>{{ $details->payment_return_url }}</td>
            </tr>
            <tr>
                <td>URL</td>
                <td>{{ $details->payment_password }}</td>
            </tr>

            <tr>
                <td>Unique code</td>
                <td>{{ $details->payment_unique_code }}</td>
            </tr>
            <tr>
                <td>webhook</td>
                <td>{{ $details->payment_webhook }}</td>
            </tr>
            <tr>
                <td>status</td>
                <td>{{ $details->status }}</td>
            </tr>
            <tr>
                <td>Payment for</td>
                @if($details->for_all===1)<td>All School</td>
                @else
                    <td>
                        <ul>
                            @foreach($schools as $school)
                                <li>{{ $school->school_name }}</li>
                        @endforeach
                    </td>
                    </td>
                @endif
            </tr>

            <tr>
                <td>Create Date</td>
                <td>{{ $details->created_at }}</td>
            </tr>
        </table>
    </div>
</div>
