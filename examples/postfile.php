<?php

require('./../phu/mattermost.php');

$data = array(
    'url' => 'https://mattermost.example.com',
    'login_id' => 'johndoe',
    'password' => 'secret-password',
);

$mm = new \Phu\Mattermost($data);

$token = $mm->getAuthenticationToken();

$file = 'image.jpg';

$data = array(
    'channel_id' => 'xxxxxxxxxxxxxxxx',
    'team_id' => 'xxxxxxxxxxxxxxxx',
);
$response = $mm->postFile($token, $file, $data);
