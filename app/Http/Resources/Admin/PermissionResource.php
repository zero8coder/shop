<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' =>  Carbon::parse($this->created_at)->format("Y-m-d H:i:s"),
            'updated_at' =>  Carbon::parse($this->updated_at)->format("Y-m-d H:i:s"),
        ];
    }
}
