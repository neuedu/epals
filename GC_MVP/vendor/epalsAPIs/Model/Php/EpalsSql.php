<?php

require_once("Config/ConfigMySQL.php");

class EpalsSql
{
        function __construct($db = MYSQL_READ_DB) 
        {

                $this->link = mysql_connect(MYSQL_READ_HOST, MYSQL_READ_USER, MYSQL_READ_PASS, TRUE) or die('Could not connect: ' . mysql_error());

                mysql_select_db($db) or die('Could not select database');
		//mysql_set_charset('utf8', $this->link); 
        }

	
		function get($query) {
			if (!$result = mysql_query($query))
			{
				throw new Exception('Query failed: ' . $query . ' ' . mysql_error());
			}
		
			$res = mysql_fetch_array($result);
			
			return $res[0];
		}
	
        function get_results($query) {
                if (!$result = mysql_query($query))
				{
					throw new Exception('Query failed: ' . $query . ' ' . mysql_error());
				}

                $ret = array();

                while ($res = mysql_fetch_array($result)) {
                         array_push ($ret, $res);
                }

                return $ret;
        }

        function field_from_results($query, $field) {
			if (!$result = mysql_query($query))
			{
				throw new Exception('Query failed: ' . $query . ' ' . $field . ' ' .mysql_error());
			}

                $ret = array();

                while ($res = mysql_fetch_array($result)) {
                         array_push ($ret, $res['id']);
                }

                return $ret;
        }

	function rows()
	{
		return mysql_affected_rows();
	}

        function query($query) {
                if (!mysql_query($query))
				{
					throw new Exception('Query failed: ' . $query . ' : ' . mysql_error());
				}
        }
}

?>
