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
    public function list(Request $request)
    {
        try {
            $pageNumber = request()->input('pageNumber');
            $pageSize = request()->input('pageSize');

            if ($pageSize === null) {
                return $this->responseCommon(400, "Vui lòng truyền pageSize trong request.", []);
            }

            $product = Product::where('is_delete', false)->paginate($pageSize, ['*'], 'page', $pageNumber);

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
            $pageNumber = request()->input('pageNumber');
            $pageSize = request()->input('pageSize');

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
            $product = Product::where('product_id', $request->input('product_id'))->where('is_delete', false)->firstOrFail();

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
            $product = Product::where('product_id', $request->input('product_id'))->where('is_delete', false)->firstOrFail();
            $product->update($request->all());

            return $this->responseCommon(200, "Cập nhật sản phẩm thành công", $product);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "Cập nhật sản phẩm không thành công, không tìm thấy id này", null);
        }

    }


    public function delete(Request $request)
    {

        try {
            $product = Product::findOrFail($request->input('product_id')); // lấy product_id truyền từ body để xóa

            if (!$product->is_delete) { // kiểm tra is_delete trong bảng là F hay không, nếu là F gán là T
                $product->is_delete = true;
                $product->save();

                return $this->responseCommon(200, "id đã được xóa thành công.", []);
            } else {
                return $this->responseCommon(404, "id đã được xóa trước đó", null);
            }
        } catch (\Exception $e) {
            return $this->responseCommon(404, "không tìm thấy id trong cơ sở dữ liệu.", null);
        }
    }
}
