<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //lấy all  danh  sách
    public function list(Request $request)
    {

        try {
            $pageNumber = request()->input('page');
            $pageSize = request()->input('pageSize');

            if ($pageSize === null) {
                return $this->responseCommon(400, "Vui lòng truyền pageSize trong request.", []);
            }


            $product = ProductCategory::where('is_delete', false)->paginate($pageSize, ['*'], 'page', $pageNumber); // Nếu is_delete = false {thì sẽ in ra tất cả}, true thì sẽ ẩn đi

            return $this->responseCommon(200, "Lấy danh sách thành công", $product);
        } catch (\Exception $e) {
            dd($e);
            return $this->responseCommon(400, "lấy danh sách không thành công", []);
        }
    }



    // lấy 1

    public function get(Request $request)
    {
        try {
            $product = ProductCategory::where('category_id', $request->input('category_id'))->firstOrFail();

            return $this->responseCommon(200, "Lấy dữ liệu từ id thành công", $product);

        } catch (ModelNotFoundException $e) {

            // Return a 404 response if the user is not found
            return $this->responseCommon(404, "không  tìm thấy id này trong cơ sở dữ liệu.", null);
        } catch (\Exception $e) {

            dd($e);
            return $this->responseCommon(500, "Lấy không thành công, vui lòng thử lại", []);
        }
    }

    // tạo mới

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'img' => 'required'

            ]);

            $product = ProductCategory::create($request->all());
            return $this->responseCommon(201, "thêm thành công", $product);

        } catch (\Exception $e) {
            return $this->responseCommon(400, "thêm không thành công " . $e->getMessage(), null);
        }
    }

    // update

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'img' => 'required'

            ]);
            $product = ProductCategory::findOrFail($request->input('category_id')); // $request->input('category_id') lấy id truyền từ body
            $product->update($request->all());

            return $this->responseCommon(200, "Cập nhật thành công", $product);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "Cập nhật không thành công", null);
        }

    }


    public function delete(Request $request)
    {

        try {
            $product = ProductCategory::findOrFail($request->input('category_id')); // lấy product_id truyền từ body để xóa
            $product->is_delete = true;
            $product->save();


            return $this->responseCommon(200, "id đã được xóa thành công.", []);

        } catch (\Exception $e) {

            return $this->responseCommon(404, "không tìm thấy id trong cơ sở dữ liệu.", null);
        }
    }
}
