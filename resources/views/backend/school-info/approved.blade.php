{{--mowmita
bugfixed for offset 0 error--}}
<table class="table table-hover table-condensed table-bordered table-stripedd">

    <tbody>


    <tr>
        <th>School 	Name</th>
        @if(!empty($school))
            <td>{{ $school[0]->school_name }}</td>
        @endif
    </tr>

    <tr>
        <th>School EIN</th>
        @if(!empty($school))
            <td>{{ $school[0]->school_ein }}</td>
        @endif
    </tr>

    <tr>
        <th>School Mobile</th>
        @if(!empty($school))
            <td>{{ $school[0]->school_mobile }}</td>
        @endif
    </tr>

    <tr>
        <th>School Email</th>
        @if(!empty($school))
            <td>{{ $school[0]->school_email }}</td>
        @endif
    </tr>

    <tr>
        <th>School District</th>
        @if(!empty($school))
            <td>{{ $school[0]->division_name }}</td>
        @endif
    </tr>

    <tr>
        <th>School Division</th>
        @if(!empty($school))
            <td>{{ $school[0]->district_name }}</td>
        @endif
    </tr>

    <tr>
        <th>School Post</th>
        @if(!empty($school))
            <td>{{ $school[0]->post_name }}</td>
        @endif
    </tr>

    <tr>
        <th>School Address</th>
        @if(!empty($school))
            <td>{{ $school[0]->school_address }}</td>
        @endif
    </tr>

    </tbody>

</table>

      <div>
          @if(!empty($school))
      		<a href="{{ route('school_info.approved', $school[0]->id) }}" class='btn btn-danger custom-button float-right white-color'> Approve This</a>
          @endif
      </div>


