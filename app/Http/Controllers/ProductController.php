<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //lấy all  danh  sách
    public function list()
    {
        try {
            $pageNumber = request()->input('page', 1);
            $pageSize = 5;

            $product = Product::paginate($pageSize, ['*'], 'page', $pageNumber);

            return $this->responseCommon(200, "Lấy danh sách sản phẩm thành công", $product);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "lấy danh sách sản phẩm không thành công", []);
        }


    }

    // tìm kiếm sản phẩm theo name
    public function search(Request $request)
    {

        try {
            $searchTerm = $request->input('search');
            $pageNumber = request()->input('page', 1);
            $pageSize = 3; // bản ghi 1 trang

            $product = Product::where('name', 'like', '%' . $searchTerm . '%')
                ->paginate($pageSize, ['*'], 'page', $pageNumber);

            return $this->responseCommon(200, "tìm kiếm thành công", $product);


        } catch (\Exception $e) {
            return $this->responseCommon(500, "lỗi hệ thống. Xin vui lòng thử lại", []);
        }
    }

    // lấy 1

    public function get(Request $request)
    {
        try {
            $product = Product::where('product_id', $request->input('product_id'))->firstOrFail();

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
                'category_id' => 'required',
                'name' => 'required|string|min:5|max:255',
                'price' => 'required|string',
                'img' => 'required',
                'code' => 'required',
                'unit_price' => 'required'

            ]);
            $product = Product::create($request->all());

            return $this->responseCommon(201, "thêm  sản phẩm thành công", $product);

        } catch (\Exception $e) {
            return $this->responseCommon(400, "thêm sản phẩm không thành công   " . $e->getMessage(), null);
        }
    }

    // update

    public function update(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'name' => 'required|string|min:5|max:255',
                'price' => 'required|string',
                'img' => 'required',
                'code' => 'required',
                'unit_price' => 'required'

            ]);
            $product = Product::findOrFail($request->input('product_id'));
            $product->update($request->all());

            return $this->responseCommon(200, "Cập nhật sản phẩm thành công", $product);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "Cập nhật sản phẩm không thành công", null);
        }

    }


    public function delete(Request $request)
    {

        try {
            $product = Product::findOrFail($request->input('product_id')); // lấy product_id truyền từ body để xóa
            $product->delete();


            return $this->responseCommon(200, "sản phẩm đã được xóa thành công.", []);

        } catch (\Exception $e) {

            return $this->responseCommon(404, "không tìm thấy id trong cơ sở dữ liệu.", null);
        }
    }
}
