<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function orderList(Request $request)
    {
        $query = $this->responseQueryOrder();
        $pageNumber = request()->input('pageNumber', $request->pageNumber);
        $pageSize = $request->pageSize;
        $user = $this->responseRole();
        if ($user['role'] === 'MEMBER') {
            $data = $query->where('orders.user_id', '=', $user['id'])
                ->paginate($pageSize, ['*'], 'pageNumber', $pageNumber);
            return $this->responseCommon(200, "Lấy danh sách thành công", $data);
        } else {
            $data = $query->paginate($pageSize, ['*'], 'page', $pageNumber);
            return $this->responseCommon(200, "Lấy danh sách thành công", $data);
        }
    }

    public function create(Request $request)
    {
        $status = $request->status;
        if (is_null($status)) {
            $status = "pending";
        }
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu điền đầy đủ thì trả lại dữ liệu cho người dùng đã thêm
            $data = Order::create([
                'user_id' => $request->user_id,
                'payment_method_id' => $request->payment_method_id,
                'restaurant_id' => $request->restaurant_id,
                'order_date' => $request->order_date,
                'total_price' => $request->total_price,
                'shipping_address' => $request->shipping_address,
                'note' => $request->note,
                'total_discount' => $request->total_discount,
                'sub_total' => $request->sub_total,
                'status' => $status
            ]);
            return $this->responseCommon(200, "Thêm đơn order thành công", $data);
        }
    }

    public function show(Request $request)
    {
        $id = $request->order_id;
        $user = $this->responseRole();

        //Câu lệnh truy vấn order
        $query = $this->responseQueryOrder();
        // Viết câu lệnh truy vấn lấy order_detail
        $order_detail = Order_detail::select('products.name as product_name','order_details.price','quantity','discount','order_details.created_at','order_details.updated_at')
            ->join('products', 'products.product_id', '=', 'order_details.product_id')
            ->where('order_details.order_id','=',$id)
            ->get();
        // End câu lệnh truy vấn order_detail
        //End câu lệnh truy vấn    
        
        if ($user['role'] === 'MEMBER') {
            $data = $query->where('orders.user_id', '=', $user['id'])->find($id);
            if (!$data) {
                return $this->responseCommon(400, "Đơn order không tồn tại hoặc đã bị xóa", []);
            }
            $data["order_details"] = $order_detail;
            return $this->responseCommon(200, "Lấy đơn order thành công", $data);
        } else {
            $data = $query->find($id);
            if(!$data){
                return $this->responseCommon(400, "Đơn order không tồn tại hoặc đã bị xóa",[]);
            }
            $data["order_details"] = $order_detail;
            return $this->responseCommon(200, "Lấy đơn order thành công", $data);
        }
    }

    public function update(Request $request)
    {
        $id = $request->order_id;
        $user = $this->responseRole(); // Lấy ra thông tin user
        $query = $this->responseQueryOrder(); // Lấy ra câu lệnh truy vấn
        $rules = $this->validateOrder();
        $alert = $this->alert();
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            if($user['role'] === 'MEMBER'){
                $data = $query->where('orders.user_id', '=', $user['id'])->find($id);
                // Nếu ID không tồn tại thì trả về lỗi
                if(!$data){
                    return $this->responseCommon(400, "Đơn order này không tồn tại hoặc đã bị xóa",[]);
                }
                // Nếu ID tồn tại thì tiếp tục kiểm tra status
                if($data['status'] === 'pending'){
                    $data->update($request->all());
                    return $this->responseCommon(200, "Cập nhật thành công", $data);
                }
                if($data['status'] === 'processing'){
                    return $this->responseCommon(400, "Đơn của bạn hiện tại đang được xử lý,vui lòng liên lạc tới cửa hàng nếu muốn thay đổi đơn hàng",[]);
                }else{
                    return $this->responseCommon(400, "Đơn của bạn đã được xử lý,không thể sửa",[]);
                }
            }else{
                $data = $query->find($id);
                // Nếu ID không tồn tại thì trả về lỗi
                if(!$data){
                    return $this->responseCommon(400, "Đơn order này không tồn tại hoặc đã bị xóa",[]);
                }
                $data->update($request->all());
                return $this->responseCommon(200, "Cập nhật thành công", $data);
            }
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->order_id;
        $user = $this->responseRole(); // Lấy ra thông tin user
        $query = $this->responseQueryOrder(); // Lấy ra câu lệnh truy vấn

        // Viết câu lệnh truy vấn order_detail
        $order_detail = Order_detail::where('order_details.order_id','=',$id);

        if($user['role'] === 'MEMBER'){
            $data = $query->where('orders.user_id', '=', $user['id'])->find($id);
            // Nếu ID không tồn tại thì trả về lỗi
            if(!$data){
                return $this->responseCommon(400, "Đơn order này không tồn tại hoặc đã bị xóa",[]);
            }
            // Nếu ID tồn tại thì tiếp tục kiểm tra status
            if($data['status'] === 'pending'){
                $data->update(['is_delete' => 1]);
                // Nếu hủy(xóa) order thì phải xóa luôn order_detail của order đó
                $order_detail->update(['is_delete' => 1]);
                return $this->responseCommon(200, "Đơn của bạn đã được hủy", []);
            }
            if($data['status'] === 'processing'){
                return $this->responseCommon(400, "Đơn của bạn hiện tại đang được xử lý,vui lòng liên lạc tới cửa hàng nếu muốn thay đổi đơn hàng",[]);
            }else{
                return $this->responseCommon(400, "Đơn của bạn đã được xử lý,không thể xóa",[]);
            }
        }else{
            $data = $query->find($id);
            // Nếu ID không tồn tại thì trả về lỗi
            if(!$data){
                return $this->responseCommon(400, "Đơn order này không tồn tại hoặc đã bị xóa",[]);
            }
            $data->update(['is_delete' => 1]);
            // Nếu hủy(xóa) order thì phải xóa luôn order_detail của order đó
            $order_detail->update(['is_delete' => 1]);
            return $this->responseCommon(200, "Xóa thành công",[]);
        }
    }
}
