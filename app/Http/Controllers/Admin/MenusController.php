<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Menu::class);
        $menu = Menu::orderby('id', 'asc')->get()->toArray();
        $tree = $this->buildTree($menu);
        return $this->success($tree);
    }

    public function store(MenuRequest $request, Menu $menu): JsonResponse
    {
        $this->authorize('create', Menu::class);
        $menu->fill($request->all());
        $menu->save();
        return $this->success($menu);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        $this->authorize('update', $menu);
        $menu->fill($request->all());
        $menu->update();
        return $this->success($menu);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $this->authorize('delete', $menu);
        $menu->delete();
        return $this->success();
    }

}
