<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

//khi bao tu khoa use DB de tac dong vao csdl
use DB;
//bat cac form control
use Request;
use Hash;
use Session;
use Auth;
use Cookie;
class CategoryServiceController extends Controller{

	
	//===============================================================
	public function list_category_service(){
		//lay du lieu tu bang user, co su dung ham de phan trang
		
			if(Cookie::get('lang') != null) {
				$lang= Cookie::get('lang');
			}
			else {
				$lang='vn';
				Cookie::queue('lang', 'vn', 4500000);
			}
			if($lang=='en'){
				$data["list_category_service"] = DB::table("tbl_category_service")->where("c_lang","=","en")->get();
			}
			if($lang=='vn'){
				$data["list_category_service"] = DB::table("tbl_category_service")->where("c_lang","=","vn")->get();
			}
			$data["title"]="Danh mục dịch vụ phân tích";	
			return view("admin.category_service.category_service",$data);
		
	}
	//======================================================================
	public function add_category_service(){
		//lay du lieu tu bang user, co su dung ham de phan trang
		
			$data["title"]="Thêm mới danh mục dịch vụ phân tích";	
			return view("admin.category_service.add_category_service",$data);
		
	}

	//===================================================================
	public function do_add_category_service(){
		//lay du lieu tu bang user, co su dung ham de phan trang
			$uni = new UniController();
			$c_name = Request::get("c_name");
			$c_description = Request::get("c_description");
			$c_description = $uni->removeP($c_description);
			$c_lang = Request::get("c_lang");
			$c_enable= Request::get("c_enable");		
			$c_icon= Request::get("c_icon");
			$c_slug=$uni->Slug($c_name);
			
			$check =DB::table("tbl_category_service")->where("c_slug","=",$c_slug)->get();

			if($check ==null ){
				DB::insert("insert into tbl_category_service(c_name,c_description,c_icon,c_lang,c_enable,c_slug) values('$c_name','$c_description','$c_icon','$c_lang','$c_enable','$c_slug')");
					
				return redirect("/admin/category_service");
			}
			else {
				$data["c_name"]=$c_name;
				$data["c_description"]=$c_description;
				$data["c_icon"]=$c_icon;
				$data["c_lang"]=$c_lang;
				$data["c_enable"]=$c_enable;
				$data["c_error"]="(*) Danh mục đã tồn tại! Vui lòng kiểm tra lại các thông tin dưới đây.";
				$data["title"]="Thêm mới danh mục ";	
				return view("admin.category_service.add_category_service",$data);
			}
			
		
	}
	//======================================================================
	public function edit_category_service($id){
		//lay du lieu tu bang user, co su dung ham de phan trang
			$data['arr']=DB::table("tbl_category_service")->where("pk_category_service_id","=",$id)->first();
			if($data['arr'] != null) {
				$data["title"]="Chỉnh sửa danh mục";	
				return view("admin.category_service.edit_category_service",$data);
			}
			else {
				return redirect("/admin/category_service");
			}
	}

	//===================================================================
	public function do_edit_category_service($id){
		//lay du lieu tu bang user, co su dung ham de phan trang
			$uni = new UniController();
			$c_name = Request::get("c_name");
			$c_description = Request::get("c_description");
			$c_description = $uni->removeP($c_description);
			$c_lang = Request::get("c_lang");
			$c_enable= Request::get("c_enable");		
			$c_icon= Request::get("c_icon");
			$c_slug=$uni->Slug($c_name);
			
			$check =DB::table("tbl_category_service")->where("c_slug","=",$c_slug)->first();

			//print_r($che);
			if($check ==null ){
				DB::update("UPDATE tbl_category_service SET c_name='$c_name',c_description='$c_description',c_slug='$c_slug',c_lang='$c_lang',c_enable='$c_enable',c_icon='$c_icon' WHERE pk_category_service_id=$id");
					
				return redirect("/admin/category_service");
			}
			else {
				$check =DB::table("tbl_category_service")->where("c_slug","=",$c_slug)->where("pk_category_service_id","=",$id)->first();
				if($check != null){
					DB::update("UPDATE tbl_category_service SET c_name='$c_name',c_description='$c_description',c_slug='$c_slug',c_lang='$c_lang',c_enable='$c_enable',c_icon='$c_icon' WHERE pk_category_service_id=$id");
					
					return redirect("/admin/category_service");
				}
				else {
					$data['arr']=DB::table("tbl_category_service")->where("pk_category_service_id","=",$id)->first();
					$data["title"]="Chỉnh sửa danh mục";	
					$data["c_error"]="(*) Danh mục đã tồn tại! Vui lòng kiểm tra lại các thông tin dưới đây.";
					return view("admin.category_service.edit_category_service",$data);
				}
				
			}
			
		
	}
	//==================================================
	public function delete_category_service($id){
		DB::delete("delete from tbl_category_service where pk_category_service_id=$id");
		return redirect("/admin/category_service");
	}

	public function delete_all_category_service(){
		
		$id=Request::get("id");
		foreach ($id as $category_service_id) {
			DB::delete("delete from tbl_category_service where pk_category_service_id=$category_service_id");
		}
		return redirect("/admin/category_service");
	}
}