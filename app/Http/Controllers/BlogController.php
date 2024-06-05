<?php

namespace App\Http\Controllers;

use App\Models\blog;
use Illuminate\Http\Request;
use App\Services\PayUService;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogController extends Controller
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


            $blog = Blog::where('is_delete', false)->paginate($pageSize, ['*'], 'page', $pageNumber);

            return $this->responseCommon(200, "Lấy danh sách blog thành công", $blog);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "lấy danh sách blog không thành công", []);
        }

    }

    public function search(Request $request)
    {

        try {
            $searchTerm = $request->input('search');
            $pageNumber = request()->input('page');
            $pageSize = request()->input('pageSize');

            $blog = Blog::where('title', 'like', '%' . $searchTerm . '%')
                ->paginate($pageSize, ['*'], 'page', $pageNumber);

            return $this->responseCommon(200, "tìm kiếm thành công", $blog);


        } catch (\Exception $e) {
            return $this->responseCommon(500, "lỗi hệ thống. Xin vui lòng thử lại", []);
        }
    }

    // lấy 1

    public function get(Request $request)
    {
        try {
            $blog = Blog::where('blog_id', $request->input('blog_id'))->where('is_delete', false)->firstOrFail();


            return $this->responseCommon(200, "Lấy dữ liệu blog từ id thành công", $blog);

        } catch (ModelNotFoundException $e) {

            // Return a 404 response if the user is not found
            return $this->responseCommon(404, "không  tìm thấy id này trong cơ sở dữ liệu.", null);
        } catch (\Exception $e) {

            return $this->responseCommon(500, "Cập nhật role không thành công, vui lòng thử lại", []);
        }
    }

    // tạo mới

    public function store(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|min:5|max:255',
                'content' => 'required|string|min:10',
                'thumbnail' => 'required',
                'highlight' => 'required'
            ]);
            $blog = blog::create($request->all());


            return $this->responseCommon(201, "thêm  bài viết thành công", $blog);

        } catch (\Exception $e) {
            return $this->responseCommon(400, "thêm bài viết không thành công", null);
        }

    }

    // update

    public function update(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|min:5|max:255',
                'content' => 'required|string|min:10',
                'thumbnail' => 'required',
                'highlight' => 'required'
            ]);
            $blog = Blog::where('blog_id', $request->input('blog_id'))->where('is_delete', false)->firstOrFail();
            $blog->update($request->all());

            return $this->responseCommon(200, "Cập nhật bài viết thành công", $blog);
        } catch (\Exception $e) {

            return $this->responseCommon(400, "Cập nhật bài viết không thành công", null);
        }

    }


    public function delete(Request $request)
    {

        try {
            $blog = Blog::findOrFail($request->input('blog_id')); // lấy product_id truyền từ body để xóa

            if (!$blog->is_delete) { // kiểm tra is_delete trong bảng là F hay không, nếu là F gán là T
                $blog->is_delete = true;
                $blog->save();

                return $this->responseCommon(200, "id đã được xóa thành công.", []);
            } else {
                return $this->responseCommon(404, "id đã được xóa trước đó", null);
            }
        } catch (\Exception $e) {
            return $this->responseCommon(404, "không tìm thấy id trong cơ sở dữ liệu.", null);
        }
    }

}
