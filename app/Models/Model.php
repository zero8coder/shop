<?php


namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
