<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Menu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

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

    /**
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Menu::class);
        $tree = $this->buildTree(Menu::all()->toArray());
        return $this->success($tree);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(MenuRequest $request, Menu $menu): JsonResponse
    {
        $this->authorize('create', Menu::class);
        $menu->fill($request->all());
        $menu->save();
        return $this->success($menu);
    }

}
