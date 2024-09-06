<?php

function safe_b64encode($string='') {
  $data = base64_encode($string);
  $data = str_replace(['+','/','='],['-','_',''],$data);
  return $data;
}

function safe_b64decode($string='') {
  $data = str_replace(['-','_'],['+','/'],$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
      $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}
function encrypt($value) {
  if(!$value) return false;
  $iv_size = openssl_cipher_iv_length('aes-256-cbc');
  $iv = openssl_random_pseudo_bytes($iv_size);
  $crypttext = openssl_encrypt($value, 'aes-256-cbc', 'your security cipherSeed', OPENSSL_RAW_DATA, $iv);
  return safe_b64encode($iv.$crypttext);
}

function decrypt($value) {
  if(!$value) return false;
  $crypttext = safe_b64decode($value);
  $iv_size = openssl_cipher_iv_length('aes-256-cbc');
  $iv = substr($crypttext, 0, $iv_size);
  $crypttext = substr($crypttext, $iv_size);
  if(!$crypttext) return false;
  $decrypttext = openssl_decrypt($crypttext, 'aes-256-cbc', 'your security cipherSeed', OPENSSL_RAW_DATA, $iv);
  return rtrim($decrypttext);
}
