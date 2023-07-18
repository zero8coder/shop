<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenusController extends Controller
{

    private function buildTree(array $data, $pid = "0"): array
    {
        $tree = [];
        foreach ($data as $item) {
            if ($item['pid'] === $pid) {
                $children = $this->buildTree($data, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    public function index()
    {
        $this->authorize('viewAny', Menu::class);
        $tree = $this->buildTree(Menu::all()->toArray());
        return $this->success($tree);
    }
}
