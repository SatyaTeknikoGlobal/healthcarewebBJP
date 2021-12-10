@include('admin.common.header')

<style type="text/css">
	.heading {
		font-weight: 500 !important
	}

	.subheadings {
		font-size: 12px;
		color: #9c27b0
	}
</style>

<div class="page-wrapper">
	<div class="container-fluid">
		<div class="row page-titles">
			<div class="col-md-5 align-self-center">
				<h4 class="text-themecolor">{{ $page_heading }}</h4>
			</div>
			<div class="col-md-7 align-self-center text-end">
				<div class="d-flex justify-content-end align-items-center">
					<ol class="breadcrumb justify-content-end">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
						<li class="breadcrumb-item active">{{ $page_heading }}</li>
					</ol>
					<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
					<a href="{{ url($back_url)}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 text-white"><i
						class="fa fa-arrow-left"></i>  Back</button></a>
					<?php } ?>
				</div>
			</div>
		</div>

		
		<div class="row">
			<!-- Column -->
			<div class="col-lg-12 col-xlg-12 col-md-12">
				<div class="card">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs profile-tab" role="tablist">
						<li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#details" role="tab">Details</a> </li>

						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#assign_hospital" role="tab">Assign Hospital</a> </li>

						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#profile" role="tab">Profile</a> </li>

						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#appointment" role="tab">Appointment History</a> </li>
						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#prescription" role="tab">Prescription</a> </li>

						<li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#transaction" role="tab">Transaction History</a> </li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">


						<div class="tab-pane active" id="details" role="tabpanel">
							<div class="card-body">

						<!--------------- DETAILS ------------------------->


								<div class="row">
									<div class="col-md-8 border-right">
										<div class="status p-3">
											<table class="table table-borderless">
												<tbody>
													<tr>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Hospital</span> <span class="subheadings">{{$hospital->name ?? ''}}</span> </div>
														</td>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Time/Date</span> <span class="subheadings">{{date('d M Y',strtotime($booking->appointment_date))}}</span> </div>
														</td>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Status</span> <span class="subheadings"><i class="dots"></i> Booked</span> </div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Speciality</span> <span class="subheadings">{{$speciality->name ?? ''}}</span> </div>
														</td>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Referring Doctor</span> <span class="subheadings">Dr. Harry Pimn</span> </div>
														</td>
														<td> </td>
													</tr>
													<tr>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Contact</span> <span class="subheadings">{{$hospital->address ?? ''}}</span> </div>
														</td>
														<td colspan="2">
															<div class="d-flex flex-column"> <span class="heading d-block">Reason of visiting</span> <span class="subheadings">Lorem ipsum is placeholder text commonly used in the graphic, print.</span> </div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="d-flex flex-column"> <span class="heading d-block">Direction</span> <span class="d-block subheadings">Get direction by using</span> <span class="d-flex flex-row">
																<?php if(!empty($hospital->latitude) && !empty($hospital->longitude)){?>

																	<a href='https://maps.google.com/?q={{$hospital->latitude}},{{$hospital->longitude}}' target="_blank">


																	<img src="https://img.icons8.com/color/100/000000/google-maps.png" class="rounded" width="30" />
																</a>
																	<?php }?>
															
															</span> 
														</div>
													</td>
													<td colspan="2">
														<div class="d-flex flex-column"> <span class="heading d-block">Hospital Gallary</span> <span class="d-flex flex-row gallery"> 
															<img src="https://i.imgur.com/VfRSLTm.jpg" width="50" class="rounded"> 
															<img src="https://i.imgur.com/jb9Cy5h.jpg" width="50" class="rounded"> 
															<img src="https://i.imgur.com/vBUz4HA.jpg" width="50" class="rounded">



														</span> 
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-4">
								<div class="p-2 text-center">
									<div class="profile"> <img src="https://i.imgur.com/VfRSLTm.jpg" width="100" class="rounded-circle img-thumbnail"> <span class="d-block mt-3 font-weight-bold">Dr. Samsung Philip.</span> </div>
									<div class="about-doctor">
										<table class="table table-borderless">
											<tbody>
												<tr>
													<td>
														<div class="d-flex flex-column"> <span class="heading d-block">Education</span> <span class="subheadings">University of Harward</span> </div>
													</td>
													<td>
														<div class="d-flex flex-column"> <span class="heading d-block">Language</span> <span class="subheadings">Spanish, English</span> </div>
													</td>
												</tr>
												<tr>
													<td>
														<div class="d-flex flex-column"> <span class="heading d-block">Organisation</span> <span class="subheadings">Accupunture</span> </div>
													</td>
													<td>
														<div class="d-flex flex-column"> <span class="heading d-block">Specialist</span> <span class="subheadings">Accupunture</span> </div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>



						</div>

				<!--------------- DETAILS ------------------------->



					</div>


				</div>

				<div class="tab-pane " id="profile" role="tabpanel">
					<div class="card-body">
						<center class="m-t-30"> <img  src="https://healthcareweb.appmantra.live/public/storage/hospital/071221055710-hospital.jpg" class="img-circle" width="150">
							<h4 class="card-title m-t-10">{{$user->name ?? ''}}</h4>
							<b>Address</b>
							<h5>{{$user->address ??''}}</h5>
							<hr>
							<b>Phone : {{$user->phone ?? ''}}</b></br>

							<b>Email : {{$user->email ?? ''}}</b>
						</center>



					</div>


				</div>


				<div class="tab-pane" id="appointment" role="tabpanel">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12 col-xs-12 b-r">
								<div class="table-responsive">
									<table id="myTable" class="table display table-striped border no-wrap">
										<thead>
											<tr>
												<th scope="col">#Booking ID</th>
												<th scope="col">Hospital Name</th>
												<th scope="col">Appointment Date</th>
												<th scope="col">Date Created</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty($appointments)){
												foreach ($appointments as $key) {
													$hospital = \App\Hospital::where('id',$key->hospital_id)->first();
													?>
													<tr>
														<td>{{$key->unique_id}}</td>

														<td>{{$hospital->name ?? ''}}</td>

														<td>{{$key->appointment_date}}</td>

														<td>{{$key->created_at}}</td>
													</tr>

												<?php }}?>

											</tbody>

										</table>
									</div>

								</div>

							</div>
						</div>

					</div>

					<div class="tab-pane" id="users" role="tabpanel">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12 col-xs-12 b-r">
									<div class="table-responsive">

									</div>

								</div>

							</div>
						</div>
					</div>


					<div class="tab-pane" id="prescription" role="tabpanel">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12 col-xs-12 b-r">
									<div class="table-responsive">

									</div>

								</div>

							</div>
						</div>
					</div>


					<div class="tab-pane" id="transaction" role="tabpanel">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12 col-xs-12 b-r">
									<div class="table-responsive">

									</div>

								</div>

							</div>
						</div>
					</div>



				</div>
			</div>
		</div>
		<!-- Column -->
	</div>




























</div>
</div>









@include('admin.common.footer')