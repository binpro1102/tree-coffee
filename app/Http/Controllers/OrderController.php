<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth:api');
    // }

    public function orderList()
    {
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $data = Order::paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200,"Lấy danh sách thành công",$data);
    }

    public function create(Request $request){
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $data = Order::create($request->all());
            return $this->responseCommon(200,"Thêm đơn order thành công",$data);
        }
    }

    public function show(string $id){
        $data = Order::find($id);
        if(!$data){
            return $this->responseCommon(400,"ID không tìm thấy hoặc đã bị xóa",[]);
        }
        return $this->responseCommon(200,"Tìm thấy thành công",$data);
    }

    public function update(Request $request,string $id){
        $data = Order::find($id);
        if(!$data){
            // Nếu không tồn tại thì trả lỗi
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $rules = $this->validateOrder();    
        $alert = $this->alert();                      
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã sửa
            $data->update($request->all());
            return $this->responseCommon(200,"Cập nhật thành công",$data);
        }
    }

    public function destroy(string $id){
        $data = Order::find($id);
        if(!$data){
            return $this->responseCommonFailed(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $data->delete();
        return $this->responseCommon(200,"Xóa thành công",[]);
    }
}
