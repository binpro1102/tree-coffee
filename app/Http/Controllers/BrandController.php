<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use Validator;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function brandList(Request $request)
    {
        $pageNumber = request()->input('page', $request->pageNumber);
        $pageSize = $request->pageSize;                              
        $data = Brand::where('is_delete', '=', 0)
        ->paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200, "Lấy danh sách thành công", $data); 
    }

    public function create(Request $request)
    {
        $rules = $this->validateBrand();// Kiểm tra validate
        $alert = $this->alert();// Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã thêm
            $data = Brand::create($request->all());
            return $this->responseCommon(200, "Thêm brand thành công", $data);
        }
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $data = Brand::find($id);
        if (!$data) {
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        return $this->responseCommon(200, "Tìm thấy ID thành công", $data);
    }

    public function update(Request $request)
    {
        // Tìm ID xem trong database có tồn tại không
        $id = $request->id;
        $data = Brand::find($id);
        if (!$data) {
            // Nếu không tồn tại thì trả lỗi
            return $this->responseCommon(400, "Không tìm thấy ID hoặc đã bị xóa", []);
        }
        $rules = $this->validateBrand();    // Kiểm tra validate
        $alert = $this->alert();            // Nếu có lỗi thì thông báo
        $validator = Validator::make($request->all(), $rules, $alert);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            // Nếu không thì trả lại dữ liệu cho người dùng đã sửa
            $data->update($request->all());
            return $this->responseCommon(200, "Cập nhật thành công", $data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $data = Brand::find($id);
        // Nếu không tìm thấy id hoặc tìm thấy id nhưng đã bị xóa
        if(!$data || $data['is_delete'] === 1){
            return $this->responseCommon(400,"Không tìm thấy ID hoặc đã bị xóa",[]);
        }
        //Nếu tìm thấy id chưa bị xóa thì thực hiện câu lệnh xóa mềm
        $data->update(['is_delete' => 1]);
        return $this->responseCommon(200, "Xóa thành công", []);
    }
}
