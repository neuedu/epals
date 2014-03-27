<?php
/*
header('Content-type: text/plain');

$password = 'password'; 

echo ("salts: " . base64_encode('12345678') . "\n");

$p = new PassModule(base64_encode('12345678'));

$encrypted_data = $p->encrypt($password);
echo("ENC: $encrypted_data\n");
$decrypted = $p->decrypt($encrypted_data);

echo("dec: $decrypted\n");*/

class EpalsPassword {
function EpalsPassword($salt = null)
 {
$this->key = $this->hexstr('3722F2E1F392C3A3269FE05AAD78475C752883A65044AB3BAA79326137C9841312535A4B3722F2E1F392C33722F2E1F392C33722F2E1F392');

if (is_null($salt))
{
 $salt = '';
 for ($i = 0; $i < 8; $i++)
 {
  $salt .= chr(rand(0,255));
 }
 $this->salt = $salt;
} else {
 $this->salt = base64_decode($salt);
 }

}

function hexstr($hexstr) {
	  $hexstr = str_replace(' ', '', $hexstr);
	  $hexstr = str_replace('\x', '', $hexstr);
	  $retstr = pack('H*', $hexstr);
	  return $retstr;
	}

function encode($pass) {
return $pass;
$msg = $this->salt . "\0" . $pass . "\0";
return $msg;
}

	function encrypt($msg)
{
$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');

         mcrypt_generic_init($td, $this->key, $this->salt);
$msg = $this->encode($msg);
          $encrypted_data = mcrypt_generic($td, $msg);
          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);
$enc =  base64_encode($encrypted_data);
$dec =  mcrypt_decrypt ( MCRYPT_BLOWFISH, $this->key , $encrypted_data , MCRYPT_MODE_CBC , $this->salt  );

return $enc;
}

function decrypt($enc)
{
 $e2 = base64_decode($enc);
 $dec =  mcrypt_decrypt ( MCRYPT_BLOWFISH, $this->key , $e2 , MCRYPT_MODE_CBC , $this->salt  );
 $r = trim($this->decode($dec), "\0");
 
 return $r;
}

function get_salt()
{
  return base64_encode($this->salt);
}

function decode($data)
{
return $data;
return substr($data, 8);

}

}

?>
