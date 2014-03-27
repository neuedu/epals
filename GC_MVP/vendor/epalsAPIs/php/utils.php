<?php

function query_get($key, $default=NULL) {
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else if (isset($_POST[$key])) {
        return $_POST[$key];
    } else {
        return $default;
    }
}

function query_exists($key) {
    return query_get($key) != NULL;
}

function query_exists_all($keys) {
    foreach ($keys as $key) {
        if (!query_exists($key)) {
            return false;
        }
    }
    return true;
}

function json_response($code, $data, $message=NULL) {
    if ($code == 200) {
        $status = "ok";
    } else {
        $status = "error";
    }
    $response = json_encode(array(
        "status" => $status,
        "code" => $code,
        "message" => $message,
        "data" => $data
    ));
    if (query_exists("callback")) {
        return query_get("callback")."(".$response.")";
    }
    return $response;
}

function valid_session($sessionid=NULL) {
    if (!$sessionid) {
        $sessionid = query_get("sessionid");
    }
    if (!$sessionid) return FALSE;
    try {
        session_id($sessionid);
        session_start();
    } catch (Exception $e) { }
    return isset($_SESSION["username"]);
}

function get_username($sessionid=NULL) {
    if ($sessionid) {
        $sessionid = query_get("sessionid");
        session_id($sessionid);
        session_start();
    }
    if (isset($_SESSION["username"])) {
        return $_SESSION["username"];
    }
    return NULL;
}

function md5_pwd($pwd) {
    return md5(strtolower(trim($pwd)));
}


/*
 * Stolen from the php man page: 
 * http://php.net/manual/en/function.microtime.php
 * 
 * but modified a bit
 */

function get_execution_time() {
    static $microtime_start = null;
    if($microtime_start === null)
    {
        $microtime_start = microtime(true);
        return 0.0; 
    }    
    return microtime(true) - $microtime_start; 
}

function debugLog($msg, $dump = true) {
    if (preg_match("/^nginx/", php_uname("n"))) {
        //return;
    } else {
        if (query_exists("debugOn")) {
            if ($dump) {
                ob_start();
                var_dump($msg);
                $contents = ob_get_contents();
                ob_end_clean();
                error_log($contents);
            } else {
                print $msg;
            }
        }
    }
}

function epals_post_async ($url, $params) {
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 30);

    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "User-Agent: Shaz 0.1a";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

function mssql_escape($data) {
    if(is_numeric($data))
        return $data;
    $unpacked = unpack('H*hex', $data);
    return '0x' . $unpacked['hex'];
}

 function check_email_address($email) {
 
     $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
     // Run the preg_match() function on regex against the email address
     if (preg_match($regex, $email)) 
     { 
        return true;
     }
     else { 
         return false;   
     }

 }


?>
