<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //lấy all  danh  sách
    public function list()
    {
        // Nếu is_delete = false {thì sẽ in ra tất cả}
        try {
            $pageNumber = request()->input('page', 1);
            $pageSize = 5;

            $product = ProductCategory::paginate($pageSize, ['*'], 'page', $pageNumber);

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
           $product->delete();
            // UPDATE ProductCategory
            // SET is_delete = 'true'
            // WHERE category_id = $product;


            return $this->responseCommon(200, "id đã được xóa thành công.", []);

        } catch (\Exception $e) {

            return $this->responseCommon(404, "không tìm thấy id trong cơ sở dữ liệu.", null);
        }
    }
}
