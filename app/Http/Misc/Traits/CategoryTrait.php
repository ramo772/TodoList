<?php

namespace App\Http\Misc\Traits;

use Illuminate\Support\Str;
use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\Errors;
use App\Http\Misc\Helpers\FileHandler;
use App\Models\Banner;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait CategoryTrait
{

    public function StoreCategory($category, $request)
    {
        $category->name = $request->name;
        $category->parent_id = $request->category;
        $category->ar_name = $request->ar_name;
        $category->keyword = $request->keyword;
        $category->seo_title = $request->seo_title;
        $category->seo_description = $request->seo_description;
        $category->slug = Str::slug($request->name);
        if ($request->order) {
            $category->order = $request->order;
        }
        if ($request->hasFile('image')) {
            Storage::disk('category_images')->delete($category->image);
            $category->image = FileHandler::store_img($request->image, 'category_images');
        } else {
        }
        $category->save();
    }

    public function UpdateCategory($category, $request)
    {
        $category->name = $request->name;
        $category->ar_name = $request->ar_name;
        if ($request->order) {
            $category->order = $request->order;
        }
        if ($request->hasFile('image')) {
            Storage::disk('category_images')->delete($category->image);
            $category->image = FileHandler::store_img($request->image, 'category_images');
        }
        if (isset($request->parent)) {
            $category->parent_id =  $request->parent;
        }
        if (isset($request->keyword)) {
            $category->keyword = $request->keyword;
        }
        if (isset($request->seo_title)) {
            $category->seo_title = $request->seo_title;
        }
        if (isset($request->seo_description)) {
            $category->seo_description = $request->seo_description;
        }
        $category->save();
    }
}
