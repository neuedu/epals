<?php

if (preg_match("/^nginx/", php_uname("n"))) {

    # Settings for production servers

    define('EPALS_MONGO_DATABASE', 'epals');
    define('EPALS_MONGO_CONNECTION_STRING', 'mongodb://mongodb01.zva.epals.net');


    define('EPALS_MEMCACHE_HOST', 'gc-memcache01.zva.epals.net');
    //define('EPALS_MEMCACHE_HOST', 'localhost');
    define('EPALS_MEMCACHE_PORT', 11211);

    define('EPALS_SOLR_HOST', 'gc-solr01.zva.epals.net');
    define('EPALS_SOLR_PROJECT_HOST', 'gcpr-solr01.zva.epals.net');
    define('EPALS_SOLR_PORT', '8080');

    define('EPALS_CMS_NOCACHE', 0);
    define('EPALS_CMS_MODE', "API");
    define('EPALS_CMS_STAGING_CONTENT', 0);
    define('EPALS_CMS_LANGUAGE', 1033);

    define('EPALS_SOLR_SERVER', 'http://gc-solr01.zva.epals.net:8080/solr');
    define('EPALS_SOLR_PROJECT_SERVER', 'http://gcpr-solr01.zva.epals.net:8080/solr');
    define('EPALS_TAGGER_SERVER', 'http://cee01.zva.epals.net:8080/BasicESB/');
} 
else if (preg_match("/^api/", php_uname("n"))) {

    # Settings for production servers

    define('EPALS_MONGO_DATABASE', 'epals');
    define('EPALS_MONGO_CONNECTION_STRING', 'mongodb://mongodb01.zva.epals.net');


    define('EPALS_MEMCACHE_HOST', 'gc-memcache01.zva.epals.net');
    //define('EPALS_MEMCACHE_HOST', 'localhost');
    define('EPALS_MEMCACHE_PORT', 11211);

    define('EPALS_SOLR_HOST', 'gc-solr01.zva.epals.net');
    define('EPALS_SOLR_PROJECT_HOST', 'gcpr-solr01.zva.epals.net');
    define('EPALS_SOLR_PORT', '8080');

    define('EPALS_CMS_NOCACHE', 0);
    define('EPALS_CMS_MODE', "API");
    define('EPALS_CMS_STAGING_CONTENT', 0);
    define('EPALS_CMS_LANGUAGE', 1033);

    define('EPALS_SOLR_SERVER', 'http://gc-solr01.zva.epals.net:8080/solr');
    define('EPALS_SOLR_PROJECT_SERVER', 'http://gcpr-solr01.zva.epals.net:8080/solr');
    define('EPALS_TAGGER_SERVER', 'http://cee01.zva.epals.net:8080/BasicESB/');
}

else if (preg_match("/^web..\.nginx/", php_uname("n"))) {

    # Settings for production servers

    define('EPALS_MONGO_DATABASE', 'epals');
    define('EPALS_MONGO_CONNECTION_STRING', 'mongodb://mongodb01.zva.epals.net');


    define('EPALS_MEMCACHE_HOST', 'gc-memcache01.zva.epals.net');
    //define('EPALS_MEMCACHE_HOST', 'localhost');
    define('EPALS_MEMCACHE_PORT', 11211);

    define('EPALS_SOLR_HOST', 'gc-solr01.zva.epals.net');
    define('EPALS_SOLR_PROJECT_HOST', 'solr01.gc01.dev.ec2.epals.net');
    define('EPALS_SOLR_PORT', '8080');

    define('EPALS_CMS_NOCACHE', 0);
    define('EPALS_CMS_MODE', "API");
    define('EPALS_CMS_STAGING_CONTENT', 0);
    define('EPALS_CMS_LANGUAGE', 1033);

    define('EPALS_SOLR_SERVER', 'http://gc-solr01.zva.epals.net:8080/solr');
    define('EPALS_SOLR_PROJECT_SERVER', 'http://gcpr-solr01.zva.epals.net:8080/solr');
    define('EPALS_TAGGER_SERVER', 'http://cee01.zva.epals.net:8080/BasicESB/');
}

else {
error_log("loaded dev setings");
    # Settings for development / testing
    # Settings for development / testing

    define('EPALS_MONGO_DATABASE', 'epals');
    define('EPALS_MONGO_CONNECTION_STRING', 'mongodb://mongo01.gc01.dev.ec2.epals.net');

    define('EPALS_MEMCACHE_HOST', 'memcache01.gc01.dev.ec2.epals.net');
    define('EPALS_MEMCACHE_PORT', 11211);

    define('EPALS_SOLR_HOST', 'solr01.gc01.dev.ec2.epals.net');
    define('EPALS_SOLR_PROJECT_HOST', 'solr01.gc01.dev.ec2.epals.net');
    define('EPALS_SOLR_PORT', '8080');

    define('EPALS_CMS_NOCACHE', 0);
    define('EPALS_CMS_MODE', "API");
    define('EPALS_CMS_STAGING_CONTENT', 0);
    define('EPALS_CMS_LANGUAGE', 1033);

    define('EPALS_SOLR_SERVER', 'http://solr01.gc01.dev.ec2.epals.net:8080/solr');
    define('EPALS_SOLR_PROJECT_SERVER', 'http://solr01.gc01.dev.ec2.epals.net:8080/solr');
    define('EPALS_TAGGER_SERVER', 'http://cee01.zva.epals.net:8080/BasicESB/');
}
?>
