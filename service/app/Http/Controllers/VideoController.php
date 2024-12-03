<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller {
    public function index() {
        $videos = Video::all();
        return $videos;
    }
    public function show($id) {
        $video = Video::find($id);
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
    public function update() {
    }
    public function destroy(Video $video) {
        $video->delete();

        return response()->json([
            "message" => 'Vídeo removido com sucesso!',
        ]);
    }
}
