<?php
namespace DdvPhp\DdvAuth;
use \DdvPhp\DdvUrl;
use \DdvPhp\DdvException\Error as ErrorException;

/**
 * Class Cors
 *
 * Wrapper around PHPMailer
 *
 * @package DdvPhp\DdvAuth\AuthSha256
 */
class AuthSha256
{
  public $authVersion = 'ddv-auth-v1';
  public $accessKeyId = '';
  public $accessKey = '';
  public $requestId = '';
  public $deviceCard = '';
  public $signTimeString = '';
  public $expiredTimeOffset = 1800;
  public $method = 'GET';
  public $scheme = 'http';
  public $uri = '';
  public $host = '';
  public $fragment = '';
  public $query = array();
  public $noSignQuery = array();
  public $headers = array();
  public $noSignHeaders = array();
  public function __destruct()
  {
    $this->method = 'GET';
    $this->scheme = 'http';
    $this->uri = '';
    $this->host = '';
    $this->fragment = '';
    $this->query = array();
    $this->noSignQuery = array();
    $this->headers = array();
    $this->noSignHeaders = array();
  }
  public function __construct($uri = null, $method = 'GET', $authVersion = 'ddv-auth-v1', $path = null, $query = array(), $noSignQuery = array(), $headers = array(), $noSignHeaders = array())
  {
    $this->setMethod($method);
    $this->setUri($uri);
    $this->setPath($path);
    $this->setQuery($query);
    $this->setNoSignQuery($noSignQuery);
    $this->setHeaders($headers);
    $this->setNoSignHeaders($noSignHeaders);
    $this->setAuthVersion($authVersion);
  }
  /**
   * 设置uri
   * @param string $uri [访问资源地址]
   * @return AuthSha256 $this [请求对象]
   */
  public function setUri($uri=''){
    $this->uri = $uri;
    // 解析uri
    $uriObj = DdvUrl::parse($uri);
    empty($uriObj['host']) || $this->setHost($uriObj['host']);
    empty($uriObj['path']) || $this->setPath($uriObj['path']);
    empty($uriObj['query']) || $this->setQuery($uriObj['query']);
    empty($uriObj['fragment']) || $this->setFragment($uriObj['fragment']);
    return $this;
  }
  /**
   * 设置fragment
   * @param [type] $fragment [description]
   * @return AuthSha256 $this [请求对象]
   */
  public function setFragment($fragment){
    $this->fragment = empty($fragment) ? '' : $fragment ;
    return $this;
  }
  /**
   * 设置path
   * @param string $path [相对跟路径]
   * @return AuthSha256 $this [请求对象]
   */
  public function setPath($path){
    $this->path = empty($path) ? '' : $path ;
    return $this;
  }
  public function setHost($host){
    $this->host = empty($host) ? '' : $host ;
    return $this;
  }
  /**
   * 设置请求参数
   * @param array  $query   [请求参数]
   * @param boolean $isClean [是否清除原有的]
   * @return AuthSha256 $this [请求对象]
   */
  public function setQuery($query, $isClean = false){
    if((!empty($query))&&is_string($query)){
      $query = DdvUrl::parseQuery($query);
    }
    if ($isClean !==false) {
      $this->query = array();
    }
    $this->query = array_merge($this->query, $query);
    return $this;
  }
  /**
   * 设置忽略签名的请求参数的key数组
   * @param array  $query   [请求参数]
   * @param boolean $isClean [是否清除原有的]
   * @return AuthSha256 $this [请求对象]
   */
  public function setNoSignQuery($noSignQuery, $isClean = false){
    if((!empty($noSignQuery))&&is_string($noSignQuery)){
      $noSignQuery = DdvUrl::parseQuery($noSignQuery);
    }
    if ($isClean !==false) {
      $this->noSignQuery = array();
    }
    $this->noSignQuery = array_merge($this->noSignQuery, $noSignQuery);
    return $this;
  }
  /**
   * 设置请求头的数组
   * @param array  $headers   [请求头]
   * @param boolean $isClean [是否清除原有的]
   * @return AuthSha256 $this [请求对象]
   */
  public function setHeaders($headers, $isClean = false){
    if ($isClean !==false) {
      $this->headers = array();
    }
    if (is_array($headers)) {
      foreach ($headers as $key => $value) {
         $this->headers[$key] =  is_array($value) ? implode('; ', $value) : $value;
      }
    }
    return $this;
  }
  /**
   * 设置忽略签名的请求头的key数组
   * @param array  $headers   [请求头key]
   * @param boolean $isClean [是否清除原有的]
   * @return AuthSha256 $this [请求对象]
   */
  public function setNoSignHeaders($noSignHeaders, $isClean = false){
    if ($isClean !==false) {
      $this->noSignHeaders = array();
    }
    $this->noSignHeaders = array_merge($this->noSignHeaders, $noSignHeaders);
    return $this;
  }
  public function setAuthVersion($authVersion){
    $this->authVersion = $authVersion;
  }
  /**
   * 授权id
   * @param string $accessKeyId [授权id]
   * @return AuthSha256 $this [请求对象]
   */
  public function setAccessKeyId($accessKeyId){
    $this->accessKeyId = $accessKeyId;
    return $this;
  }
  /**
   * 授权key
   * @param string $accessKey [授权key]
   * @return AuthSha256 $this [请求对象]
   */
  public function setAccessKey($accessKey){
    $this->accessKey = $accessKey;
    return $this;
  }
  /**
   * 设置请求id
   * @param string $requestId [请求id]
   * @return AuthSha256 $this [请求对象]
   */
  public function setRequestId($requestId){
    $this->requestId = $requestId;
    return $this;
  }
  /**
   * 设备cardid
   * @param string $deviceCard [设备cardid]
   * @return AuthSha256 $this [请求对象]
   */
  public function setDeviceCard($deviceCard){
    $this->deviceCard = $deviceCard;
    return $this;
  }
  /**
   * 设置请求方式
   * @param string $method [请求方式]
   * @return AuthSha256 $this [请求对象]
   */
  public function setMethod($method){
    $this->method = $method;
    return $this;
  }
  public function setExpiredTimeOffset($expiredTimeOffset){
    $this->expiredTimeOffset = $expiredTimeOffset;
    return $this;
  }
  public function setSignTimeString($signTimeString){
    if (is_numeric($signTimeString)) {
      $signTimeString = gmdate('Y-m-d\TH:i:s\Z', $signTimeString);
    }else if(class_exists('DateTime') && class_exists('DateTimeZone') && $signTimeString instanceof \DateTime){
      $signTimeString->setTimezone(new \DateTimeZone('UTC'));
      $signTimeString = $signTimeString->format('Y-m-d\TH:i:s\Z');
    }
    $this->signTimeString = $signTimeString;
    return $this;
  }
  public function getSigningKey($authString = null){
    if (empty($authString)) {
      $authString = $this->getAuthStringPrefix();
    }
    //生成加密key
    $signingKey = hash_hmac('sha256', $authString, $this->accessKey);
    return $signingKey;
  }
  public function getAuthStringPrefix(){
    // 授权字符串
    $authString = $this->authVersion;
    if (!empty($this->requestId)) {
      $authString .= '/'.$this->requestId;
    }
    $authString .= '/'.$this->accessKeyId;
    if (!empty($this->deviceCard)) {
      $authString .= '/'.$this->deviceCard;
    }
    $authString .= "/{$this->signTimeString}/{$this->expiredTimeOffset}";
    return $authString;
  }
  public function getAuthString(){
    $authObj = $this->getAuthArray();
    return $authObj['authString'];
  }
  public function checkSignTime(){
    //签名时间
    $signTime = empty($this->signTimeString) ? 0 : strtotime(strtoupper($this->signTimeString));
    //过期
    $expiredTimeOffset = empty($this->expiredTimeOffset) ? 0 : intval($this->expiredTimeOffset);
    //签名过期
    if (time() > ($signTime + $expiredTimeOffset)) {
      //抛出过期
      throw new ErrorException('Request authorization expired!','AUTHORIZATION_REQUEST_EXPIRED',403);
    }elseif (($signTime - $expiredTimeOffset) > time()) {
      //签名期限还没有到
      throw new ErrorException('Request authorization has not yet entered into force!','AUTHORIZATION_REQUEST_NOT_ENABLE',403);
    }
  }
  public function getAuthArray(){
    // 获取auth
    $authString = $this->getAuthStringPrefix();
    // 生成临时key
    $signingKey = $this->getSigningKey($authString);
    // 获取path
    $canonicalPath = DdvUrl::urlEncodeExceptSlash($this->path);
    // 重新排序编码
    $canonicalQuery = Sign::canonicalQuerySort(DdvUrl::buildQuery($this->query));

    $signHeaders = array();
    foreach ($this->headers as $key => $value) {
      if (in_array($key, $this->noSignHeaders)) {
        continue;
      }
      $signHeaders[$key] = $value;
    }
    // 通过
    $signHeaderKeysStr = implode(';', array_keys($signHeaders));
    // 获取签名头
    $canonicalHeaders = Sign::getCanonicalHeaders($signHeaders);
    //生成需要签名的信息体
    $canonicalRequest = "{$this->method}\n{$canonicalPath}\n{$canonicalQuery}\n{$canonicalHeaders}";

    //服务端模拟客户端算出的签名信息
    $sign = hash_hmac('sha256', $canonicalRequest, $signingKey);
    // 组成最终签名串
    $authString .= "/{$signHeaderKeysStr}/{$sign}";

    return array(
      'requestId.server'=>$this->requestId,
      'accessKeyId.server'=>$this->accessKeyId,
      'accessKey.server'=>$this->accessKey,
      'deviceCard.server'=>$this->deviceCard,
      'signingKey.server'=>$signingKey,
      'signHeaderKeysStr.server'=>$signHeaderKeysStr,
      'canonicalRequest.server'=>$canonicalRequest,
      'sign'=>$sign,
      'authString'=>$authString
    );
  }
}
