<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $showSensitiveFields = false;

    public function toArray($request)
    {

        if (!$this->showSensitiveFields) {
            $this->resource->makeHidden(['phone', 'email']);
        }
        $data = parent::toArray($request);
        $data['bound_phone'] = (bool)$this->resource->phone;
        $data['bound_wechat'] = $this->resource->weixin_unionid || $this->resource->weixin_openid;
        return $data;
    }

    public function showSensitiveFields(): UserResource
    {
        $this->showSensitiveFields = true;
        return $this;
    }
}
