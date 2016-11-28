<?php

require('./../phu/mattermost.php');

$data = array(
    'url' => 'https://mattermost.example.com',
    'login_id' => 'johndoe',
    'password' => 'secret-password',
);

$mm = new \Phu\Mattermost($data);

$token = $mm->getAuthenticationToken();

$data = array(
    'message' => 'Hello world !',
    'channel_id' => 'xxxxxxxxxxxxxxxx',
    'team_id' => 'xxxxxxxxxxxxxxxx',
);

$response = $mm->postMessage($token, $data);
