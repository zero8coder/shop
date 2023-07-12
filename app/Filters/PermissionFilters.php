<?php


namespace App\Filters;

class PermissionFilters extends BaseFilters
{
    protected $filters = [
        'name'
    ];

    public function name($name)
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }
}
