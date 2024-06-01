<?php

namespace App\Http\Controllers;

use App\Models\Order_detail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function orderDetailList()
    {
        $pageNumber = request()->input('page', 1); // Lấy trang hiện tại từ URL
        $pageSize = 5;                              // Số bản ghi trên mỗi trang
        $data = Order_detail::paginate($pageSize, ['*'], 'page', $pageNumber);
        return $this->responseCommon(200,"Lấy danh sách thành công",$data);
    }
}
