<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class ImageFavoriteController extends Controller {
    public function addFavorite($imageId) {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $user->favoriteImages()->attach($image);

        return response()->json(['message' => 'Image favorited successfully']);
    }

    public function removeFavorite($imageId) {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $user->favoriteImages()->detach($image);

        return response()->json(['message' => 'Image unfavorited successfully']);
    }
}
