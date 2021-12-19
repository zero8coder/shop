<?php

namespace App\Handlers;

use Illuminate\Http\UploadedFile;
use Str;
use Intervention\Image\Facades\Image;

class ImageUploadHandler
{
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    /**
     * @param $file UploadedFile 上传的图片
     * @param $folder string 存储的文件夹
     * @param $file_prefix string 文件名前缀
     * @param $max_width int 图片宽度限制
     */
    public function save(UploadedFile $file, string $folder, string $file_prefix, $max_width = false)
    {
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder" . date("Ym/d", time());

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/shop/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        $extension=  strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        $file->move($upload_path, $filename);

        if ($max_width && $extension != 'gif') {
            // 裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
          'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        $image = Image::make($file_path);
        $image->resize($max_width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save();
    }

}
