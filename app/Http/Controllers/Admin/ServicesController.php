<?php
namespace App\Http\Controllers\Admin;
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
use App\Admin;
use App\Blocks;
use App\Society;
use App\Services;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class ServicesController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->roleId = Auth::guard('admin')->user()->role_id; 

	}



	public function index(Request $request){



        // CustomHelper::isAllowedSection('blockes' , 1 , $type='add');

     if(!(CustomHelper::isAllowedSection('services' , Auth::guard('admin')->user()->role_id , $type='show'))){
         return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.services.index'));

     }

     $data = [];
     return view('admin.services.index',$data);
 }

 public function get_services(Request $request){

  $role_id = Auth::guard('admin')->user()->role_id;

  $routeName = CustomHelper::getAdminRouteName();
  $datas = Services::where('is_delete',0)->orderBy('id','desc');
  if($role_id!=0){
    $datas->where('added_by',Auth::guard('admin')->user()->id);
}


$datas = $datas->get();

return Datatables::of($datas)


->editColumn('id', function(Services $data) {

   return  $data->id;
})

->editColumn('name', function(Services $data) {
   return  $data->name;
})


->editColumn('society_id', function(Services $data) {
    $society_name = '';
    if($data->society_id !=0){
        $society = Society::where('id',$data->society_id)->first();
        $society_name = $society->name;
    }


    return $society_name;
})

->editColumn('image', function(Services $data) {

    $html ='';
    $image = isset($data->image) ? $data->image :'';
    $storage = Storage::disk('public');
    $path = 'services/';
     if(!empty($image)){
        if($storage->exists($path.$image)){
            $html.="<a href='/storage/app/public/$path/thumb/$image' target='_blank'><img src='/storage/app/public/$path/thumb/$image' style='width:70px;'></a>";

        }}


    return  $html;
})

->editColumn('added_by', function(Services $data) {

    $user = Admin::where('id',$data->added_by)->first();
    return  isset($user->name) ? $user->name :'';
})

->editColumn('status', function(Services $data) {
   $sta = '';
   $sta1 ='';
   if($data->status == 1){
    $sta1 = 'selected';
}else{
    $sta = 'selected';
}

$html = "<select id='change_services_status$data->id' onchange='change_services_status($data->id)'>
<option value='1' ".$sta1.">Active</option>
<option value='0' ".$sta.">InActive</option>
</select>";





return  $html;
})

->editColumn('created_at', function(Services $data) {
   return  date('d M Y',strtotime($data->created_at));
})

->addColumn('action', function(Services $data) {
   $routeName = CustomHelper::getAdminRouteName();

   $BackUrl = $routeName.'/services';
   $html = '';
   if(CustomHelper::isAllowedSection('services' , Auth::guard('admin')->user()->role_id , $type='edit')){
    $html.='<a title="Edit" href="' . route($routeName.'.services.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
}   




return $html;
})

->rawColumns(['name', 'status', 'action','image'])
->toJson();
}




public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;
    if(!(CustomHelper::isAllowedSection('services' , Auth::guard('admin')->user()->role_id , $type='add'))){
     return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.services.index'));

 }
 $services = '';
 if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('services' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.services.index'));
    }




    $services = Services::find($id);
    if(empty($services)){
        return redirect($this->ADMIN_ROUTE_NAME.'/services');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/services';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];

    $rules['name'] = 'required';
   // $rules['society_id'] = 'required';




    $this->validate($request, $rules);

    $createdCat = $this->save($request, $id);

    if ($createdCat) {
        $alert_msg = 'Services has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'Services has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add Service';

if(isset($services->name)){
    $services_name = $services->name;
    $page_heading = 'Update Service - '.$services_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['services'] = $services;

//$data['societies'] = Society::where('status',1)->get();

return view('admin.services.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    if($id == 0){

        $role_id = Auth::guard('admin')->user()->role_id;
        if($role_id == 0){
        $data['added_by'] = 0; 

    }else{
        $data['added_by'] = Auth::guard('admin')->user()->id; 

    }

    }

    $role_id = Auth::guard('admin')->user()->role_id;
    if($role_id == 0){
        $data['society_id'] = 0;
    }else{
        $data['society_id'] = Auth::guard('admin')->user()->society_id;

    }


    $oldImg = '';

    $services = new Services;

    if(is_numeric($id) && $id > 0){
        $exist = Services::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $services = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $services->$key = $val;
    }

    $isSaved = $services->save();

    if($isSaved){
        $this->saveImage($request, $services, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $services, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'services/';
        $thumb_path = 'services/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

           // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($oldImg)){
                if($storage->exists($path.$oldImg)){
                    $storage->delete($path.$oldImg);
                }
                if($storage->exists($thumb_path.$oldImg)){
                    $storage->delete($thumb_path.$oldImg);
                }
            }
            $image = $uploaded_data['file_name'];
            $services->image = $image;
            $services->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = Services::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Blockes has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_services_status(Request $request){
  $service_id = isset($request->service_id) ? $request->service_id :'';
  $status = isset($request->status) ? $request->status :'';

  $services = Services::where('id',$service_id)->first();
  if(!empty($services)){

     Services::where('id',$service_id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'No Service FOund';
     return response()->json($response);
 }


}


}