<div class="row">
	<div class="col-md-12">
		<form method="POST" id="PostForm" action="{{ route('notices.store') }}/store">
			@csrf
			<div class="card-body">
				<input type="hidden" name="hidden_notice_id" id="hidden_notice_id" value="">
				<div class="form-group">
					<label for="notice_title">Notice Title <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="notice_title" placeholder="Title" value="" name="notice_title" required>
                    <div class="invalid-notice_title"></div>
				</div>
				<div class="form-group">
					<label for="notice_for">Notice For <span class="text-danger">*</span></label>
					<select class="form-control" id="notice_for" name="notice_for" placeholder="School" required>
						<option value="All">All School</option>
						@foreach($schools as $school)
							<option value="{{ $school->id }}" >{{ $school->school_name }}</option>
    					@endforeach
					</select>
                    <div class="invalid-notice_for"></div>

                </div>
				<div class="form-group">
					<label for="notice_body">Notice Body <span class="text-danger">*</span></label>
					<textarea class="form-control" rows="8" id="notice_body" placeholder="Body" name="notice_body" required></textarea>
                    <div class="invalid-notice_body"></div>

                </div>
			</div>
		</form>
	</div>
</div>
