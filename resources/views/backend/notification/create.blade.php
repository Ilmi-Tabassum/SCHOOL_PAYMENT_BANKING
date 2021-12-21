<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('all_notification.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <input type="hidden" name="hidden_notification_id" id="hidden_notification_id" value="">
                <div class="form-group">
                    <label for="notification_title">Notification Title<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="notification_title" placeholder="Title" value="" name="notification_title" required>
                    <div class="invalid-notification_title"></div>
                </div>
                <div class="form-group">
                    <label for="notification_for">Notification For<span class="text-danger">*</span></label>
                    <select class="form-control" id="notification_for" name="notification_for" required>
                        <option value="All">All School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" >{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-notification_for"></div>
                </div>
                <div class="form-group">
                    <label for="notification_body">Notification Body <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="8" id="notification_body" placeholder="Body" name="notification_body" required></textarea>
                    <div class="invalid-notification_body"></div>

                </div>
            </div>
        </form>
        <!-- /.card -->
    </div>

</div>
