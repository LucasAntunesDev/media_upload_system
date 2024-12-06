<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller {
    public function index() {
        $images = Image::all();
        return $images;
    }
    public function show($id) {
        $image = Image::find($id);
        return $image;
    }
    public function store(StoreImageRequest $image) {
        $path = $image->file('url')->store('images', 'public');

        if (!$path) {
            return response()->json(['message' => 'Erro ao armazenar a imagem.'], 500);
        }

        $newImage = Image::create([
            'url' => $path,
        ]);

        if ($newImage) {
            return response()->json([
                "message" => 'Nova imagem adicionada com sucesso!',
                'image' => $newImage,
            ]);
        }

        return response()->json([
            'message' => 'Deu ruim. Te vira aí pra descobrir o que aconteceu.'
        ], 422);
    }
    public function update() {
    }
    public function destroy(Image $image) {
        $image->delete();

        return response()->json([
            "message" => 'Imagem removida com sucesso!',
        ]);
    }
}
