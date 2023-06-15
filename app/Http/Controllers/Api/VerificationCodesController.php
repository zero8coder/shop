<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VerificationCodeRequest;
use Cache;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function store(VerificationCodeRequest $request)
    {
        $captcha_key  = $request->input('captcha_key');
        $captcha_code = $request->input('captcha_code');
        $captchaData  = Cache::get($captcha_key);

        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $captcha_code)) {
            Cache::forget($captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            try {
                $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            } catch (Exception $e) {
                throw new AuthenticationException('生成验证失败');
            }
            // todo 发送短息
        }

        $key       = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        Cache::forget($captcha_key);
        return response()->json([
            'key'        => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
