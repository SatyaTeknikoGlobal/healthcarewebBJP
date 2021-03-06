@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$roles_id = (isset($roles->id))?$roles->id:'';
$name = (isset($roles->name))?$roles->name:'';
$status = (isset($roles->status))?$roles->status:'';


$routeName = CustomHelper::getAdminRouteName();
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

                                <input type="hidden" name="id" value="{{$roles_id}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Role Name</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Role Name" name="name" value="{{ old('name', $name) }}">
                                         @include('snippets.errors_first', ['param' => 'name'])
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" class="form-label">Status</label>
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
<script>

</script>