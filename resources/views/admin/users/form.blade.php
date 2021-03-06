@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$users_id = (isset($users->id))?$users->id:'';
$name = (isset($users->name))?$users->name:'';
$description = (isset($users->description))?$users->description:'';
$location = (isset($users->location))?$users->location:'';
$state_id = (isset($users->state_id))?$users->state_id:'';
$city_id = (isset($users->city_id))?$users->city_id:'';
$username = (isset($users->username))?$users->username:'';
$phone = (isset($users->phone))?$users->phone:'';
$address = (isset($users->address))?$users->address:'';
$society_id = (isset($users->society_id))?$users->society_id:'';
$is_approve = (isset($users->is_approve))?$users->is_approve:'';
$role_id = (isset($users->role_id))?$users->role_id:'';
$email = (isset($users->email))?$users->email:'';
$local_id= (isset($users->locality_id))?$users->locality_id:'';



$status = (isset($users->status))?$users->status:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';



?>


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
            <div class="col-12">
            @include('snippets.errors')
            @include('snippets.flash')
            <!-- table responsive -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $page_heading }}</h4>
                        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form" class="mt-4">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{$users_id}}">




                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter  Name" name="name" value="{{ old('name', $name) }}">
                                @include('snippets.errors_first', ['param' => 'name'])
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" name="email" value="{{ old('email', $email) }}">
                                @include('snippets.errors_first', ['param' => 'email'])
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Phone" name="phone" value="{{ old('phone', $phone) }}">
                                @include('snippets.errors_first', ['param' => 'phone'])
                            </div>



                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">Address</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Address" name="address" value="{{ old('address', $address) }}">
                                @include('snippets.errors_first', ['param' => 'address'])
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    <option value="" selected disabled>Select State</option>
                                    <?php
                                        if(!empty($states)){
                                            foreach ($states as $state){
                                    ?>
                                        <option value="{{$state->id}}" <?php if($state->id == $state_id) echo 'selected';?>>{{$state->name}}</option>
                                    <?php }}?>
                                </select>
                                @include('snippets.errors_first', ['param' => 'state_id'])
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">City</label>
                                <select class="form-control" name="city_id" id="city_id">
                                    <option value="" selected disabled>Select City</option>
                                    <?php
                                    if(!empty($cities)){
                                    foreach ($cities as $city){
                                    ?>
                                    <option value="{{$city->id}}" <?php if($city->id == $city_id) echo 'selected';?>>{{$city->name}}</option>
                                    <?php }}?>
                                </select>
                                @include('snippets.errors_first', ['param' => 'city_id'])
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1" class="form-label">Locality</label>
                                <select class="form-control" name="locality_id" id="locality_id">
                                    <option value="" selected disabled>Select locality</option>
                                    <?php
                                    if(!empty($locality)){
                                    foreach ($locality as $local){
                                    ?>
                                    <option value="{{$local->id}}" <?php if($local->id == $local_id) echo 'selected';?>>{{$local->name}}</option>
                                    <?php }}?>
                                </select>
                                @include('snippets.errors_first', ['param' => 'locality_id'])
                            </div>



                            <div class="form-group">
                                <label for="exampleInputPassword1" class="form-label">Status</label>
                                <br>
                                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                &nbsp;
                                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                @include('snippets.errors_first', ['param' => 'status'])
                            </div>




                            <button type="submit" class="btn btn-primary text-white">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>


@include('admin.common.footer')
