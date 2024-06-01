<?php

namespace App\Http\Controllers;

use App\Models\Restaurant_image;
use Illuminate\Http\Request;
use Validator;
class RestaurantImageController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function RestaurantImageList(){
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $data = Restaurant_image::paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200,"Lấy danh sách thành công",$data);
    }

    public function create(Request $request)
    {
        $rules = $this->validateRestaurantImage();// Kiểm tra validate
        $alert = $this->alert();// Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $data = Restaurant_image::create($request->all());
            return $this->responseCommon(200,"Thêm image cho restaurant thành công",$data);
        }   
    }

    public function show(Request $request)
    {
        $id=$request->restaurant_img_id;
        $data = Restaurant_image::find($id);
        if(!$data){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        return $this->responseCommon(200,"Tìm thấy ID thành công",$data);
    }


    public function update(Request $request)
    {
        $id=$request->restaurant_img_id;
        $data = Restaurant_image::find($id);
        if(!$data){
            // Nếu không tồn tại thì trả lỗi
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $rules = $this->validateRestaurantImage();    // Kiểm tra validate
        $alert = $this->alert();                      // Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã sửa
            $data->update($request->all());
            return $this->responseCommon(200,"Cập nhật thành công",$data);
        }    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id=$request->restaurant_img_id;
        $data = Restaurant_image::find($id);
        if(!$data){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $data->delete();
        return $this->responseCommon(200,"Xóa thành công",[]);
    }
}
