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
use App\Society;
use App\Blocks;
use App\State;
use App\City;
use App\Roles;
use App\SocietyDocument;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class AdminController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
       if(!(CustomHelper::isAllowedSection('admins' , Auth::guard('admin')->user()->role_id , $type='show'))){
           return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admins.index'));

       }


       $data =[];
       return view('admin.admins.index',$data);
   }

   public function get_admins(Request $request){

      $role_id = Auth::guard('admin')->user()->role_id;

      $routeName = CustomHelper::getAdminRouteName();



      $datas = Admin::where('is_delete',0)->where('role_id','!=',0)->orWhere('role_id',NULL)->orderBy('id','desc');

      $datas = $datas->get();



      return Datatables::of($datas)


      ->editColumn('id', function(Admin $data) {

         return  $data->id;
     })
      ->editColumn('name', function(Admin $data) {
         return  $data->name;
     })
      ->editColumn('username', function(Admin $data) {
         return  $data->username;
     })

      ->editColumn('email', function(Admin $data) {
         return  $data->email;
     })

      ->editColumn('role_id', function(Admin $data) {
       $roles = Roles::where('status',1)->get();
        $html = "<select id='change_admins_role$data->id' onchange='change_admins_role($data->id)'>";
        $html.='<option value="" selected disabled>Select Role</option>';
        if(!empty($roles)){
            foreach($roles as $role){
                $selected = '';
                if($role->id == $data->role_id){
                    $selected = 'selected';
                }
                $html.='<option value='.$role->id.' '.$selected.'>'.$role->name.'</option>';

            }
        }
        $html.="</select>";
        return  $html;

    })
      ->editColumn('is_approve', function(Admin $data) {

         $sta = '';
         $sta1 ='';
         if($data->is_approve == 1){
            $sta1 = 'selected';
        }else{
            $sta = 'selected';
        }

        $html = "<select id='change_admins_approve$data->id' onchange='change_admins_approve($data->id)'>
        <option value='1' ".$sta1.">Approved</option>
        <option value='0' ".$sta.">Not Approved</option>
        </select>";





        return  $html;
    })
      ->editColumn('address', function(Admin $data) {
         return  $data->address;
     })

      ->editColumn('status', function(Admin $data) {
         $sta = '';
         $sta1 ='';
         if($data->status == 1){
            $sta1 = 'selected';
        }else{
            $sta = 'selected';
        }

        $html = "<select id='change_admins_status$data->id' onchange='change_admins_status($data->id)'>
        <option value='1' ".$sta1.">Active</option>
        <option value='0' ".$sta.">InActive</option>
        </select>";





        return  $html;
    })

      ->editColumn('created_at', function(Admin $data) {
         return  date('d M Y',strtotime($data->created_at));
     })

      ->addColumn('action', function(Admin $data) {
         $routeName = CustomHelper::getAdminRouteName();

         $BackUrl = $routeName.'/admins';
         $html = '';


         if(CustomHelper::isAllowedSection('admins' , Auth::guard('admin')->user()->role_id , $type='edit')){
            $html.='<a title="Edit" href="' . route($routeName.'.admins.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;';
        }   

        if(CustomHelper::isAllowedSection('admins' , Auth::guard('admin')->user()->role_id , $type='delete')){
            // $html.='<a title="Edit" href="' . route($routeName.'.admins.delete',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-trash">Delete</i></a>&nbsp;&nbsp;&nbsp;';
        }  



        return $html;
    })

      ->rawColumns(['name', 'status','is_approve', 'action','role_id'])
      ->toJson();
  }




  public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;
    if(!(CustomHelper::isAllowedSection('admins' , Auth::guard('admin')->user()->role_id , $type='add'))){
       return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admins.index'));

   }





   $admins = '';
   if(is_numeric($id) && $id > 0){

    if(!(CustomHelper::isAllowedSection('admins' , Auth::guard('admin')->user()->role_id , $type='edit'))){
        return redirect()->to(route($this->ADMIN_ROUTE_NAME.'.admins.index'));
    }

    $admins = Admin::find($id);
    if(empty($admins)){
        return redirect($this->ADMIN_ROUTE_NAME.'/admins');
    }
}

if($request->method() == 'POST' || $request->method() == 'post'){

    if(empty($back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/admins';
    }

    $name = (isset($request->name))?$request->name:'';


    $rules = [];
    if(is_numeric($id) && $id > 0){
         $rules['name'] = 'required';
       $rules['address'] = 'required';
       $rules['role_id'] = 'required';
       $rules['username'] = 'required';
       $rules['phone'] = 'required';
       $rules['email'] = 'required';
       
    }else{
      $rules['name'] = 'required';
        $rules['address'] = 'required';
        $rules['role_id'] = 'required';
        $rules['username'] = 'required|unique:admins,username';
        $rules['phone'] = 'required|unique:admins,phone';
        $rules['email'] = 'required|unique:admins,email';

   }



   $this->validate($request, $rules);

   $createdCat = $this->save($request, $id);

   if ($createdCat) {
    $alert_msg = 'Admin has been added successfully.';
    if(is_numeric($id) && $id > 0){
        $alert_msg = 'Admin has been updated successfully.';
    }
    return redirect(url($back_url))->with('alert-success', $alert_msg);
} else {
    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}
}


$page_heading = 'Add Admin';

if(isset($admins->name)){
    $admins_name = $admins->name;
    $page_heading = 'Update Admin - '.$admins_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['admins'] = $admins;
$data['states'] = State::get();
$cities = [];
if(is_numeric($id) && $id > 0){
    $cities = City::where('state_id',$admins->state_id)->get();
}


$data['roles'] = Roles::where('status',1)->get();
$data['cities']= $cities;

return view('admin.admins.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);

        //prd($request->toArray());

    if($id == 0){
        $data['added_by'] = Auth::guard('admin')->user()->id; 
    }

    if(!empty($request->password)){
        $data['password'] = bcrypt($request->password);
    }

    $oldImg = '';

    $admins = new Admin;

    if(is_numeric($id) && $id > 0){
        $exist = Admin::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $admins = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $admins->$key = $val;
    }

    $isSaved = $admins->save();

    if($isSaved){
        $this->saveImage($request, $admins, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $society, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'society/';
        $thumb_path = 'society/thumb/';
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
            $society->image = $image;
            $society->save();         
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
        $is_delete = Society::where('id', $id)->update(['is_delete',1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Society has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_admins_status(Request $request){
  $admin_id = isset($request->admin_id) ? $request->admin_id :'';
  $status = isset($request->status) ? $request->status :'';

  $admins = Admin::where('id',$admin_id)->first();
  if(!empty($admins)){

   Admin::where('id',$admin_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}



public function change_admins_role(Request $request){
  $admin_id = isset($request->admin_id) ? $request->admin_id :'';
  $role_id = isset($request->role_id) ? $request->role_id :'';

  $admins = Admin::where('id',$admin_id)->first();
  if(!empty($admins)){

   Admin::where('id',$admin_id)->update(['role_id'=>$role_id]);
   $response['success'] = true;
   $response['message'] = 'Role updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}





public function change_admins_approve(Request $request){
 $admin_id = isset($request->admin_id) ? $request->admin_id :'';
 $approve = isset($request->approve) ? $request->approve :'';

 $admins = Admin::where('id',$admin_id)->first();
 if(!empty($admins)){

   Admin::where('id',$admin_id)->update(['is_approve'=>$approve]);
   $message ='';
   if($approve == 1){
    $message = 'Approved';
}else{
    $message = 'Not Approved';

}

$response['success'] = true;
$response['message'] = $message;


return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}



public function documents(Request $request){

 $society_id = isset($request->id) ? $request->id :0;
 $method = $request->method();

 $data = [];


 if($method == 'post' || $method == 'POST'){

    $rules = [];
    $rules['file'] = 'required';

    $this->validate($request,$rules);

    if($request->hasFile('file')) {

        $image_result = $this->saveImageMultiple($request,$society_id);
        if($image_result){
            return back()->with('alert-success', 'Image uploaded successfully.');

        }
    }


}

$documents = SocietyDocument::where('society_id',$society_id)->get();

$data['documents'] = $documents;

return view('admin.society.documents',$data);

}
private function saveImageMultiple($request,$society_id){

    $files = $request->file('file');
    $path = 'societydocument/';
    $storage = Storage::disk('public');
            //prd($storage);
    $IMG_WIDTH = 768;
    $IMG_HEIGHT = 768;
    $THUMB_WIDTH = 336;
    $THUMB_HEIGHT = 336;
    $dbArray = [];

    if (!empty($files)) {

        foreach($files as $file){
            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['file'] = $image;
                $dbArray['society_id'] = $society_id;

                $success = SocietyDocument::create($dbArray);
            }
        }
        return true;
    }else{
        return false;
    }
}



}