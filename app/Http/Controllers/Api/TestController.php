<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $uploader = new ImageUploadHandler();
        return $uploader->save($request->file('avatar'), 'avatars', 'test', 10);
    }
}
