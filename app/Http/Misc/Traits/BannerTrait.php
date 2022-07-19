<?php

namespace App\Http\Misc\Traits;

use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\Errors;
use App\Http\Misc\Helpers\FileHandler;
use App\Models\Banner;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait BannerTrait
{

    public function StoreBanner($banner, $request)
    {
        $mime = mime_content_type(strval($request->media));

        if (strstr($mime, "image/")) {
            $banner->type = Banner::IMAGE;
        } elseif (strstr($mime, "video/")) {
            $banner->type = Banner::VIDEO;
        }
        $banner->description = $request->description;
        $banner->url = $request->url;
        $banner->position = $request->position;
        $banner->image = FileHandler::store_img($request->media, 'banners_images');
        $banner->save();
    }

    public function UpdateBanner($banner , $request)
    {
        if (is_null($request->media)) {
        } else {
            $mime = mime_content_type(strval($request->media));

            if (strstr($mime, "image/")) {
                $banner->type = Banner::IMAGE;
            } elseif (strstr($mime, "video/")) {
                $banner->type = Banner::VIDEO;
            }
            Storage::disk('banners_images')->delete($request->image);
            $banner->image = FileHandler::store_img($request->media, 'banners_images');
        }

        $banner->description = $request->description;
        $banner->url = $request->url;
        $banner->position = $request->position;
        $banner->save();
    }
}
