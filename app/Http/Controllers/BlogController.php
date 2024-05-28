<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\blog;
use App\Services\PayUService;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    //lấy all  danh  sách
    public function list()
    {


        try {
            $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
            $pageSize = 3;                              // Số bản ghi trên mỗi trang

            $blog = Blog::paginate($pageSize, ['*'], 'page', $pageNumber);

            return response()->json([
                'status' => 'OK',
                'message' => 'lấy danh sách blog thành công',
                'data' => $blog
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => ' lấy danh sách blog không thành công: ' . $e->getMessage(),
                'data' => []
            ], 400);
        }


    }

    // lấy 1

    public function get(Request $request)
    {
        try {
            $blog = Blog::where('blog_id', $request->blog_id)->firstOrFail();

            return response()->json([
                'status' => 'OK',
                'message' => 'Lấy dữ liệu blog từ id thành công',
                'data' => $blog
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Không tìm thấy blog với id này: ' . $e->getMessage(),
                'data' => null
            ], 404);
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
            return response()->json([
                'status' => 'OK',
                'data' => $blog,
                "message" => 'thêm  bài viết thành công'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'thêm bài viết không thành công: ' . $e->getMessage(),
                'data' => null
            ], 411);
        }

    }

    // update

    public function update(Request $request, $blog_id)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|min:|max:255',
                'content' => 'required|string|min:10',
                'thumbnail' => 'required',
                'highlight' => 'required'
            ]);
            $blog = Blog::findOrFail($blog_id);
            $blog->update($request->all());

            return response()->json(['status' => 'OK', 'data' => $blog, "message" => 'Cập nhật bài viết thành công'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'cập nhật bài viết không thành công: ' . $e->getMessage(),
                'data' => null
            ], 411);
        }

    }




    public function delete($blog_id)
    {

        try {
            $blog = blog::findOrFail($blog_id);
            $blog->delete();

            return response()->json([
                'status' => 'OK',
                'message' => 'Bài viết đã được xóa thành công.',
                'data' => []
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'không tìm thấy id trong cơ sở dữ liệu: ' . $e->getMessage(),
                'data' => null
            ], 411);
        }

    }

}
