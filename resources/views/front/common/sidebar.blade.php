<style type="text/css">
    .edit{
            position: absolute;
    right: 38px;
    font-size: 17px;

    }

    .widget-profile .profile-info-widget .booking-doc-img{
        width: 100%;
    }

</style>
<?php 
$url = url()->current();


$baseurl = url('/');
  $user =  Auth::guard('appusers')->user();

?>



                     <div class="col-md-3 col-sm-4 col-xs-12 col-lg-3 profile-sidebar">
                        <div class="single-sidebar d-block">
                            <div class="widget-profile pro-widget-content">
                                <div class="profile-info-widget"><a class="booking-doc-img"  alt="User">

                                <form action="{{route('home.profile_img')}}" id="profile_form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                                    <button style="border: none;outline: 0;background: transparent;">
                                        <i class="fa fa-pencil theme-color mr-3 edit">                                       
                                    </i>
                                    <input type="file" name="profile_image" id="profile_image"  style="opacity: 0" onchange="profile_submit()">
                                    </button>
                                </form>
                               

                                    <img src="{{asset('storage/app/public/user/'.$user->image)}}" alt="">
                                    </a>
                                    <div class="profile-det-info text-left">
                                        <h3><i class="fa fa-user theme-color mr-3" aria-hidden="true"></i> {{auth()->guard('appusers')->user()->name ?? ''}}</h3>
                                        <h3> <i class="fa fa-envelope mr-3  theme-color" aria-hidden="true"></i> {{auth()->guard('appusers')->user()->email ?? ''}}</h3>
                                        <h3><i class="fa fa-phone mr-3 theme-color"></i> {{auth()->guard('appusers')->user()->phone ?? ''}}</h3>
                                    </div>
                                </div>
                            </div>

                            <ul class="categories clearfix">
                                <li class="<?php if($url == $baseurl.'/dashboard') echo "single-sidebar-active"?>"><a href="{{route('home.dashboard')}}"><i class="fa fa-columns mr-3"></i> Dashboard</a></li>

                                <li class="<?php if($url == $baseurl.'/profile') echo "single-sidebar-active"?>"><a href="{{route('home.profile')}}"><i class="fa fa-columns mr-3"></i> Profile</a></li>

                                <li class="<?php if($url == $baseurl.'/prescription_list') echo "single-sidebar-active"?>"><a href="{{route('home.prescription_list')}}"> <i class="fas fa-notes-medical mr-3"></i> Prescription </a></li>

                                <!-- <li><a href=""><i class="fa fa-user mr-3"></i> My Doctors</a></li> -->

                                <li class="<?php if($url == $baseurl.'/new-booking') echo "single-sidebar-active"?>"><a href="{{route('home.new_booking')}}"><i class="fa fa-user-plus mr-3" aria-hidden="true"></i> New Booking</a></li>

                                <li class="<?php if($url == $baseurl.'/booking-history') echo "single-sidebar-active"?>"><a href="{{route('home.booking_history')}}"><i class="fa fa-columns mr-3"></i>Booking History</a></li>

                               <!--  <li><a href=""><i class="fas fa-notes-medical mr-3"></i>Medical Records</a></li> -->

                                <li class="<?php if($url == $baseurl.'/shortlisted-hospital') echo "single-sidebar-active"?>"><a href="{{route('home.shortlisted_hospital')}}">
                                    <i class="fa fa-list-alt mr-3" aria-hidden="true"></i>
                                My Shortlisted Hospitals</a></li>

                                <li class="<?php if($url == $baseurl.'/chat-with-admin') echo "single-sidebar-active"?>"><a href="{{route('home.chat_with_admin')}}"><i class="fas fa-comments mr-3"></i>Chat With Admin</a></li>

                                <li class="<?php if($url == $baseurl.'/payment-history') echo "single-sidebar-active"?>"><a href="{{route('home.payment_history')}}">
                                    <i class="fa fa-history mr-3" aria-hidden="true"></i>

                                Payment History</a></li>                                 

                                 <li><a href="{{route('home.logout')}}"><i class="fa fa-sign-out mr-3"></i>Logout</a></li>
                            </ul>
                        </div>

                    </div>
 <script>
     
     function profile_submit()
     {
        
        document.getElementById("profile_form").submit();
     }
 </script>   
   
