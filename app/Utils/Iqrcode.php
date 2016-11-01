<?php
/*
 v1.0
 功能：二维码工具类
 作者：wuzhihui
 日期：2016/8/9
*/
namespace app\Utils;
use QrCode;
class IQrcode{
    //二维码参数
    public static $SIZE = 450;
    public static $MARGIN = 2;
    public static $ERROECORRECTION = 'M';
    public static $COLOR = [ 'r' => 149, 'g' => 24, 'b' => 48];
    //水印参数
    public static $WATERMARK_PATH = '/public/scama/images/qrcode_watermarking.jpg'; //水印路径
    public static $WATERMARK_SIZE = 0.25; //水印尺寸
    //根据追溯码生成二维码
    public function generate($str)
    {
        $path = 'storage/tmp/'.md5(date('ymdhis').$str).'.png';
        QrCode::format('png')->size(Iqrcode::$SIZE)->margin(Iqrcode::$MARGIN)->color(Iqrcode::$COLOR['r'],Iqrcode::$COLOR['g'],Iqrcode::$COLOR['b'])->errorCorrection(Iqrcode::$ERROECORRECTION)->merge(Iqrcode::$WATERMARK_PATH, Iqrcode::$WATERMARK_SIZE)->generate($str, $path);
        return $path;
    }
}