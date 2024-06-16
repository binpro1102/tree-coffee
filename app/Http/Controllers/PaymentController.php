<?php

namespace App\Http\Controllers;

use App\Models\Payment_method;
use Validator;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function paymentList(Request $request)
    {
        $pageNumber = request()->input('page', $request->pageNumber);
        $pageSize = $request->pageSize;                              
        $data = Payment_method::where('is_delete', '=', 0)
        ->paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200, "Lấy danh sách thành công", $data);
    }

    public function create(Request $request)
    {
        $name = $request->name;
        $status = $request->status;
        if (!is_null($status)) {
            $status = 0;
        } else {
            $status = 1;
        }
        $rules = $this->validatePayment();// Kiểm tra validate
        $alert = $this->alert();// Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $data = Payment_method::create([
                'name' => $name,
                'status' => $status
            ]);
            return $this->responseCommon(200, "Thêm payment_method thành công", $data);
        }
    }

    public function show(Request $request)
    {
        $id = $request->payment_method_id;
        $data = Payment_method::find($id);
        // Nếu không tìm thấy id hoặc tìm thấy id nhưng đã bị xóa
        if (!$data || $data['is_delete'] === 1) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        return $this->responseCommon(200, "Tìm thấy ID thành công", $data);
    }

    public function update(Request $request)
    {
        $id = $request->payment_method_id;
        $data = Payment_method::find($id);
        // Nếu không tìm thấy id hoặc tìm thấy id nhưng đã bị xóa
        if (!$data || $data['is_delete'] === 1) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        $rules = $this->validatePayment();// Kiểm tra validate
        $alert = $this->alert();// Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $name = $request->name;
            $status = $request->status;
            if (!is_null($status)) {
                $status = 0;
            } else {
                $status = 1;
            }
            $data->update([
                'name' => $name,
                'status' => $status
            ]);
            return $this->responseCommon(200, "Cập nhật thành công", $data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->payment_method_id;
        $data = Payment_method::find($id);
        // Nếu không tìm thấy id hoặc tìm thấy id nhưng đã bị xóa
        if(!$data || $data['is_delete'] === 1){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        //Nếu tìm thấy id chưa bị xóa thì thực hiện câu lệnh xóa mềm
        $data->update(['is_delete' => 1]);
        return $this->responseCommon(200, "Xóa thành công", []);
    }

}
