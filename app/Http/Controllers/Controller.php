<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseCommon($httpCode,$message,$data){
        $pattern = '/^2\d{2}$/';
        $status = false;
        if(preg_match($pattern,$httpCode) === 1){
            $status = true;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ],$httpCode);
    }
    
    //Validate brands
    public function validateBrand()
    {
        return [
            'name' => 'required|min:5|max:30',
            'image' => 'required',
        ];
    }

    //Validate restaurant
    public function validateRestaurant()
    {
        return [
            'name' => 'required|min:5|max:30',
            'address' => 'required|min:5|max:30',
            'phone_number' => 'required|max:10|regex:/(0)[0-9]{9}/',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
    }
    //Validate restaurant image
    public function validateRestaurantImage()
    {
        return [
            'restaurant_id' => 'required',
            'img_path' => 'required',
            'highlight' => '',
        ];
    }

    public function validateOrder()
    {
        return [
        'user_id' => 'required',
        'order_date' => 'required',
        'total_price' => 'required',
        'shipping_address' => 'required',
        'note' => 'required',
        'total_discount' => 'required',
        'sub_total' => 'required',
        'status'=> 'required'
        ];
    }

    public function validatePayment()
    {
        return [
        'name' => 'required|min:5|max:30'
        ];
    }

    public function validateOrderDetail(){
        return [
            'price' => 'required',
            'quantity' => 'required',
            'discount' => 'required'
        ];
    }
    public function alert()
    {
        return [
            'required' => 'Không được để trống thông tin :attribute.',
            'name.min' => 'Bạn cần nhập tên ít nhất 5 kí tự',
            'name.max' => 'Bạn chỉ được nhập tên nhiều nhất 30 kí tự',
            'address.min' => 'Bạn cần nhập địa chỉ ít nhất 5 kí tự',
            'address.max' => 'Địa chỉ quá dài,vui lòng nhập lại',
            'phone_number.regex' => 'Bạn nhập sai số, xin vui lòng nhập lại',
            'phone_number.max' => 'Số điện thoại không được vượt quá 10 số',

        ];
    }
}
