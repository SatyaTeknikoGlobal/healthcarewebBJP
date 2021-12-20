<?php
namespace App\Http\Controllers\Hospital;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\User;
use App\Hospital;
use App\AssignBookings;
use App\State;
use App\Category;
use App\Locality;
use App\City;
use App\Leads_documents;
use App\Speciality;
use App\SubCategory;
use App\Blocks;
use App\Flats;
use Yajra\DataTables\DataTables;

use App\Bookings;
use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class LeadController extends Controller
{

	public function index(Request $request){
		$data = [];       

    return view('hospital.leads.index',$data);
  }

  public function get_bookings(Request $request)
  {

   $user = Auth::guard('hospital')->user()->hospital_id;


   $routeName = CustomHelper::getHospitalRouteName();
   $datas = AssignBookings::where('hospital_id', $user)->orderBy('id','desc');
   $datas = $datas->get();

   // print_r($datas);


   return Datatables::of($datas)

   ->editColumn('id', function(AssignBookings $data) {

     return  $data->id;
   })

   ->editColumn('booking_id', function(AssignBookings $data) {

        // return  $data->booking_id;

    $user = Bookings::select('unique_id')->where('id',$data->booking_id)->first();
    return  $user->unique_id ?? '';
  })
   ->editColumn('hospital_id', function(AssignBookings $data) {

    $hospital_name = Hospital::select('name')->where('id',$data->hospital_id)->first();
    return  $hospital_name->name ?? '';
  })
   ->editColumn('description', function(AssignBookings $data) {
     return  $data->description;
   })   


   ->editColumn('status', function(AssignBookings $data) {
     $sta = '';
     $sta1 ='';
     if($data->status == 1){
      $sta1 = 'selected';
    }else{
      $sta = 'selected';
    }

    $html = "<select id='change_admins_status$data->id'>
    <option value='1' ".$sta1.">Active</option>
    <option value='0' ".$sta.">InActive</option>
    </select>";

    return  $html;
  })



   ->addColumn('action', function(AssignBookings $data) {
     $routeName = CustomHelper::getHospitalRouteName();

     $BackUrl = $routeName.'/hospital';
     $html = '';

     $html.='<a title="Edit" href="' . route($routeName.'.leads.details',$data->id.'?back_url='.$BackUrl) . '" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-info-circle"></i></a>&nbsp;&nbsp;&nbsp;';        

     return $html;
   })

   ->rawColumns(['booking_id', 'hospital_id','description','status', 'action'])
   ->toJson();
 }


 public function details(Request $request)
 {


  $data = [];
  $id = isset($request->id) ? $request->id : '';


  $booking = AssignBookings::where('id', $id)->first();
  $hospital_id = $booking->hospital_id;


  $hospital_details = Hospital::where('id',$hospital_id)->first();

  $exists = AssignBookings::where('booking_id', $booking->booking_id)->where('hospital_id', $hospital_id)->where('description','!=',null)->first();
  //prd($hospital_details);

  $page_heading = "Leads Information";
  $data['hospital_details'] = $hospital_details;

 // $data['specialities'] =  $specialities;

  $data['booking'] = $booking;

  $data['exists'] = $exists;

  $data['page_heading'] = $page_heading;


  return view('hospital.leads.details',$data);

}


public function upload_documents(Request $request)
{
  $id = isset($request->id) ? $request->id : '';
  $method = $request->method();
  if($method == "POST" || $method == "post")
  {

    $rules = [];

    //$rules['documents'] = 'required';
    $rules['description'] = 'required'; 

    $this->validate($request , $rules);

    $details = new AssignBookings;

    $details->id = $request->id;
    $details->description = $request->description;
    AssignBookings::where('id',$request->id)->update(['description' => $request->description]);
    $files = $request->file('documents');


    $path = 'leads_documents/';
    $storage = Storage::disk('public');
    $dbArray = [];

   if ($files && count($files) > 0) {
      foreach($files as $file){
        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
        if($uploaded_data['success']){
          $image = $uploaded_data['file_name'];
          $dbArray['documents'] = $image;
          $dbArray['leads_id'] = $id;
          $dbArray['type'] = $uploaded_data['extension'];
          $success = DB::table('leads_documents')->insert($dbArray);
        }
      }
    }
    return back()->with('alert-success', 'Document Uploaded Successfully');
  }
   return back()->with('alert-success', 'Document Uploaded Successfully');
}


//   private function saveImageMultiple($request,$id){

//     $files = $request->file('documents');

//      // die;
//     $path = 'leads_documents/';
//     $storage = Storage::disk('public');
//             //prd($storage);
//     $IMG_WIDTH = 768;
//     $IMG_HEIGHT = 768;
//     $THUMB_WIDTH = 336;
//     $THUMB_HEIGHT = 336;
//     $dbArray = [];

//     if (!empty($files)) {

//         foreach($files as $file){
//             $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
//             if($uploaded_data['success']){
//                 $image = $uploaded_data['file_name'];
//                 $dbArray['file'] = $image;
//                 $dbArray['id'] = $id;

//                 $success = DB::table('leads_documents')->create($dbArray);
//             }
//         }
//         return true;
//     }else{
//         return false;
//     }
// }












//     public function profile(Request $request)
//     {     

//     $id = (isset($request->id))?$request->id:0;
//     $data = [];

//     $user = Auth::guard('hospital')->user();

//     $hospital = Hospital::where('id',$user->id)->first();

//    // prd($hospital);

//     $galleries = $hospital->getGalleryImage;
//     $documents = $hospital->getDocuments;
//     $roles = $hospital->getRole;
//     $users = $hospital->getUser;



//     $doctors = $hospital->getDoctor;



//     $data['states'] = State::where('status',1)->get();
//     $data['cities'] = City::where('state_id',$hospital->state_id)->get();
//     $data['localities'] = Locality::where('city_id',$hospital->city_id)->get();

//     $data['hospital'] = $hospital;
//     $data['roles'] = $roles;
//     $data['documents'] = $documents;
//     $data['doctors'] = $doctors;

//     $data['users'] = $users;
//     $data['galleries'] = $galleries;

//     return view('hospital.home.profile',$data);

// }




// public function get_sub_cat(Request $request){
//   $cat_id = isset($request->cat_id) ? $request->cat_id : '';
//   $html = '<option value="" selected disabled>Select Sub Category</option>';
//   if(!empty($cat_id)){
//     $subcategories = SubCategory::where('cat_id',$cat_id)->get();
//     if(!empty($subcategories)){
//         foreach($subcategories as $sub_cat){
//             $html.='<option value='.$sub_cat->id.' >'.$sub_cat->name.'</option>';
//         }
//     }
// }


// echo $html;

// }



// private function saveImage($file, $id,$type){
//         // prd($file); 
//         //echo $type; die;

//     $result['org_name'] = '';
//     $result['file_name'] = '';

//     if ($file) 
//     {
//         $path = 'user/';
//         $thumb_path = 'user/thumb/';
//         $IMG_WIDTH = 768;
//         $IMG_HEIGHT = 768;
//         $THUMB_WIDTH = 336;
//         $THUMB_HEIGHT = 336;

//         $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
//         if($uploaded_data['success']){
//             $new_image = $uploaded_data['file_name'];

//             if(is_numeric($id) && $id > 0){
//                 $user = Admin::find($id);

//                 if(!empty($user) && $user->id > 0){

//                     $storage = Storage::disk('public');

//                     if($type == 'file'){
//                         $old_image = $user->image;
//                         $user->image = $new_image;
//                     }

//                     $isUpdated = $user->save();

//                     if($isUpdated){

//                         if(!empty($old_image) && $storage->exists($path.$old_image)){
//                             $storage->delete($path.$old_image);
//                         }

//                         if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
//                             $storage->delete($thumb_path.$old_image);
//                         }
//                     }
//                 }


//             }
//         }

//         if(!empty($uploaded_data))
//         {   
//             return $uploaded_data;
//         }
//     }
// }


// public function setting(Request $request){
//     $data =[];  

//     $method = $request->method();

//     if($method == 'POST' || $method =="post"){

//         $dbArray = [];

//         $dbArray['privacy'] = isset($request->privacy) ? $request->privacy:'';
//         $dbArray['terms'] = isset($request->terms) ? $request->terms:'';
//         $dbArray['about'] = isset($request->about) ? $request->about:'';

//         DB::table('setting')->where('id',1)->update($dbArray);
//         $data['settings'] = DB::table('setting')->where('id',1)->first();
//         return back()->with('alert-success','Updated Successfully');
//     }

//     $data['settings'] = DB::table('setting')->where('id',1)->first();

//     return view('admin.home.settings',$data);

// }















// public function change_password(Request $request){
//     //prd($request->toArray());
//     $data = [];
//     $password = isset($request->password) ?  $request->password:'';
//     $new_password = isset($request->new_password) ?  $request->new_password:'';
//     $method = $request->method();

//         //prd($method);
//     $auth_user = Auth::guard('admin')->user();
//     $admin_id = $auth_user->id;
//     if($method == 'POST' || $method =="post"){
//         $post_data = $request->all();
//         $rules = [];

//         $rules['old_password'] = 'required|min:6|max:20';
//         $rules['new_password'] = 'required|min:6|max:20';
//         $rules['confirm_password'] = 'required|min:6|max:20|same:new_password';

//         $validator = Validator::make($post_data, $rules);

//         if($validator->fails()){
//             return back()->withErrors($validator)->withInput();
//         }
//         else{
//                 //prd($request->all());

//             $old_password = $post_data['old_password'];

//             $user = Admin::where(['id'=>$admin_id])->first();

//             $existing_password = (isset($user->password))?$user->password:'';

//             $hash_chack = Hash::check($old_password, $user->password);

//             if($hash_chack){
//                 $update_data['password']=bcrypt(trim($post_data['new_password']));

//                 $is_updated = Admin::where('id', $admin_id)->update($update_data);

//                 $message = [];

//                 if($is_updated){

//                     $message['alert-success'] = "Password updated successfully.";
//                 }
//                 else{
//                     $message['alert-danger'] = "something went wrong, please try again later...";
//                 }

//                 return back()->with($message);


//             }
//             else{
//                 $validator = Validator::make($post_data, []);
//                 $validator->after(function ($validator) {
//                     $validator->errors()->add('old_password', 'Invalid Password!');
//                 });
//                     //prd($validator->errors());
//                 return back()->withErrors($validator)->withInput();
//             }
//         }
//     }



// }

// // public function profile(Request $request){
// //     $data = [];


// //     return view('admin.home.profile',$data);
// // }

// public function upload(Request $request){
//    $data = [];
//    $method = $request->method();
//    $user = Auth::guard('admin')->user();

//    if($method == 'post' || $method == 'POST'){
//        $request->validate([
//         'file' => 'required',
//     ]);

//        if($request->hasFile('file')) {
//         $file = $request->file('file');
//         $image_result = $this->saveImage($file,$user->id,'file');
//         if($image_result['success'] == false){     
//             session()->flash('alert-danger', 'Image could not be added');
//         }
//     }
//     return back()->with('alert-success','Profile Updated Successfully');
// }
// }






// public function get_city(Request $request){
//     $state_id = isset($request->state_id) ? $request->state_id :0;
//     $html = '<option value="" selected disabled>Select City</option>';
//     if($state_id !=0){
//         $cities = City::where('state_id',$state_id)->get();
//         if(!empty($cities)){
//             foreach($cities as $city){
//                 $html.='<option value='.$city->id.'>'.$city->name.'</option>';
//             }
//         }
//     } 
//     echo $html;
// }


//     public function get_locality(Request $request){
//         $city_id = isset($request->city_id) ? $request->city_id :0;
//         $html = '<option value="" selected disabled>Select Locality</option>';
//         if($city_id !=0){
//             $locality = Locality::where('city_id',$city_id)->get();
//             if(!empty($locality)){
//                 foreach($locality as $local){
//                     $html.='<option value='.$local->id.'>'.$local->name.'</option>';
//                 }
//             }
//         }
//         echo $html;
//     }



//     public function cmsPage(Request $request){
//     $data = [];

//     return view('admin.home.cmspage',$data);
// }


// public function get_blocks(Request $request){
//    $society_id = isset($request->society_id) ? $request->society_id :0;
//    $html = '<option value="0" selected="" disabled >Select Society</option>';
//    if($society_id !=0){
//     $blocks = Blocks::where('society_id',$society_id)->get();
//     if(!empty($blocks)){
//         foreach($blocks as $block){
//             $html.='<option value='.$block->id.'>'.$block->name.'</option>';
//         }
//     }
// } 
// echo $html;


// }


// public function get_flats(Request $request){
//    $block_id = isset($request->block_id) ? $request->block_id :0;
//    $html = '<option value="0" selected="" disabled >Select Flats</option>';
//    if($block_id !=0){
//     $flats = Flats::where('block_id',$block_id)->get();
//     if(!empty($flats)){
//         foreach($flats as $flat){
//             $html.='<option value='.$flat->id.'>'.$flat->flat_no.'</option>';
//         }
//     }
// } 
// echo $html;


// }



// public function upload_xls(Request $request){
//     $method = $request->method();
//      $data = [];
//      $html= '';
//     if($method =='post' || $method == 'POST'){
//              $phpWord = IOFactory::createReader('Word2007')->load($request->file('file')->path());
//              $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
//              $objWriter->save('doc.html');
//              $page = file_get_contents('https://mydoor.appmantra.live/doc.html');



//              DB::table('new')->insert(['text'=>$page]);
//                  echo $page;
//                  die;

//     foreach($phpWord->getSections() as $section) {
//         foreach($section->getElements() as $element) {
//             if(method_exists($element,'getText')) {
//                 $html.=$element->getText();
//             }
//         }
//     }
//     }

//     $data['html'] = $html;

//     return view('admin.home.upload_file',$data);


// }





}