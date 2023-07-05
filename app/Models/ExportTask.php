<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property mixed module
 * @property mixed param
 * @property mixed|string url
 * @property int|mixed status
 */
class ExportTask extends Model
{
    use HasFactory;

    const STATUS_WAIT = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = 3;

    public static $statusMap = [
        self::STATUS_WAIT => '待处理',
        self::STATUS_SUCCESS => '成功',
        self::STATUS_FAIL => '失败',
    ];

    protected $fillable = [
        'name',
        'module',
        'param',
        'url',
        'status',
        'admin_id',
        'admin_name'
    ];

    protected $casts = [
        'param' => 'array'
    ];

    public static $moduleMap = [
        'admin' => 'AdminExport',
    ];


    public static function addTask($name, $module, $param = [])
    {
        return self::query()->create([
            'name' => $name,
            'module' => $module,
            'param' => $param,
            'url' => '',
            'status' => self::STATUS_WAIT,
            'admin_id' => auth('admin')->id(),
            'admin_name' => auth('admin')->user()['name'] ?? ''
        ]);
    }

    public function isWait()
    {
        return self::STATUS_WAIT == $this->status;
    }

    public static function exportFilesSuffix(): string
    {
        return Carbon::now()->toDateTimeString() . '-' . auth('admin')->user()['name'] ?? '';
    }
}
