<?php

namespace Maxima\Helpers;



class PhotoHelper
{
    public static function getResizeArray($filePath)
    {
        $arr = \CFile::MakeFileArray($filePath);
        $size = getimagesize($filePath);
        return[
            "HEIGHT" => $size[1],
            "WIDTH" => $size[0],
            "FILE_SIZE"=> $arr['size'],
            "CONTENT_TYPE"=>  $arr['type'],
            "SUBDIR" => dirname(str_replace($_SERVER['DOCUMENT_ROOT'] . '/upload/', '', $arr['tmp_name'])),
            "FILE_NAME" => basename($arr['tmp_name']),
            "SRC" => str_replace($_SERVER['DOCUMENT_ROOT'], '', $arr['tmp_name'])
        ];
    }
}