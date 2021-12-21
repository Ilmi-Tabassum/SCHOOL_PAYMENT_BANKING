<!DOCTYPE html>
<html lang="en">
	@include('common.page-header')
	<body class="hold-transition sidebar-mini layout-fixed">
		<div class="wrapper" style="background-color: #f4f6f9">
			@include('common.preloader')

			<!-- Top navigation bar start-->
			@include('common.top-navbar')
			<!-- Top navigation bar end-->

			<!-- left navigationbar start -->
			@include('common.left-navbar')
			<!-- left navigationbar end -->
			<div class="content-wrapper">
				<div id="page-content" style="margin-top: 20px;margin-left: 20px">
					<!-- Alert part -->
					<div id="page-content" style="margin-top: 20px;margin-left: 20px">
					  @if(Session::has('success'))
					  <div class="row">
					      <div class="col-sm-12">
					          <div id="alertMessage" class="alert alert-success collapse">
					               <i class="nav-icon fas fa-info-circle"></i> {{ Session::get('success') }}
					               <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-times"></i></a>
					          </div>
					      </div>
					  </div>
					  @endif
					 


					  @if(Session::has('error'))
					  <div class="row">
					      <div class="col-sm-12">
					          <div id="alertMessage" class="alert alert-danger collapse">
					                <i class="nav-icon fas fa-exclamation-triangle"></i>  {{ Session::get('error') }}
					               <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-trash-alt"></i></a>
					          </div>
					      </div>
					  </div>
					  @endif  
					</div> 
					

					<!-- ./ alert part --> 
					<div id="page-content" style="margin-top: 20px;margin-left: 20px">
						<button type="button" class="btn btn-primary CallModal" data-submit="Save" data-title="Add New Notice" data-toggle="modal" data-target="#modal-custom" data-url="{{ route('notices.create') }}" style="background-color:#ee1b22;border-color:#ee1b22 ">
						   <i class="fa fa-plus" aria-hidden="true"></i> Add Notice
						</button>
						            
						<a href="{{ route('notices') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
						  <i class="fa fa-eye" aria-hidden="true"></i> View Notice
						</a>
						            
						<a href="{{ route('notices') }}?gen=trash" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
						  <i class="fa fa-trash" aria-hidden="true"></i> View Trash
						</a>
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12"><h3>Notice List</h3></div>
						</div>  
						<div style="clear:both; height:10px;"></div>
						<table class="table table-hover table-bordered table-condensed table-striped">
							<thead>
								<tr>
									<th>SL</th>
									<th>Title</th>
									<th>Status</th>
									<th colspan="3">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$table_option = "";
        						$sl = 1;
        						foreach ($notices as $key => $value) {
								?>
								<tr>
									<td>{{$sl++}}</td>
									<td>{{$value->notice_title}}</td>
									<td>@if($value->status =='1') Active @else Inactive @endif</td>
									<td>
										<a href="javascript:;" data-title="Notice Details" data-id="{{$value->id}}" data-toggle="modal" data-url="{{ route('notices.details',$value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
											<i class='nav-icon fas fa-eye text-success'></i>
										</a>
									</td>
									<td>
										<a href="javascript:;" data-id="{{$value->id}}" data-original-title="Edit Notice" data-toggle="modal" data-url="{{ route('notices.edit', $value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
											<i class='nav-icon fas fa-edit text-success'></i>
										</a>
									</td>
									<td>
										<a href="{{ route('notices.destroy', $value->id) }}" class="tooltip-button confirm_delete_dialog">
											<i class='nav-icon fas fa-trash text-danger'></i>
										</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="d-flex">
						    <div class="mx-auto">
						        {{$notices->links("pagination::bootstrap-4")}}
						    </div>
						</div> 
					</div>

				</div>
			</div>
		</div>
		@include('common.page-script')
   		@yield('custom-script')
	</body>
</html>