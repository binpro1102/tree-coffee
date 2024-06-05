<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Order_detail;
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
        $name = $user['name'];
        if ($user['role'] == 'MEMBER') {
            $data = Order::all(); // Lấy ra danh sách order
            // Duyệt vòng lặp, để lấy ra đơn order của user
            foreach ($data as $dataList) {
                if ($user['id'] == $dataList['user_id'] && $dataList['is_delete'] === 0) {
                    array_push($array, $dataList);
                }
            }
            // Nếu user đó không có đơn order thì trả về là không có
            if (!$array) {
                return $this->responseCommon(200, "$name chưa có đơn đặt hàng nào", []);
            }
            // Nếu user có đơn order, thì trả lại tất cả đơn order của user
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
        $array = [];
        // Nếu id không tồn tại, và is_delete = 1 thì trả về lỗi
        if (!$data || $data['is_delete'] === 1) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        $user = auth()->user(); // Lấy ra thông tin user khi đăng nhập
        if ($user['role'] == 'MEMBER') {
            if ($user['id'] == $data['user_id']) {
                array_push($array,$data);
                $order_detail = Order_detail::all();
                foreach ($order_detail as $list) {
                    if ($user['id'] == $list['order_id']) {
                        array_push($array, $list);
                    }
                }
                return $this->responseCommon(200, "Tìm thấy thành công", $array);
            } else {
                return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
            }
        } else {
            array_push($array,$data);
            $order_detail = Order_detail::all();
            foreach ($order_detail as $list) {
                if ($data['order_id'] == $list['order_id']) {
                    array_push($array, $list);
                }
            }
            return $this->responseCommon(200, "Tìm thấy thành công", $array);
        }
    }

    public function update(Request $request)
    {
        $id = $request->order_id;
        $data = Order::find($id);
        // Nếu id không tồn tại, và is_delete = 1 thì trả về lỗi
        if (!$data || $data['is_delete'] === 1) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $user = auth()->user(); // Lấy ra thông tin user khi đăng nhập
            if ($user['role'] == 'MEMBER') {
                if ($user['id'] === $data['user_id']) {
                    $data->update($request->all());
                    return $this->responseCommon(200, "Cập nhật thành công", $data);
                } else {
                    return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
                }
            } else {
                $data->update($request->all());
                return $this->responseCommon(200, "Cập nhật thành công", $data);
            }
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
        $user = auth()->user(); // Lấy ra thông tin user khi đăng nhập
        if ($user['role'] == 'MEMBER') {
            if ($user['id'] == $data['user_id']) {
                //Nếu tìm thấy id chưa bị xóa thì thực hiện câu lệnh xóa mềm
                $data->update(['is_delete' => 1]);
                // Nếu xóa đơn order thì phải xóa luôn order_detail của order đó
                $order_detail = Order_detail::all();
                foreach ($order_detail as $list) {
                    if ($id == $list['order_id']) {
                        $list->update(['is_delete' => 1]);
                    }
                }
                return $this->responseCommon(200, "Xóa thành công", []);
            } else {
                return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
            }
        } else {
            $data->update(['is_delete' => 1]);
            // Nếu xóa đơn order thì phải xóa luôn order_detail của order đó
            $order_detail = Order_detail::all();
            foreach ($order_detail as $list) {
                if ($id == $list['order_id']) {
                    $list->update(['is_delete' => 1]);
                }
            }
            return $this->responseCommon(200, "Xóa thành công", []);
        }
    }
}
