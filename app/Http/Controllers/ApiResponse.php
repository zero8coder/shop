<?php


namespace App\Http\Controllers;


use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * 返回成功信息
     * @param array $data
     * @param string $message
     * @param int $code
     * @param bool $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], string $message = "请求成功", int $code = Response::HTTP_OK, $status = 'success'): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => $status, 'code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * 返回失败信息
     * @param $code
     * @param $message
     * @param bool $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($code, $message, string $status = 'error'): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => $status, 'code' => $code, 'message' => $message]);
    }
}
