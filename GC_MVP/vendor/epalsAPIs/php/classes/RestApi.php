<?php

require_once('EpalsSql.php');
require_once('RestSession.php');

class RestApi {

    public $_fileIn = 'php://input';
    public $app_id = null;
    public $app_key = null;

    function convertUrlQuery($query) { 
      $queryParts = explode('&', $query); 
    
      $params = array(); 
      foreach ($queryParts as $param) { 
        $item = explode('=', $param); 
        $params[$item[0]] = $item[1]; 
      } 
    
      return $params; 
    } 

    public function __construct($app_id = null, $app_key = null, $session_id = null)
    {
      if (isset($_SERVER['REQUEST_URI']))
      {
        $p = parse_url($_SERVER['REQUEST_URI']);
        if (isset($p['query'])) 
        {
          $q = $this->convertUrlQuery($p['query']);     
          if (isset($q['app_id'])) $this->app_id = $q['app_id'];
          if (isset($q['app_key'])) $this->app_key = $q['app_key'];
        }
      }
      if (isset($_SERVER['HTTP_EPALS_SESSION_ID'])) $this->session_id = $_SERVER['HTTP_EPALS_SESSION_ID'];
      if (!is_null($app_id)) $this->app_id = $app_id; 
      if (!is_null($app_key)) $this->app_key = $app_key; 
      if (!is_null($session_id)) $this->session_id = $session_id; 
    }

    function _build_error($error_message, $field_name = '', $type = 'field') {
        $res = array();
        $res['message'] = $error_message;
        $res['field_name'] = $field_name;
        $res['type'] = $type;

        return $res;
    }

    function validate($validation_rule, $request_object, $field_name, &$errors) {
        $func = $validation_rule["function"];
        $result = false;
        if ($this->$func($request_object, $field_name) === false) {
            array_push($errors, $this->_build_error($validation_rule["message"], $field_name));
        } else {
            $result = true;
        }

        return $result;
    }

    function field_numeric($request_object, $field_name) {
        if (!isset($request_object[$field_name]))
            return true;
        $v = $request_object[$field_name];
        if (preg_match('/\d+/', $v))
            return true;
        return false;
    }

    function field_required($request_object, $field_name) {
        if (isset($request_object[$field_name])) {
            if ($request_object[$field_name] != "") {
                return true;
            }
        }
        return false;
    }

    function field_user_id_exists($request_object, $field_name) {
        if (!isset($request_object[$field_name]))
            return true;
        $s = new EpalsSql();
        /* $a = preg_split('/\@/', $request_object[$field_name]);
          $username = $a[0];
          $domain = 0;
          if (count($a) == 2) $domain = $a[1];
          if ($domain == "epals.com")
          {
          $d = 0;
          } else {
          $k = "select id from nameSpaces where name='" . mysql_real_escape_string($domain) . "'";
          $d = intval($s->get($k));
          }
          $k = "select count(*) from users where user='". mysql_real_escape_string($username) . "' and domain = '" . mysql_real_escape_string($d) . "'";
         */
        $k = "select count(*) from users where row_id='" . mysql_real_escape_string($request_object[$field_name]) . "'";
        $c = intval($s->get($k));
        return ($c > 0);
    }

    function field_greater_than_0($request_object, $field_name) {
        if (!isset($request_object[$field_name]))
            return true;
        return (intval($request_object[$field_name]) > 0);
    }

    function user_id_from_username($account) {
        $s = new EpalsSql();
        $c = preg_split('/\@/', $account);
        if (isset($c[1])) {
            if ($c[1] == 'epals.com') {
                $domain_id = 0;
            } else {
                $domain_id = intval($s->get("select id from nameSpaces where name='" . mysql_real_escape_string($c[1]) . "'"));
                if ($domain_id == 0)
                    return 0; // domain not found
            }
        } else {
            $domain_id = 0;
        }

        $uid = intval($s->get("select row_id from users where user='" . mysql_real_escape_string($c[0]) . "' and domain='" . mysql_real_escape_string($domain_id) . "'"));
        if ($uid == 0)
            return 0;
        return $uid;
    }

    function _request_object(&$errors = array()) {
        //echo "FILEIN : " . $this->_fileIn . "\n";
        if ($this->_fileIn == 'php://input') {
            $raw_post_data = file_get_contents($this->_fileIn);
        } else {
            if (file_exists($this->_fileIn)) {
                $raw_post_data = file_get_contents($this->_fileIn);
            } else {
                $raw_post_data = '';
            }
        }
        
        $request_object = json_decode($raw_post_data, true);
        //$request_object = $_POST;  not pure rest but using form params still quite common

	if (($raw_post_data != "") && (is_null($request_object ))) { 
          return FALSE;
        }

        $this->prepareSession($request_object, $errors);

        return $request_object;
    }

    function prepareSession(&$request_object, &$errors = array())
    {
       $res = TRUE;
       if (isset($this->session_id)) {
            $this->website_session = new RestSession($this->session_id);
            $request_object['user_id'] = $this->website_session->getUserId();
            if (isset($request_object['account'])) {
              if ($this->website_session->isAdmin()) {
                #TODO: need a way to make sure this user belongs to this admin.  mysql tables dont have this yet, should probably go elsewhere.
                $request_object['user_id'] = $this->user_id_from_username($request_object['account']);
                $request_object['admin_id'] = $this->website_session->getUserId();
              } else {
                array_push($errors, $this->_build_error('Can not create an acount on behalf of other users unless the session belongs to an admin.', '', 'general'));
                $res = FALSE;
              } 
              unset($request_object['account']);
            }
       }
       return $res;
    }

    function _create($config_file, $request_object, &$response_object) {
      $errors = array();
      if ((!is_null($this->app_id)) && (!is_null($this->app_key)))
      { 
        if ($config_file != '') {
            $reflector = new ReflectionClass(get_class());
            $base_path = dirname($reflector->getFileName());
            $config = json_decode(file_get_contents($base_path . '/Config/' . $config_file), true);
            // need to validate params, make sure we have users etc..
            // need to also make sure they have permission to act on those users? need to get tennant/community from 3scale account id?

            if (isset($config["fields"])) {
                foreach ($config["fields"] as $f) {
                    $ks = array_keys($f);
                    $field_name_www = $ks[0];
                    $field = $f[$field_name_www];
                    $field_name_db = $field["field_name"];
                    $validations = $field["validation"];
                    foreach ($validations as $v) {
                        if ($this->validate($v, $request_object, $field_name_www, $errors)) {
                            if (isset($request_object[$field_name_www]))
                                $response_object->$field_name_db = $request_object[$field_name_www];
                        }
                    }
                    if (isset($request_object[$field_name_www]))
                        unset($request_object[$field_name_www]);
                }
            }

            if (isset($config["other_fields"])) {
                foreach ($config["other_fields"] as $f) {
                    if (isset($request_object[$f])) {
                        $response_object->$f = $request_object[$f];
                        unset($request_object[$f]);
                    }
                }
            }

            /* if (count($request_object) > 0)
              {
              array_push($errors, $this->_build_error("Unknown field(s) in request object.  Check the spelling of the following parameters: " . implode(", ", array_keys($request_object)), '', 'general'));
              } */
        } else {
            if (!is_null($request_object))
            {
                $response_object = $request_object;
            }
        }
      } else {
        array_push($errors, $this->_build_error('app_id or app_key not specified in the query string.  Please add ?app_id=APP_ID&app_key=APP_KEY to your request URL', '', 'general'));
        
      }

      return $errors;
    }

}

