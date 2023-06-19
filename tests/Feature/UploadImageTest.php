<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    public function test_upload_avatar()
    {
        // 登录
        $this->signIn();
        \Storage::fake('public');
        $file = UploadedFile::fake()->image('test.png', 240, 240);
        $response = $this->post(route('api.v1.images.store'), [
            'type'  => 'avatar',
            'image' => $file,
        ]);
        $response->assertStatus(201);
        $this->assertCount(1, Image::all());
    }
}
