<?php

namespace TwT\SSO;

class Api {
  var $server = 'login.twt.edu.cn';

  var $appid, $appkey, $https;
  function __construct($appid, $appkey, $https = true) {
    $this->appid = $appid;
    $this->appkey = $appkey;
    $this->https = $https;
  }

  private function _getServer() {
    return ($this->https ? 'https' : 'http') . '://' . $this->server . '/';
  }

  private function _getQuery($source = null) {
    if (!$source) $source = '';
    $query = 'app_id=' . $this->appid . '&time=' . time() . '&source=' . rtrim(strtr(base64_encode(json_encode($source)), '+/', '-_'), '=');
    return $query . '&sign=' . hash_hmac('sha1', $query, $this->appkey);
  }

  private function _request($url, $postData = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

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
    return $this->_getServer() . 'sso/login?' . $this->_getQuery($redirUrl);
  }

  function getVerifiedPhoneQuery($user, $token) {
    return $this->_getQuery([
      'user' => $user,
      'token' => $token
    ]);
  }

  function getVerifiedPhoneSign($phone, $token, $time) {
    return base64_encode(hash_hmac('sha256', $phone . '@' . $token . '@' . $time, $this->appkey, true));
  }

  /**
   * @deprecated Use fetchUserInfo instead.
   */
  function getUserInfo($token) {
    return $this->fetchUserInfo($token);
  }

  function fetchUserInfo($token) {
    return $this->callApi('sso/getUserInfo', $token);
  }

  function logout($token) {
    return $this->callApi('sso/logout', $token);
  }
}
