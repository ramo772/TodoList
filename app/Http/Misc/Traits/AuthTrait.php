<?php

namespace App\Http\Misc\Traits;

use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\Errors;
use App\Http\Misc\Helpers\FileHandler;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait AuthTrait
{
    public function register($user, $request)
    {
        $user->first_name = mb_convert_encoding($request->first_name, 'UTF-8', 'UTF-8');
        $request->first_name;
        $user->last_name =  mb_convert_encoding($request->last_name, 'UTF-8', 'UTF-8');
        $username = "User" . rand(1, 200000);
        $i = 0;
        while (User::whereuser_name($username)->exists()) {
            $i++;
            $username = "User" . $i;
        }
        $user->user_name = $username;
        $user->phone_number = $request->phone_number;
        $user->birth_date = $request->birth_date;
        if ($request->gender == 'male') {
            $user->gender = $request->gender;
            $user->image = asset('images/BtFUrp6CEAEmsml.jpg');
        }
        if ($request->gender == 'female') {
            $user->gender = $request->gender;
            $user->image = asset('images/female.jpg');
        }
        $user->role = User::USER;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
    }

    public function register_vendor($user,  $service, $request)
    {
        $user->first_name = mb_convert_encoding($request->first_name, 'UTF-8', 'UTF-8');
        $request->first_name;
        $user->last_name =  mb_convert_encoding($request->last_name, 'UTF-8', 'UTF-8');
        $username = "Vendor" . rand(1, 200000);
        $i = 0;
        while (User::whereuser_name($username)->exists()) {
            $i++;
            $username = "Vendor" . $i;
        }
        $user->user_name = $username;
        $user->vendor_name = $request->vendor_name;
        $user->phone_number = $request->phone_number;
        $user->role = User::VENDOR;
        $user->category_id = $request->category_id;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $service->status = Service::DRAFT;
        $service->user_id = $user->id;
        $service->category_id = $user->category_id;
        $service->type = Service::PRIMARY;
        $service->save();
    }
    public function storeAdmin($admin, $request)
    {
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $firstName = $request->first_name;
        $lastName = strtolower($request->last_name);
        $username = $firstName[0] . $lastName;
        $admin->first_name = mb_convert_encoding($request->first_name, 'UTF-8', 'UTF-8');
        $request->first_name;
        $admin->last_name =  mb_convert_encoding($request->last_name, 'UTF-8', 'UTF-8');
        $username = "User" . rand(1, 200000);
        $i = 0;
        while (User::whereuser_name($username)->exists()) {
            $i++;
            $username = "User" . $i;
        }
        $admin->user_name = $username;
        $admin->phone_number = $request->phone_number;
        $admin->birth_date = $request->birth_date != "" ? $request->birth_date : null;
        $admin->gender = $request->gender;
        $admin->role = User::ADMIN;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        if (isset($request->image)) {
            $admin->image = FileHandler::store_img($request->image, 'users_images');
        }
        $admin->save();
    }

    public function updateAdmin($admin, $request)
    {
        if ($request->hasFile('image')) {
            Storage::disk('users_images')->delete($admin->image);
            $admin->image = FileHandler::store_img($request->image, 'users_images');
        } else {
        }
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->phone_number = $request->phone_number;
        $admin->birth_date = $request->birth_date != "" ? $request->birth_date : null;
        $admin->gender = $request->gender;
        $admin->save();
    }
}
