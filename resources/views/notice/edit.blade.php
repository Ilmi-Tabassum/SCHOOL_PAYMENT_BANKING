<div class="row">
	<div class="col-md-12">
		<form method="POST" id="PostForm" action="{{ route('notices.store') }}/store">
			@csrf
			<div class="card-body">
				<input type="hidden" name="hidden_notice_id" id="hidden_notice_id" value="{{ $details->id }}">
				<div class="form-group">
					<label for="notice_title">Notice Title <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="notice_title" placeholder="Title" value="{{ $details->notice_title }}" name="notice_title">
				</div>
				<div class="form-group">
					<label for="notice_for">Notice For <span class="text-danger">*</span></label>
					<select class="form-control" id="notice_for" name="notice_for">
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
					<label for="notice_body">Notice Body <span class="text-danger">*</span></label>
					<textarea class="form-control" rows="8" id="notice_body" placeholder="Body" name="notice_body">{{ $details->notice_body }}</textarea>
				</div>
			</div>
		</form>
	</div>
</div>