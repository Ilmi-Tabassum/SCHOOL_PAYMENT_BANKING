<div class="row">
	<div class="col-md-12">
		<form method="POST" action="{{ route('class_info.store') }}">
			@csrf
			<div class="card-body">
				<input type="hidden" name="hidden_class_id" id="hidden_class_id" value="">
				<div class="form-group">
					<label for="class_name">Class Name</label>
					<input type="text" class="form-control" id="class_name" placeholder="Class Name" name="class_name">
				</div>
			</div>
		</form>
	</div>
</div>