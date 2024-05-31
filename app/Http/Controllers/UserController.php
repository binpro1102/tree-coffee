<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function updateRole(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:users,id',
                'role' => 'required',
            ]);

            // tìm id
            $user = User::findOrFail($request->input('id'));

            // Update role
            $user->update(['role' => $request->input('role')]);

            return $this->responseCommon(200, "Cập nhật role thành công.", $user);
        } catch (ModelNotFoundException $e) {

            // Return a 404 response if the user is not found
            return $this->responseCommon(404, "không  tìm thấy id người dùng.", null);

        } catch (\Exception $e) {
            return $this->responseCommon(500, "Cập nhật role không thành công, vui lòng thử lại", []);
        }
    }



    public function update(Request $request)
    {
        try {


            // Lấy dữ liệu từ request, chỉ cập nhật trường được yêu cầu, các trường khác giữ nguyên
            $data = $request->only(['id', 'name', 'email', 'password', 'address', 'phone_number',]);

            $request->validate([
                'id' => 'required|integer',
                'name' => 'sometimes|string|between:2,100',
                'email' => 'sometimes|string|email|max:100|unique:users,email, {{$data["id"]}}',
                'password' => 'sometimes|string|confirmed|min:6',
                'address' => 'sometimes|required',
                'phone_number' => 'sometimes|required|numeric',
            ], $request->all());

            // Cập nhật user
            $user = User::findOrFail($data['id']);
            $user->update($data);


            return $this->responseCommon(200, "Cập nhật data thành công.", $user);
        } catch (ModelNotFoundException $e) {

            return $this->responseCommon(404, "không  tìm thấy id người dùng", null);

        } catch (\Exception $e) {
            return $this->responseCommon(500, "Cập nhật không thành công, vui lòng thử lại", []);
        }
    }


}
