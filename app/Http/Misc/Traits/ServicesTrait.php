<?php

namespace App\Http\Misc\Traits;

use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\FileHandler;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceComments;
use App\Models\ServiceImages;
use App\Models\ServiceRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


trait ServicesTrait
{

    public function indexSearch(Request $request, $services)
    {
        if ($request->query('category_id') && $request->query('category_id') != 'none') {
            $services = $services->where('category_id', $request->query('category_id'));
        }
        if ($request->query('government_id') && $request->query('government_id') != 'none') {
            $services = $services->where('government_id', $request->query('government_id'));
        }
        if ($request->query('city_id') && $request->query('city_id') != 'none') {
            $services = $services->where('city_id', $request->query('city_id'));
        }
        if ($request->query('min_price') && $request->query('min_price') != 'none') {
            $services = $services->where('price_from', '>', $request->query('min_price'));
        }
        if ($request->query('max_price') && $request->query('max_price') != 'none') {
            $services = $services->where('price_to', '<', $request->query('max_price'));
        }
        if ($request->viewed == 'most') {
            $services = $services->orderBy('visited', 'desc');
        } else {
            $services = $services->orderBy('created_at', 'desc');
        }
        return $services;
    }

    public function saveServices(Request $request, $service)
    {
        $service->government_id = $request->government_id;
        $service->city_id = $request->city_id;
        $service->name = $request->name;
        $service->vendor_type = $request->vendor_type;
        $service->description = $request->description;
        $service->banner_type = $request->banner_type;
        $service->price_from = $request->price_from;
        $service->price_to = $request->price_to;
        if ($request->price_to == null) {
            $service->price_type = Service::NUM;
        } else {
            $service->price_type = Service::RANGE;
        }
    }

    public function primaryService(Request $request, $service)
    {
        $service->whats_app = $request->whats_app;
        $service->contact_number = $request->contact_number;
        $service->contact_mail = $request->contact_mail;
        if (isset($request->big_image)) {
            $service->banner = Base64Handler::storeFile($request->big_image, 'services_images');
        }
        if (isset($request->profile_image)) {
            $service->profile_image = Base64Handler::storeFile($request->profile_image, 'services_images');
        }
    }

    public function image(Request $request, $service)
    {
        if ($service->images) {
            foreach ($service->images as $image) {
                $path = Str::after($image->media, 'public');
                $path = base_path('public' . $path);
                // unlink($path);
                $image->delete();
            }
        }
        foreach ($request->images as $image) {
            $service_image = new ServiceImages();
            $service_image->media =  Base64Handler::storeFile($image['image'], 'services_images');
            $service_image->service_id = $service->id;
            $service_image->save();
        }
    }

    public function secandService(Request $request, $sec_service)
    {
        $sec_service->category_id = $request->category_id;
        $sec_service->user_id = $request->user()->id;
        $sec_service->type = Service::SECONDARY;
    }

    public function serviceComment($comment, $service, $request)
    {
        $comment->user_id = $request->user()->id;
        $comment->service_id = $service->id;
        $comment->caption = $request->comment;
        $comment->rate = $request->rate;
        $comment->save();
        if (isset($request->rate)) {
            $rate = $service->ratings()->where('user_id', $request->user()->id)->first();
            if (!$rate) $rate = new ServiceRating();
            $rate->rate = $request->rate;
            $rate->user_id = $request->user()->id;
            $rate->service_id = $service->id;
            $rate->save();
        }
    }

    public function PrimaryServiceAdmin($service, $request)
    {
        $government = City::where("id", $request->city_id)->first()->government;
        $service->name = $request->name;
        $service->price_from = $request->price_from;
        $service->price_to = $request->price_to;
        if ($request->price_to == null) {
            $service->price_type = Service::NUM;
        } else {
            $service->price_type = Service::RANGE;
        }
        $service->status = Service::PUBLISHED;
        $service->government_id = $government->id;
        $service->city_id = $request->city_id;
        $service->vendor_type = $request->vendor_type;
        $service->description = $request->description;
        $service->whats_app = $request->whats_app;
        $service->contact_number = $request->contact_number;
        $service->contact_mail = $request->contact_mail;
        $service->banner = FileHandler::store_img($request->banner, 'services_images');
        $service->profile_image = FileHandler::store_img($request->profile_image, 'services_images');
        $service->banner_type = $request->banner_type;
        $service->video_url = $request->video_url;
        $service->status = $request->status;
        if ($request->status == Service::PUBLISHED) {
            $service->user->status = User::COMPLETED;
        } else {
            $service->user->status = User::NOTCOMPLETED;
        }
        if (isset($request->slug)) {
            $service->slug = trim($request->slug, " ");
        } else {
            $service->slug = trim($service->name . $service->id, " ");
        }
        $service->user->save();
        $service->save();
    }


    public function UpdatePrimaryAdmin($primary_service, $request)
    {
        $primary_service->name = $request->name;
        if ($request->price_from != "")
            $primary_service->price_from = $request->price_from;

        if ($request->price_to != "")
            $primary_service->price_to = $request->price_to;

        if ($request->category_id != "") {
            $primary_service->category_id = $request->category_id;
            $primary_service->user->category_id =  $request->category_id;
        }

        if ($request->price_to == null) {
            $primary_service->price_type = Service::NUM;
        } else {
            $primary_service->price_type = Service::RANGE;
        }
        $primary_service->status = $request->status;
        if ($request->government != "") {

            $primary_service->government_id = $request->government;
        }
        if ($request->city != "") {
            $primary_service->city_id = $request->city;
        }

        if ($request->vendor_type != "") $primary_service->vendor_type = $request->vendor_type;

        $primary_service->description = $request->description;
        $primary_service->whats_app = $request->whats_app;
        $primary_service->contact_number = $request->contact_number;
        $primary_service->contact_mail = $request->contact_mail;
        if ($request->hasFile('profile_image')) {
            Storage::disk('services_images')->delete($primary_service->profile_image);
            $primary_service->profile_image = FileHandler::store_img($request->profile_image, 'services_images');
        } else {
        }
        if ($request->hasFile('banner')) {
            Storage::disk('services_images')->delete($primary_service->banner);
            $primary_service->banner = FileHandler::store_img($request->banner, 'services_images');
        } else {
        }
        $primary_service->banner_type = $request->banner_type;
        $primary_service->video_url = $request->video_url;

        if (isset($request->slug)) {
            $primary_service->slug = trim($request->slug, " ");
        } else {
            $primary_service->slug = trim($primary_service->name . $primary_service->id, " ");
        }
        if ($request->status == Service::PUBLISHED) {
            $primary_service->user->status = User::COMPLETED;
        } else {
            $primary_service->user->status = User::NOTCOMPLETED;
        }
        $primary_service->user->save();
        $primary_service->save();
    }

    public function UpdateSecondaryAdmin($secondary_service, $request)
    {
        $secondary_service->name = $request->name;
        if ($request->price_from != "")
            $secondary_service->price_from = $request->price_from;

        if ($request->price_to != "")
            $secondary_service->price_to = $request->price_to;

        if ($request->category_id != "") {
            $secondary_service->category_id = $request->category_id;
            $secondary_service->user->category_id =  $request->category_id;
        }

        if ($request->price_to == null) {
            $secondary_service->price_type = Service::NUM;
        } else {
            $secondary_service->price_type = Service::RANGE;
        }
        $secondary_service->status = $request->status;
        if ($request->government != "") {

            $secondary_service->government_id = $request->government;
        }
        if ($request->city != "") {
            $secondary_service->city_id = $request->city;
        }

        if ($request->vendor_type != "") $secondary_service->vendor_type = $request->vendor_type;

        $secondary_service->description = $request->description;
        $secondary_service->whats_app = $request->whats_app;
        $secondary_service->contact_number = $request->contact_number;
        $secondary_service->contact_mail = $request->contact_mail;
        if ($request->hasFile('profile_image')) {
            Storage::disk('services_images')->delete($secondary_service->profile_image);
            $secondary_service->profile_image = FileHandler::store_img($request->profile_image, 'services_images');
        } else {
        }
        $secondary_service->banner_type = $request->banner_type;
        $secondary_service->video_url = $request->video_url;

        if (isset($request->slug)) {
            $secondary_service->slug = trim($request->slug, " ");
        } else {
            $secondary_service->slug = trim($secondary_service->name . $secondary_service->id, " ");
        }

        $secondary_service->status = $request->status;
        $secondary_service->user->save();
        $secondary_service->save();
    }

    public function StoreSecondaryAdmin($secondary_service, $request)
    {
        if ($request->name) {
            $secondary_service->name = $request->name;
        }
        if ($request->government) {
            $secondary_service->government_id = $request->government;
        }
        if ($request->city) {
            $secondary_service->city_id = $request->city;
        }
        if ($request->category_id) {
            $secondary_service->category_id = $request->category_id;
        }
        if ($request->user_id) {
            $secondary_service->user_id = $request->user_id;
        }
        if ($request->description) {
            $secondary_service->description = $request->description;
        }
        if ($request->video_url) {
            $secondary_service->video_url = $request->video_url;
        }
        if ($request->banner_type) {
            $secondary_service->banner_type = $request->banner_type;
        }
        if ($request->price_from) {
            $secondary_service->price_from = $request->price_from;
        }
        if ($request->price_to) {
            $secondary_service->price_to = $request->price_to;
        }
        $secondary_service->status = $request->status;
        if ($request->price_to == null) {
            $secondary_service->price_type = Service::NUM;
        } else {
            $secondary_service->price_type = Service::RANGE;
        }
        $secondary_service->save();
    }
}
