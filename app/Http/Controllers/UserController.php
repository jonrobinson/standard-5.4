<?php namespace App\Http\Controllers;

// Includes
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\EmailCheckRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Log;

class UserController extends Controller
{
    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public function getCurrent()
    {
        $user = Auth::user();
        return $this->success($user, "Retrieved user");
    }

    public function updateInfo(UpdateUserRequest $request)
    {
        $user = Auth::user();
        $user->updateMe($request->all());
        return $this->success($user, "User updated.");
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return $this->error([], "User's current password does not match");
        }
        $user->updatePassword($request->input('new_password'));
        return $this->success();
    }

    public function checkEmail(EmailCheckRequest $request)
    {
        return $this->success();
    }
}