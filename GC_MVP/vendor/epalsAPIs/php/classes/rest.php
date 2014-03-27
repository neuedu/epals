<?php

/**
 * This is part of a Demo PHP application for demonstrating the ePals Webservice APIs
 * 
 * class Rest - used to make generic rest calls to Policy Manager and SIS REST
 * 
 * @copyright ePals Inc, all rights reserved
 */
require_once('config.php');

class Rest {

    public function _getPMURL($path, $params) {
        $config = new Config();
        $server = $config->policyManager;
        $res = NULL;
        try {
            $res = $this->_getURL($server, $path, $params);
        } catch (Exception $e) {
            error_log("Couldn't getURL: $e)");
        }
        return $res;
    }

    public function _getSISURLSimple($path, $params) {
        $config = new Config();
        $server = $config->sisRestAPI;

        return $this->_getURLSimple($server, $path, $params);
    }

    public function _deleteSISURLSimple($path, $postfields) {
        $config = new Config();
        $server = $config->sisRestAPI;

        return $this->_deleteURLSimple($server, $path, $postfields);
    }

    public function _getSISURL($path, $params) {
        $config = new Config();
        $server = $config->sisRestAPI;
        try {
            return $this->_getURL($server, $path, $params);
        } catch (Exception $e) {
            error_log("In _getSISURL: $e");
            throw new Exception($e->getMessage());
        }
    }

    public function _postSISURL($path, $params, $postdata = false) {
        $config = new Config();
        $server = $config->sisRestAPI;

        return $this->_postURL($server, $path, $params, $postdata);
    }

    public function _putSISURL($path, $params, $postdata) {
        $config = new Config();
        $server = $config->sisRestAPI;

        return $this->_putURL($server, $path, $params, $postdata);
    }

    public function _getURL($server, $path, $params) {

        $url = $server . $path . "?" . (strlen($params) == 0 ? "" : $params . "&") . "format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //curl_setopt($ch, CURLOPT_PROXY, '10.3.17.11:7777');
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'sktest:sktest');

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_status == "200" or $http_status == "201") {
            return json_decode($result);
        } else {
            throw new Exception($result);
        }
    }

    public function _postURL($server, $path, $params, $postdata) {
        $url = $server . $path . "?" . (strlen($params) == 0 ? "" : $params . "&") . "format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        //var_dump($postdata);
        if (isset($postdata)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);   //utf8_encode($postdata)
        }
        
        //curl_setopt($ch, CURLOPT_PROXY, '10.3.17.11:7777');
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'sktest:sktest');

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //var_dump($ch);
        //var_dump($result);
        //var_dump($url);
        //var_dump($http_status);
        curl_close($ch);

        if ($http_status == "200" or $http_status == "201") {
            $json = json_decode($result);

            if (json_last_error() == JSON_ERROR_NONE)
                return $json;
            else
                return $result;
        } else
            throw new Exception($result);
    }

    public function _putURL($server, $path, $params, $postdata) {
        $url = $server . $path . "?" . (strlen($params) == 0 ? "" : $params . "&") . "format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);

        if (isset($postdata)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        
        //curl_setopt($ch, CURLOPT_PROXY, '10.3.17.11:7777');
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'sktest:sktest');

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status == "200" or $http_status == "201") {
            $jsonobj = json_decode($result);
            if ($jsonobj == null)
                return $result;
            else
                return $jsonobj;
        } else
            throw new Exception($result);
    }

    /*
     * Send post request on REST API
     */
    function _postURLWithContentType($url, $post, array $contentType, array $options = array()) {

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => $contentType,
            CURLOPT_POSTFIELDS => $post
        );

        $ch = curl_init();

        curl_setopt_array($ch, ($options + $defaults));

        if (!$result = curl_exec($ch)) {
            echo 'Error:' + $result;
            trigger_error(curl_error($ch));
        }

        curl_close($ch);


        return $result;
    }

    /*
     * Create and send get request on REST API
     * @param $url - absilute URL to post serv
     * @param $params - parameters to in include in get request
     * 
     * @return returns friendly cofirmation message
     */
    public function _getURLSimple($server, $path, $params) {

        $url = $server . $path . "?" . (strlen($params) == 0 ? "" : $params . "&") . "format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        $result = curl_exec($ch);
        $response = curl_getinfo($ch);

        if (!curl_errno($ch)) {
            if ($response['http_code'] == '404')
                $result = 'Unable to perform request. Please try again later!';
        }

        if ($response['http_code'] == '200') {

            $result = 'Request completed sucessfully';
        }

        curl_close($ch);

        return $result;
    }

    /*
     * Send delete request on REST API
     * @param $url: URL to make delete request
     * @param $post: POST Data
     * @param $contentType: HTML content type of post data
     * @param $options: Options to add in request
     * 
     * @return returns friendly cofirmation message
     * 
     */

    function _deleteURLSimple($server, $path, $postfields, array $contentType = array('Content-Type: application/json'), array $options = array()) {

        $url = $server . $path;

        $defaults = array(
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => $contentType,
            CURLOPT_POSTFIELDS => $postfields
        );

        $ch = curl_init();

        curl_setopt_array($ch, ($options + $defaults));

        $result = curl_exec($ch);
        $response = curl_getinfo($ch);

        if (!curl_errno($ch)) {
            if ($response['http_code'] == '404')
                $result = 'Unable to perform request. Please try again later!';
        }

        if ($response['http_code'] == '200') {

            $result = 'Request completed sucessfully';
        }

        curl_close($ch);

        return $result;
    }
}

?>
