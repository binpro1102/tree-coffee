<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Validator;

class RestaurantController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function restaurantList()
    {
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $data = Restaurant::paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200,"Lấy danh sách thành công",$data);
    }

    public function create(Request $request)
    {
        $rules = $this->validateRestaurant();// Kiểm tra validate
        $alert = $this->alert();// Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $data = Restaurant::create($request->all());
            return $this->responseCommon(200,"Thêm restaurant thành công",$data);
        }
    }

    public function show(string $id)
    {
        $data = Restaurant::find($id);
        if(!$data){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",$data);
        }
        return $this->responseCommon(200,"Tìm thấy ID thành công",$data);
    }

    public function update(Request $request, string $id)
    {
        $data = Restaurant::find($id);
        if (!$data) {
            // Nếu không tồn tại thì trả lỗi
            return $this->responseCommonFailed(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $rules = $this->validateRestaurant();    // Kiểm tra validate
        $alert = $this->alert();            // Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã sửa
            $data->update($request->all());
            return $this->responseCommon(200,"Cập nhật thành công",$data);
        }
    }

    public function destroy(string $id)
    {
        $data = Restaurant::find($id);
        if(!$data){
            return $this->responseCommonFailed(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $data->delete();
        return $this->responseCommon(200,"Success","Delete success");
    }
}
