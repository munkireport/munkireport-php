<?php

return [
  /*
  |===============================================
  | reCaptcha Integration
  |===============================================
  |
  | Enable reCaptcha Support on the Authentication Form
  | Request API keys from https://www.google.com/recaptcha
  |
  */
  'recaptchaloginpublickey'  => mr_env('RECAPTCHA_LOGIN_PUBLIC_KEY', ''),
  'recaptchaloginprivatekey' => mr_env('RECAPTCHA_LOGIN_PRIVATE_KEY', ''),
  
  /*
  |===============================================
  | Force secure connection when authenticating
  |===============================================
  |
  | Set this value to TRUE to force https when logging in.
  | This is useful for sites that serve MR both via http and https
  |
  */
  'auth_secure' => mr_env('AUTH_SECURE', false),

];
