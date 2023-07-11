<?php


namespace App\Filters;

class RoleFilters extends BaseFilters
{
    protected $filters = [
        'name'
    ];

    public function name($name)
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }
}
