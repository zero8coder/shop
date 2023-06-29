<?php


namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * 返回成功信息
     * @param  $data
     * @param string $message
     * @param int $code
     * @param bool $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], string $message = "请求成功", $http_code = Response::HTTP_OK, int $business_code = Response::HTTP_OK, $status = 'success'): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => $status, 'code' => $business_code, 'message' => $message, 'data' => $data], $http_code);
    }

    /**
     * 返回失败信息
     * @param $message
     * @param $code
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $http_code = Response::HTTP_INTERNAL_SERVER_ERROR, $business_code = 500, string $status = 'error'): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => $status, 'code' => $business_code, 'message' => $message], $http_code);
    }

    /**
     * 重写分页格式
     * @param $data
     * @return array|mixed
     */
    public function formatPaginatorData($data)
    {
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            return [
                'list' => $data->items(),
                'page'    => $data->currentPage(),
                'perPage'  => $data->perPage(),
                'total'    => $data->total(),
                'lastPage' => $data->lastPage()
            ];
        }

        return $data;
    }
}
