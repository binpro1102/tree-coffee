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
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function paymentList()
    {
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $data = Payment_method::paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200,"Lấy danh sách thành công",$data);
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
        $data = Payment_method::create([
            'name' => $name,
            'status' => $status
        ]);
        return $this->responseCommon(200,"Thêm payment_method thành công",$data);
        
    }

    public function show(string $id)
    {
        $data = Payment_method::find($id);
        if(!$data){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        return $this->responseCommon(200,"Tìm thấy ID thành công",$data);
    }

    public function update(Request $request, string $id)
    {
        $data = Payment_method::find($id);
        if (!$data) {
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
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
        return $this->responseCommon(200,"Cập nhật thành công",$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Payment_method::find($id);
        if(!$data){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        $data->delete();
        return $this->responseCommon(200,"Xóa thành công",[]);
    }

}
