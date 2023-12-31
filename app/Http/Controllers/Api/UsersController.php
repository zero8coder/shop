<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Cache;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->input('verification_key'));
        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->input('verification_code'))) {
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name'     => $request->input('name'),
            'phone'    => $verifyData['phone'],
            'password' => $request->input('password')
        ]);

        Cache::forget($request->input('verification_key'));

        return $this->success((new UserResource($user))->showSensitiveFields());

    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    public function me(Request $request)
    {
        return $this->success((new UserResource($request->user()))->showSensitiveFields());
    }

    public function update(UserRequest $request)
    {
        $user       = $request->user();
        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image                = Image::find($request->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);
        return $this->success((new UserResource($user))->showSensitiveFields());
    }
}
