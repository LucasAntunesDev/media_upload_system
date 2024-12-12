<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VideoFavoriteController extends Controller {

    public function addFavorite($videoId) {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $video = Video::find($videoId);

        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }

        $user->favoriteVideos()->attach($video);

        return response()->json(['message' => 'Video favorited successfully']);
    }

    public function removeFavorite($videoId) {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $video = Video::find($videoId);

        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }

        $user->favoriteVideos()->detach($video);

        return response()->json(['message' => 'Video unfavorited successfully']);
    }
}
