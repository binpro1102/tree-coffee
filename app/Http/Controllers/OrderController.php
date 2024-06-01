<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function orderList(Request $request)
    {
        $user = auth()->user(); // Lấy ra thông tin user khi đăng nhập
        $array = [];
        if ($user['role'] == 'MEMBER') {
            $data = Order::all(); // Lấy ra danh sách order
            // Duyệt vòng lặp, để lấy ra đơn order của user
            foreach ($data as $dataList) {
                if ($user['id'] == $dataList['user_id']) {
                    array_push($array, $dataList);
                }
            }
            // Nếu user đó không có đơn order thì trả về là không có
            if (!$array) {
                return $this->responseCommon(200, "Bạn chưa có đơn đặt hàng nào", []);
            }
            // Nếu user có đơn order, thì trả lại tất cả đơn order của user
            $name = $user['name'];
            return $this->responseCommon(200, "Danh sách đặt hàng của: $name", $array);
        } else {
            $pageNumber = request()->input('page', $request->pageNumber);
            $pageSize = $request->pageSize;
            $data = Order::where('is_delete', '=', 0)
                ->paginate($pageSize, ['*'], 'page', $pageNumber);
            return $this->responseCommon(200, "Lấy danh sách thành công", $data);
        }
    }

    public function create(Request $request)
    {
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $data = Order::create($request->all());
            return $this->responseCommon(200, "Thêm đơn order thành công", $data);
        }
    }

    public function show(Request $request)
    {
        $id = $request->order_id;
        $data = Order::find($id);
        if (!$data) {
            return $this->responseCommon(400, "ID không tìm thấy hoặc đã bị xóa", []);
        }
        return $this->responseCommon(200, "Tìm thấy thành công", $data);
    }

    public function update(Request $request)
    {
        $id = $request->order_id;
        $data = Order::find($id);
        if (!$data) {
            // Nếu không tồn tại thì trả lỗi
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã sửa
            $data->update($request->all());
            return $this->responseCommon(200, "Cập nhật thành công", $data);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->order_id;
        $data = Order::find($id);
        // Nếu không tìm thấy id hoặc tìm thấy id nhưng đã bị xóa
        if (!$data || $data['is_delete'] === 1) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        //Nếu tìm thấy id chưa bị xóa thì thực hiện câu lệnh xóa mềm
        $data->update(['is_delete' => 1]);
        return $this->responseCommon(200, "Xóa thành công", []);
    }
}
