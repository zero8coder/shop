<?php

namespace App\Models;

use App\Filters\BaseFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


/**
 * @method static latest()
 * @method static create(array $array)
 * @method static where(array $array)
 * @property mixed sex
 */
class Admin extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use HasRoles;

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

    const SEX_MAN = 1;
    const SEX_WOMAN = 2;
    public static $sexMap = [
        self::SEX_MAN   => '男',
        self::SEX_WOMAN => '女',
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

    // 重写 passport 验证
    public function findForPassport($username)
    {
        return self::where(['name' => $username])->first();
    }

    // 获取性别名称
    public function getSexNameAttribute(): string
    {
        return self::$sexMap[$this->sex] ?? '';
    }

    // 过滤器
    public function scopeFilter($query, BaseFilters $filters)
    {
        return $filters->apply($query);
    }
}
