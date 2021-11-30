<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Overtrue\LaravelSocialite\Socialite;

class AuthorizationsController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = Socialite::create($type);
        try {
            if ($code = $request->input('code')) {
                $oauthUser = $driver->userFromCode($code);
            } else {
                if ($type == 'wechat') {
                    $driver->withOpenid($request->input('openid'));
                }
                $oauthUser = $driver->userFromToken($request->input('access_token'));
            }
        } catch (Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        if (!$oauthUser->getId()) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'wechat':
                $unionid = $oauthUser->getRaw()['unionid'] ?? null;
                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid
                    ]);
                }
                break;
        }

        return response()->json(['token' => $user->id]);

    }
}
