<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'sex',
        'password',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }
}
