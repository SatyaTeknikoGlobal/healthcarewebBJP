@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
$roleId = Auth::guard('admin')->user()->role_id;

?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Chat With Users</h4>
            </div>
            <div class="col-md-7 align-self-center text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Chat With Users</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-0">
                    <!-- .chat-row -->
                    <div class="chat-main-box">
                        <!-- .chat-left-panel -->
                        <div class="chat-left-aside">
                            <div class="open-panel"><i class="ti-angle-right"></i></div>
                            <div class="chat-left-inner" style="height: 71px;">
                                <div class="form-material">
                                    <input class="form-control p-2" type="text" id="search" placeholder="Search Contact">
                                </div>
                                <ul class="chatonline style-none ps ps--theme_default ps--active-y" data-ps-id="ebaf5428-568b-1f1b-b861-a1a4b7ededc3">
                                    <p id="user_list">

                                    </p>
                                    <li class="p-20"></li>
                                    <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px; height: 486px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 364px;"></div></div></ul>
                                </div>
                            </div>

                            <div class="chat-right-aside">
                                <div class="chat-main-header">
                                    <div class="p-3 b-b">
                                        <h4 class="box-title" id="user_name"></h4>
                                    </div>
                                </div>
                                <div class="chat-rbox ps ps--theme_default ps--active-y" id="chat_scroll" data-ps-id="e99f3cff-27e1-6e99-b1f0-e61b9db06e57">
                                    <ul class="chat-list p-3" style="height: 0px;">
                                        <!--chat Row -->
                                        <p id="chat-data">

                                        </p>




                                    </ul>
                                    <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: -376px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 376px; right: 0px; height: 32px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 17px; height: 1px;"></div></div>
                                </div>
                                <div class="card-body border-top">
                                    <div class="row">
                                        <div class="col-8">
                                            <textarea placeholder="Type your message here" id="message" class="form-control border-0"></textarea>
                                        </div>
                                        <div class="col-4 text-end">
                                            <button type="button" id="send_message" class="btn btn-info btn-circle btn-lg text-white"><i class="fas fa-paper-plane"></i> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .chat-right-panel -->
                        </div>
                        <!-- /.chat-row -->
                    </div>
                </div>
            </div>


        </div>
    </div>


    <input type="hidden" name="user_id" id="user_id" value="{{$user_id ?? 0}}">
<input type="hidden" name="page" id="page" value="1">




@include('admin.common.footer')


<script>
$( document ).ready(function() {

var search='';
var user_id = $('#user_id').val();
get_user_list(search='',user_id);
get_user_name(user_id);

var page = 1;

$('#chat_scroll').on('scroll',function() {
  page++;
  var user_id = $('#user_id').val();

  $('#page').val(page);
  get_user_chat(user_id,page);
}); 

get_user_chat(user_id,page);
});


$(document).ready(function(){
 var user_id = $('#user_id').val();
  var page = $('#page').val();
 setInterval(get_user_chat(user_id,page),2000);
});





        $("#search").keyup(function(){
           var search = $('#search').val();
           get_hospital_list(search)
       });


        function get_user_name(user_id){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.chat_with_user.get_user_name') }}",
                type: "POST",
                data: {user_id:user_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#user_id').html(resp);
                }
            });

        }



        function get_user_list(search='',user_id=''){
            //alert(hospital_id);
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.chat_with_user.get_user_list') }}",
                type: "POST",
                data: {search:search,user_id:user_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    //$('#user_id').html(resp);
                    $('#user_list').html(resp);
                }
            });

        }






        function get_user_chat(user_id='',page=1){


            $('#user_id').val(user_id);
            if(page == 1){
                $("#chat-data").html('');
            }

            get_user_name(user_id);
            get_user_list(search='',user_id)

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.chat_with_user.get_user_chat') }}",
                type: "get",
                data: {user_id:user_id,page:page},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                //$('#hospital_list').html(resp);
                //alert(resp);
                $("#chat-data").html(resp);
            }
        });

        }


    $("#send_message").click(function(){
        var user_id = $('#user_id').val();
        var page = $('#page').val();
        var message = $('#message').val();

        if(message==''){
                alert('Type Something!!');

            return false;
        }

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.chat_with_user.send_message') }}",
                type: "get",
                data: {user_id:user_id,message:message},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                $('#message').val('');
                get_user_chat(user_id,page);
            }
        });
       });


    </script>
