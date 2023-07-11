<?php

namespace App\Models;

use App\Filters\BaseFilters;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;
class Role extends BaseRole
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    // 过滤器
    public function scopeFilter($query, BaseFilters $filters)
    {
        return $filters->apply($query);
    }
}
