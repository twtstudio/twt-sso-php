<?php

namespace \twtstudio\sso;

class Api {
  var $server = 'login.twtstudio.com';
  var $serverIp = '202.113.13.167';

  var $appid, $appkey, $https, $useIp;
  function __construct($appid, $appkey, $https = true, $useIp = false) {
    $this->appid = $appid;
    $this->appkey = $appkey;
    $this->https = $https;
    $this->useIp = $useIp;
  }

  private function _getServer($forceDomain = false) {
    return ($this->https ? 'https' : 'http') . '://' . ($this->useIp && !$forceDomain ? $this->serverIp : $this->server) . '/';
  }

  private function _getQuery($source = null) {
    if (!$source) $source = '';
    $query = 'app_id='.$this->appid.'&time='.time().'&source='.rtrim(strtr(base64_encode(json_encode($source)), '+/', '-_'), '=');
    return $query . '&sign=' . hash_hmac('sha1', $query, $this->appkey);
  }

  private function _request($url, $postData = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($this->useIp) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: ' . $this->server));
    }
    if ($postData) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }

    $data = curl_exec($ch);
    curl_close($ch);

    return json_decode($data);
  }

  function callApi($api, $source = null, $postData = null) {
    return $this->_request($this->_getServer() . $api . '?' . $this->_getQuery($source), $postData);
  }

  function getLoginUrl($redirUrl) {
    return $this->_getServer(true) . 'sso/login?' . $this->_getQuery($redirUrl);
  }

  function getUserInfo($token) {
    return $this->callApi('sso/getUserInfo/' . $token);
  }

  function logout($token) {
    return $this->_request($this->_getServer() . 'sso/logout/' . $token . '?' . $this->_getQuery());
  }
}
