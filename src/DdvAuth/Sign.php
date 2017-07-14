<?php
namespace DdvPhp\DdvAuth;
use \DdvPhp\DdvUrl;

/**
 * Class Cors
 *
 * Wrapper around PHPMailer
 *
 * @package DdvPhp\DdvAuth\Sign
 */
class Sign
{
  public static function getCanonicalHeaders($signHeaders = array())
  {
    //重新编码
    $canonicalHeader = array();
    foreach ($signHeaders as $key => $value) {
      $canonicalHeader[] = strtolower(DdvUrl::urlEncode(trim($key))).':'.DdvUrl::urlEncode(trim(is_array($value) ? implode('; ', $value) : $value));
    }
    sort($canonicalHeader);
    //服务器模拟客户端生成的头
    $canonicalHeader = implode("\n", $canonicalHeader) ;
    return $canonicalHeader;
  }
  public static function canonicalQuerySort($canonicalQuery = '')
  {
    //拆分get请求的参数
    $canonicalQuery = empty($canonicalQuery) ? array() : explode('&', $canonicalQuery);
    $tempNew = array();
    $temp = '';
    $tempI = '';
    $tempKey = '';
    $tempValue = '';
    foreach ($canonicalQuery as $key => $temp) {
      $tempI = strpos($temp,'=');
      if (strpos($temp,'=')===false) {
        continue;
      }
      $tempKey = substr($temp, 0,$tempI);
      $tempValue = substr($temp, $tempI+1);
      
      $tempNew[] = DdvUrl::urlEncode(DdvUrl::urlDecode($tempKey)).'='.DdvUrl::urlEncode(DdvUrl::urlDecode($tempValue));
    }
    sort($tempNew);
    $canonicalQuery = implode('&', $tempNew) ;
    unset($temp,$tempI,$tempKey,$tempValue,$tempNew);
    return $canonicalQuery;
  }
}


