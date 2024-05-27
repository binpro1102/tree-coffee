<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth:api');
    // }

    public function brandList()
    {
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $brands = Brand::paginate($pageSize, ['*'], 'page', $pageNumber);
        try{
            return response()->json([
            'status' => 'Oke',
            'message' => 'Success',
            'data' => $brands
        ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'Failed',
                'message' => 'Không tìm thấy danh sách brands',
                'data' => $brands
            ],400);
        }
    }

    public function create(Request $request)
    {
        $brands = Brand::create($request->all());
        return response()->json([
            'status' => 'Oke',
            'message' => 'Create success',
            'data' => $brands
        ],200);
    }

    public function show(string $id)
    {
        $brands = Brand::find($id);
        if(!$brands){
            return response()->json([
            'status' => 'Failed',
            'message' => 'Không tìm thấy',
            'data' => [],
        ]);
            
        }
        return response()->json([
            'status' => 'Oke',
            'message' => 'Success',
            'data' => $brands
        ]);
    }

    public function update(Request $request, string $id)
    {
        $brands = Brand::find($id);
        if(!$brands){
            return response()->json([
                'status' => 'Failed',
                'message' => 'Không tìm thấy id để sửa',
                'data' => [],
                ]);
        }
        $brands->update($request->all());
        return response()->json([
            'status' => 'Oke',
            'message' => 'Update success',
            'data' => $brands
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brands = Brand::find($id);
        if(!$brands){
            return response()->json([
                'status' => 'Failed',
                'message' => 'Không tìm thấy id để xóa',
                'data' => []
            ]);
        }
        $brands -> delete();
        return response()->json([
            'status' => 'Oke',
            'message' => 'Delete success',
        ]);
    }
}
