<?php
namespace Phu;

class Mattermost
{
    const VERSION = 'v3';
    const API = 'api';
    private $_url;
    private $_login_id;
    private $_password;

    public function __construct($data = array())
    {
        $this->_url = sprintf('%s/%s/%s', $data['url'], self::API, self::VERSION);
        $this->_login_id = $data['login_id'];
        $this->_password = $data['password'];
    }

    public function getAuthenticationToken()
    {
        try {
            $postfields = array(
                'login_id' => $this->_login_id,
                'password' => $this->_password,
            );
            $url = sprintf('%s/users/login', $this->_url);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                 ));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
            $response = explode("\n", curl_exec($ch));
            foreach ($response as $v) {
                if(preg_match('/Token:/i', $v)) {
                    $tmp = explode(':', $v);
                    $token = trim($tmp[1]);
                }
            }
            return $token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function postMessage($token, $data = array())
    {
        try {
            $postfields = array(
                'channel_id' => $data['channel_id'],
                'message' => $data['message'],
            );
            $url = sprintf('%s/teams/%s/channels/%s/posts/create', $this->_url , $data['team_id'], $data['channel_id']);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '. $token,
                 ));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
            $response = curl_exec($ch);
            return json_decode($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function uploadFile($token, $curlfile, $data = array())
    {
        try {
            $postfields = array(
                'channel_id' => $data['channel_id'],
                'files' => $curlfile,
            );
            $url = sprintf('%s/teams/%s/files/upload', $this->_url , $data['team_id']);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: multipart/form-data',
                    'Authorization: Bearer '. $token,
                 ));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            $response = curl_exec($ch);
            return json_decode($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function postFile($token, $file, $data = array())
    {
        try {
            $curlfile = curl_file_create($file);
            $response = $this->uploadFile($token, $curlfile, $data);
            $postfields = array(
                'channel_id' => $data['channel_id'],
                'file_ids' => array($response->file_infos[0]->id),
                'filenames' => array($response->file_infos[0]->name),
            );
            $url = sprintf('%s/teams/%s/channels/%s/posts/create', $this->_url , $data['team_id'], $data['channel_id']);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '. $token,
                 ));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
            $response = curl_exec($ch);
            return json_decode($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
