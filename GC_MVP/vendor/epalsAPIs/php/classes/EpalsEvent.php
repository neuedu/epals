<?php
namespace Epals {
require_once('ApiEntityObject.php');

/**
 * Description of Event
 *
 * @author stevemulligan
 */

class EpalsEvent extends ApiEntityObject {

    function setType($event_type) {
        $this->data['type'] = $event_type;
    }
    
    function setData($event_data) {
        $this->data['data'] = $event_data;
    }
    
    function setCallback($event_callback) {
        $this->data['callback'] = $event_callback;
    }
    
    function getType() {
        return isset($this->data['type']) ? $this->data['type'] : null;
    }
    
    function getData() {
        return isset($this->data['data']) ? $this->data['data'] : null;
    }
    
    function getCallback() {
        return isset($this->data['callback']) ? $this->data['callback'] : null;
    }

}
}
