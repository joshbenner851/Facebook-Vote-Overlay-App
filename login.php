<?php

require_once __DIR__ . '/vendor/autoload.php';


$fb = new Facebook\Facebook([
  'app_id' => '970982232977864', // Replace {app-id} with your app id
  'app_secret' => 'a2a891313142f7bb0b9356b02f840bbc',
  'default_graph_version' => 'v2.5',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://localhost.com:8888/FacebookShamerApp/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>