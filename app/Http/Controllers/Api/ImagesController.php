<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Str;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image): ImageResource
    {
        $user = $request->user();
        $type = $request->input('type');
        $size = $type == 'avatar' ? 416 : 1024;
        $result = $uploader->save($request->file('image'), Str::plural($type), $user->id, $size);
        $image->path = $result['path'];
        $image->type = $type;
        $image->user_id = $user->id;
        $image->save();
        return new ImageResource($image);

    }
}
