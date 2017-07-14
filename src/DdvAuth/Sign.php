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
  public static function getHeaderKeysByStr($signHeaderKeysStr = '')
  {
    //被签名的头的key，包括自定义和系统
    $signHeaderKeysStr = (is_string($signHeaderKeysStr)||is_numeric($signHeaderKeysStr)) ? (string)$signHeaderKeysStr : '';
    //拆分头键名为数组 方便后期处理
    $signHeaderKeys = explode(';', $signHeaderKeysStr);
    //定义一个空数组来存储对授权头key预处理
    $signHeaderKeysNew = array();
    //遍历授权头的key
    foreach ($signHeaderKeys as $key => $authHeader) {
      //去空格，转小写
      $signHeaderKeysNew[]=trim($authHeader);
    }
    //把处理后的头的key覆盖原来的变量，释放内存
    $signHeaderKeys = $signHeaderKeysNew;unset($signHeaderKeysNew);
    //移除数组中重复的值
    $signHeaderKeys = array_unique($signHeaderKeys);
    return $signHeaderKeys;
  }
  //生成guid
  public static function createGuid( $opt = false ){
    //  Set to true/false as your default way to do this.
    if( function_exists('com_create_guid') ){
      return $opt?com_create_guid() : trim( com_create_guid(), '{}' );
    }else{
      mt_srand( (double)microtime() * 10000 );    // optional for php 4.2.0 and up.
      $charid = strtoupper( md5(uniqid(rand(), true)) );
      $hyphen = chr( 45 );    // "-"
      $left_curly = $opt ? chr(123) : "";     //  "{"
      $right_curly = $opt ? chr(125) : "";    //  "}"
      $uuid = $left_curly
          . substr( $charid, 0, 8 ) . $hyphen
          . substr( $charid, 8, 4 ) . $hyphen
          . substr( $charid, 12, 4 ) . $hyphen
          . substr( $charid, 16, 4 ) . $hyphen
          . substr( $charid, 20, 12 )
          . $right_curly;
      return $uuid;
    }
  }
}
