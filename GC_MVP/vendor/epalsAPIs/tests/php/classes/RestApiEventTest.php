<?php

require_once('classes/RestApiEvent.php');

/**
 * Description of RestApiEventTest
 *
 * @author stevemulligan
 */

class RestApiEventTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->app_id = 'test';
        $this->app_key = '123';
        /*$random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_session_' . $random_string . '.json';
        $s = new RestApiSession($this->app_id, $this->app_key);
        $s->_fileIn = $json_test_temp_file;
        $request_object = array('username' => 'steve@epals.com', 'password' => 'password');
        file_put_contents($json_test_temp_file, json_encode($request_object));
        $res = $s->create_session();
        $this->teacher_session = $res['result']['session_id'];

        $request_object = array('username' => 'admindemo@epals.com', 'password' => 'adminwest');
        file_put_contents($json_test_temp_file, json_encode($request_object));
        $res = $s->create_session();

        $this->admin_session = $res['result']['session_id'];

        sleep(1);*/
    }

    public function testLoadNonExistantEvent()
    {
        $a = new RestApiEvent($this->app_id, $this->app_key);
        $p = $a->load_document('1c1db230-f3c2-4b93-8c2a-11dc431af40f');
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('fail', $p['status']);
        $this->assertArrayNotHasKey('result', $p);

        $p = $a->load_document('');
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('fail', $p['status']);
        $this->assertArrayNotHasKey('result', $p);
    }
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    public function testEventCreation()
    {
        $a = new RestApiEvent($this->app_id, $this->app_key);
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_event_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('type' => 'asd', 'callback' => 'callback', 'data' => $random_string);
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->create_document();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);
        
        return array('id' => $res['result']['id'], 'random_string' => $random_string);
    }
    
    /**
     * @depends testEventCreation
     */
    public function testEventLoad($test_result)
    {
        sleep(1);
        $a = new RestApiEvent($this->app_id, $this->app_key);
        $e = $a->load_document($test_result['id']);
        $this->assertArrayHasKey('status', $e);
        $this->assertEquals('ok', $e['status']);
        $this->assertArrayHasKey('result', $e);
        $this->assertEquals($test_result['random_string'], $e['result']['data']);
    }
    
}

?>
