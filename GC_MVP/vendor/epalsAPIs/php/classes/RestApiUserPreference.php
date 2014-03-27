<?php

require_once('UserPreference.php');
require_once('RestApiKeyValue.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestApiAttributeInterface
 *
 * @author stevemulligan
 */
class RestApiUserPreference extends RestApiKeyValue 
{
    public $_model = 'UserPreference';
    
    
}

?>
