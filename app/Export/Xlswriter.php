<?php


namespace App\Export;


class Xlswriter
{
    public static function getExcel($config = []): \Vtiful\Kernel\Excel
    {
        if (empty($config)) {
            $config = [
                'path' => base_path() . '/public/uploads/excel' // xlsx文件保存路径
            ];
        }
        return new \Vtiful\Kernel\Excel($config);
    }

}
