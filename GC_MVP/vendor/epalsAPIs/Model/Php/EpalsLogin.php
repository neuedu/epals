<?php

require_once('RestSession.php');
require_once('EpalsSql.php');
require_once('EpalsPassword.php');

class EpalsLogin 
{

  public $session_id = null;

  function EpalsLogin($session_id = null)
  {
    $this->session_id = $session_id;
  }

  function login($username, $password)
  {
    $es = null;
    $s = new EpalsSql();
    $a = preg_split('/\@/', $username);
    $u = $a[0];
    if (isset($a[1])) {
     $dn = $a[1];
     //TODO: Memcache this
     $d = intval($s->get("select id from nameSpaces where name = '" . mysql_real_escape_string($dn) . "'"));
    } else {
      $d= 0;
    }
      
    $res = $s->get_results("select role,row_id,pass,salt from users where user='" . mysql_real_escape_string($u) . "' and domain = '" . mysql_real_escape_string($d) . "'");
    if (count($res) > 0)
    {
       $p = new EpalsPassword($res[0]['salt']);
       if ($p->encrypt($password) == $res[0]['pass'])
       {
         $es = new RestSession();
         $es->setUserId($res[0]['row_id']);
         $es->setRole($res[0]['role']);
         $es->update();
       } 
    }

    return $es;
  }

  function logout()
  {

    $es = new RestSession($this->session_id);
    $es->expireSession();
  }

}
