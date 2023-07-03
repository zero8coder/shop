<?php


namespace App\Filters;

class AdminFilters extends BaseFilters
{
    protected $filters = [
        'name',
        'sex',
        'phone'
    ];

    public function name($name)
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }

    public function phone($phone)
    {
        return $this->builder->where('phone', 'like', $phone . '%');
    }

    public function sex($sex)
    {
        return $this->builder->where('sex', $sex);
    }
}
