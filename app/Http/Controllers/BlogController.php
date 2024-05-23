<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\blog;

class BlogController extends Controller
{
    //lấy all  danh  sách
    public function index()
    {
        $blog = Blog::paginate(5);
        return response()->json($blog, 200);

    }


    // lấy 1

    public function get(Request $request)
    {
        $res = blog::where('blog_id', $request->blog_id)->first();
        return response()->json(['data' => $res, "success" => 'lấy thành công'], 200);
    }



    // tạo mới

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'thumbnail' => 'required',
            'highlight' => 'required',
        ]);

        $blog = blog::create($request->all());
        return response()->json(['data' => $blog, "message" => 'thêm  bài viết thành công'], 200);
    }


    public function update(Request $request, blog $blog)
    {

        $r->create($request->all());
        return response()->json(['data' => $r, "success" => true], 200);

    }




    public function delete($blog_id)
    {
        $blog = blog::find($blog_id);

        if (!$blog) {
            return response()->json(['message' => 'không có bài viết nào'], 404);
        }

        $blog->delete();
        return response()->json(['message' => 'Xóa bài thành công'], 200);

        // $customer->delete();
        // return response()->json(['data' => $customer, "success" => true], 200);

    }

}
