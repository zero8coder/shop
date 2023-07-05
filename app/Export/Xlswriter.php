<?php


namespace App\Export;


class Xlswriter
{
    public const DOWNLOAD_PATH = '/uploads/excel';
    public static function getExcel($config = []): \Vtiful\Kernel\Excel
    {
        if (empty($config)) {
            $config = [
                'path' => base_path() . '/public' .  self::DOWNLOAD_PATH // xlsx文件保存路径
            ];
        }
        return new \Vtiful\Kernel\Excel($config);
    }

}
