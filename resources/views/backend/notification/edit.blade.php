<div class="row">
    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('all_notification.store') }}">
            @csrf
            <div class="card-body">
                <input type="hidden" name="hidden_notification_id" id="hidden_notification_id" value="{{ $details->id }}">
                <div class="form-group">
                    <label for="notification_title">Notification Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="notification_title" placeholder="Title" value="{{ $details->notification_title }}" name="notification_title">
                </div>
                <div class="form-group">
                    <label for="notification_for">Notification For <span class="text-danger">*</span></label>
                    <select class="form-control" id="notification_for" name="notification_for">
                        @if($details->for_all==1)
                            <option value="All" selected="selected">All School</option>
                        @else
                            <option value="All">All School</option>
                        @endif
                        @foreach($schools as $school)
                            @if(in_array($school->id,explode(',',$details->school_id)))

                                <option value="{{ $school->id }}" selected="selected">{{ $school->school_name }}</option>


                            @else
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="notification_body">Notification Body <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="8" id="notification_body" placeholder="Body" name="notification_body">{{ $details->notification_body }}</textarea>
                </div>
            </div>
        </form>
    </div>
</div>
