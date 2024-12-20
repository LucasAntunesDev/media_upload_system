<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VideoController extends Controller {
    public function index() {
        $user = JWTAuth::parseToken()->authenticate();

        $videos = Video::all()->map(function ($video) use ($user) {
            $video->favorited = $user->favoriteVideos()->where('video_id', $video->id)->exists();
            return $video;
        });

        return $videos;
    }
    public function show($id) {
        $user = JWTAuth::parseToken()->authenticate();
        $video = Video::find($id);

        $video->favorited = $user->favoriteVideos()->where('video_id', $video->id)->exists();
        return $video;
    }
    public function store(StoreVideoRequest $video) {
        $videoPath = $video->file('url')->store('videos', 'public');
        $thumbnailPath = $video->file('thumbnail')->store('images', 'public');

        if (!$videoPath) {
            return response()->json(['message' => 'Erro ao armazenar o vídeo.'], 500);
        }

        $newVideo = Video::create([
            'name' => $video->input('name'),
            'url' => $videoPath,
            'thumbnail' => $thumbnailPath,
            'tags' => $video->input('tags'),
            'isPrivate' => $video->input('isPrivate'),
        ]);

        if ($newVideo) {
            return response()->json([
                "message" => 'Novo vídeo adicionado com sucesso!',
                'video' => $newVideo,
            ]);
        }

        return response()->json([
            'message' => 'Deu ruim. Te vira aí pra descobrir o que aconteceu.'
        ], 422);
    }

    public function destroy(Video $video) {
        $video->delete();

        return response()->json([
            "message" => 'Vídeo removido com sucesso!',
        ]);
    }
}
