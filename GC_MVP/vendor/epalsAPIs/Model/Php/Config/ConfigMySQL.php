<?php

if (preg_match("/^nginx/", php_uname("n"))) {
    define('MYSQL_WRITE_USER', 'epals');
    define('MYSQL_WRITE_PASS', 'something');
    define('MYSQL_WRITE_DB', 'epals2');

    define('MYSQL_READ_USER', 'epals');
    define('MYSQL_READ_PASS', 'something');
    define('MYSQL_READ_DB', 'epals2');
    
    define('MYSQL_WRITE_HOST', '10.122.60.51');
    define('MYSQL_READ_HOST',  '10.124.180.51');
}
else if (preg_match("/^api/", php_uname("n"))) {
    define('MYSQL_WRITE_USER', 'epals');
    define('MYSQL_WRITE_PASS', 'something');
    define('MYSQL_WRITE_DB', 'epals2');

    define('MYSQL_READ_USER', 'epals');
    define('MYSQL_READ_PASS', 'something');
    define('MYSQL_READ_DB', 'epals2');
    
    define('MYSQL_WRITE_HOST', '10.122.60.51');
    define('MYSQL_READ_HOST',  '10.124.180.51');
}
else if (preg_match("/^web..\.nginx/", php_uname("n"))) {
    define('MYSQL_WRITE_USER', 'epals');
    define('MYSQL_WRITE_PASS', 'something');
    define('MYSQL_WRITE_DB', 'epals2');

    define('MYSQL_READ_USER', 'epals');
    define('MYSQL_READ_PASS', 'something');
    define('MYSQL_READ_DB', 'epals2');
    
    define('MYSQL_WRITE_HOST', '10.122.60.51');
    define('MYSQL_READ_HOST',  '10.124.180.51');
}
else {
    define('MYSQL_WRITE_USER', 'root');
    define('MYSQL_WRITE_PASS', '3pals123!');
    define('MYSQL_WRITE_DB', 'epals2');

    define('MYSQL_READ_USER', 'root');
    define('MYSQL_READ_PASS', '3pals123!');
    define('MYSQL_READ_DB', 'epals2');

    define('MYSQL_WRITE_HOST', 'gc-dev-db01.c0f2oekk3smp.us-east-1.rds.amazonaws.com');
    define('MYSQL_READ_HOST', 'gc-dev-db01.c0f2oekk3smp.us-east-1.rds.amazonaws.com');
}

?>
