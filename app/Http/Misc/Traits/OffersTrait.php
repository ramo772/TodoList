<?php

namespace App\Http\Misc\Traits;

use App\Http\Misc\Helpers\Base64Handler;
use App\Http\Misc\Helpers\FileHandler;
use App\Models\OfferImages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait OffersTrait
{

    public function saveOffer(Request $request, $offer)
    {
        $offer->service_id = $request->service_id;
        $offer->category_id = $request->category_id;
        $offer->name = $request->name;
        $offer->offer_from = Carbon::parse($request->offer_from);
        $offer->offer_to = Carbon::parse($request->offer_to);
        $offer->government_id = $request->government_id;
        $offer->city_id = $request->city_id;
        $offer->description = $request->desc;

        if (isset($request->main_pic)) {
            $offer->main_pic = Base64Handler::storeFile($request->main_pic, 'offers_images');
        }
        $offer->video_url = $request->video_link;
        $offer->price_before = $request->price_from;
        $offer->price_after = $request->price_to;
        $offer->user_id = $request->user()->id;
        $offer->ratio = (($request->price_after) / ($offer->price_before)) * 100;
    }

    public function image(Request $request, $offer)
    {
        if (isset($request->slug)) {
            $offer->slug = trim($request->slug, " ");
        } else {
            $offer->slug = trim($request->name . $offer->id, "");
        }
        $offer->save();
        foreach ($request->images as $image) {
            $offer_image = new OfferImages();
            $offer_image->image =  Base64Handler::storeFile($image['image'], 'offers_images');
            $offer_image->offer_id = $offer->id;
            $offer_image->save();
        }
    }

    public function newComment($comment, $offer, $request)
    {
        $comment->user_id = $request->user()->id;
        $comment->offer_id = $offer->id;
        $comment->caption = $request->comment;
        $comment->rate = $request->rate;
        $comment->save();
    }
    public function offerReact($react, $comment, $request)
    {
        $react->type = $request->type;
        $react->user_id = $request->user()->id;
        $react->offer_id = $comment->offer->id;
        $react->comment_id = $comment->id;
        $react->save();
    }
    public function OfferStoreAdmin($offer, $request)
    {
        $offer->name =  $request->name;
        $offer->price_before =  $request->price_before;
        $offer->price_after =  $request->price_after;
        $offer->ratio = (($request->price_after) / ($offer->price_before)) * 100;
        $offer->offer_from =  $request->offer_from;
        $offer->offer_to =  $request->offer_to;
        $offer->user_id =  $request->user_id;
        $offer->category_id =  $request->category_id;
        $offer->service_id =  $request->service_id;
        $offer->city_id =  $request->city;
        $offer->video_url =  $request->video_url;
        $offer->status =  $request->status;
        $offer->description =  $request->description;
        $offer->government_id =  $request->government;
        $offer->main_pic = FileHandler::store_img($request->main_pic, 'offers_images');
        $offer->save();
    }
    public function offerUpdate($offer,  $request)
    {
        $offer->name =  $request->name;
        $offer->price_before =  $request->price_before;
        $offer->price_after =  $request->price_after;
        $offer->ratio = (($request->price_after) / ($offer->price_before)) * 100;
        $offer->offer_from =  $request->offer_from;
        $offer->offer_to =  $request->offer_to;
        $offer->user_id =  $request->user_id;
        $offer->category_id =  $request->category_id;
        $offer->service_id =  $request->service_id;
        $offer->city_id =  $request->city;
        $offer->video_url =  $request->video_url;
        $offer->status =  $request->status;
        $offer->description =  $request->description;
        $offer->ratio = (($request->price_after) / ($offer->price_before)) * 100;
        $offer->government_id =  $request->government;
        if ($request->hasFile('main_pic')) {
            Storage::disk('offers_images')->delete($offer->main_pic);
            $offer->main_pic = FileHandler::store_img($request->main_pic, 'offers_images');
        } else {
        }    }
}
