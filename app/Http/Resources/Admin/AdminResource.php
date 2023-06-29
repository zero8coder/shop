<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'sex' => Admin::$sexMap[$this->sex] ?? '',
        ];
    }
}
