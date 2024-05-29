<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function updateRole(Request $request, $id)
    {
        try {
            $request->validate([
                'role' => 'required',
            ]);

            // tìm id
            $user = User::findOrFail($id);

            // Update role
            $user->update(['role' => $request->input('role')]);

            return response()->json([
                'status' => 'OK',
                'data' => $user,
                "message" => 'Cập nhật role thành công'
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Return a 404 response if the user is not found
            return response()->json([
                'status' => 'Failed',
                'data' => null,
                "message" => 'không  tìm thấy id người dùng'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'data' => [],
                "message" => 'Cập nhật role không thành công, vui lòng thử lại'
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Lấy dữ liệu từ request, chỉ cập nhật trường được yêu cầu
            $data = $request->only(['name', 'email', 'password', 'address', 'phone_number',]);

            $request->validate([
                'name' => 'sometimes|string|between:2,100',
                'email' => 'sometimes|string|email|max:100|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|confirmed|min:6',
                'address' => 'sometimes|required',
                'phone_number' => 'sometimes|regex:/^(\+84|0)\d{9}$/',
            ], $request->all());

            // Cập nhật user
            $user->update($data);

            return response()->json([
                'status' => 'OK',
                'data' => $user,
                "message" => 'Cập nhật thành công'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'Failed',
                'data' => null,
                "message" => 'không  tìm thấy id người dùng'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'Failed',
                'data' => [],
                "message" => 'Cập nhật không thành công, vui lòng thử lại'
            ], 500);
        }
    }


}
