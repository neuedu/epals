<?php
 
require_once('classes/RestApiProfile.php');
require_once('classes/RestApiSession.php');

class RestApiProfileTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        $this->app_id = 'test';
        $this->app_key = '123';
        $random_string = $this->generateRandomString();
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

        unlink($json_test_temp_file);

        sleep(1);
    }

    public function testCreateTeacherSession() {
       $random_string = $this->generateRandomString();
       $json_test_temp_file = 'create_session_' . $random_string . '.json';
       $s = new RestApiSession($this->app_id, $this->app_key);
       $s->_fileIn = $json_test_temp_file;
       $request_object = array('username' => 'steve@epals.com', 'password' => 'password');
       file_put_contents($json_test_temp_file, json_encode($request_object));
       $res = $s->create_session();
       $this->assertArrayHasKey('result', $res);
       $this->assertArrayHasKey('session_id', $res['result']);
       unlink($json_test_temp_file);
    }

    public function testLoadNonExistantProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->teacher_session);
        $p = $a->profile_by_id('1c1db230-f3c2-4b93-8c2a-11dc431af40f');
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('fail', $p['status']);
        $this->assertArrayNotHasKey('result', $p);

        $p = $a->profile_by_id('');
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('fail', $p['status']);
        $this->assertArrayNotHasKey('result', $p);
    }

    public function testLoadApprovedProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->teacher_session);
        $p = $a->profile_by_id('206799c5-83b8-489b-adac-a785b2127f52');
        $this->assertArrayHasKey('id', $p['result']);
    }

    public function testLoadPendingProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->teacher_session);
        $p = $a->profile_by_id('c4605d44-6f6e-40ac-996f-beb2d2e29bee');
        $this->assertArrayHasKey('id', $p['result']);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function testAdminUpdateProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);
        $editable_profile_id = 'c1dd826d-16aa-47cc-95ac-594e77d57a38';
        $p = $a->profile_by_id($editable_profile_id);
        $this->assertArrayHasKey('id', $p['result']);

        $random_string = $this->generateRandomString();
        $p["name"] = $random_string;

        $json_test_temp_file = 'edit_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('name' => $random_string);
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_admin_edit($editable_profile_id);
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        unlink($json_test_temp_file);

        $p = $a->profile_by_id($editable_profile_id);
        $this->assertArrayHasKey('id', $p['result']);
        $this->assertArrayHasKey('name', $p['result']);
        $this->assertEquals($random_string, $p['result']['name']);
    }

    public function testCreateAndDeleteProfile() {
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_profile_' . $random_string . '.json';
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->teacher_session);
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('name' => $random_string, 'teacher_name' => $random_string, 'school_name' => 'test');
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_create();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);

        $p = $a->profile_by_id($res['result']['id']);
        $this->assertArrayHasKey('id', $p['result']);
        $this->assertArrayHasKey('name', $p['result']);
        $this->assertEquals($random_string, $p['result']['name']);

        $res2 = $a->profile_delete($p['result']['id']);
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);

        $p = $a->profile_by_id($res['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('fail', $p['status']);
        $this->assertArrayNotHasKey('result', $p);
    }

    public function testApproveProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('user_id' => 13862818, 'name' => $random_string, 'teacher_name' => $random_string, 'school_name' => 'test');
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_create();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);

        $p = $a->profile_approve($res['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayHasKey('id', $p['result']);

        $p = $a->profile_by_id($p['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayNotHasKey('status', $p['result']); // approve profile will NOT have status field, FIXME: Should really set the type in the response object for this
    }

    public function testHoldProfile() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('user_id' => 13862818, 'name' => $random_string, 'teacher_name' => $random_string, 'school_name' => 'test');
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_create();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);

        $request_object = array('holding_comments' => $random_string);
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $p = $a->profile_hold($res['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        unlink($json_test_temp_file);

        $p = $a->profile_by_id($p['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayHasKey('status', $p['result']);
        $this->assertEquals(4, $p['result']['status']);
        $this->assertArrayHasKey('holding_comments', $p['result']);
        $this->assertEquals($random_string, $p['result']['holding_comments']);
    }

    public function testUserEditProfile() {
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_profile_' . $random_string . '.json';
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->teacher_session);
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('name' => $random_string, 'teacher_name' => $random_string, 'school_name' => 'test');
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_create();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);

        $b = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);

        $p = $b->profile_approve($res['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayHasKey('id', $p['result']);

        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'edit_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('name' => $random_string, 'teacher_name' => $random_string);
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $e = $a->profile_edit($p['result']['id']);
        $this->assertArrayHasKey('status', $e);
        $this->assertEquals('ok', $e['status']);
        $this->assertArrayHasKey('result', $e);
        $this->assertArrayHasKey('id', $e['result']);
        unlink($json_test_temp_file);

        $p = $a->profile_by_id($e['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayHasKey('result', $p);
        $this->assertArrayHasKey('class', $p['result']);
        $this->assertEquals($random_string, $p['result']['name']);
        $this->assertEquals('TwoPhase\\ProfilePending', $p['result']['class']);

        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'edit_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('name' => $random_string, 'teacher_name' => $random_string);
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $e = $a->profile_edit($p['result']['id']);
        $this->assertArrayHasKey('status', $e);
        $this->assertEquals('ok', $e['status']);
        $this->assertArrayHasKey('result', $e);
        $this->assertArrayHasKey('id', $e['result']);
        unlink($json_test_temp_file);

        $p = $a->profile_by_id($e['result']['id']);
        $this->assertArrayHasKey('status', $p);
        $this->assertEquals('ok', $p['status']);
        $this->assertArrayHasKey('result', $p);
        $this->assertArrayHasKey('class', $p['result']);
        $this->assertEquals($random_string, $p['result']['name']);
        $this->assertEquals('TwoPhase\\ProfilePending', $p['result']['class']);
    }

    function testLoadProfilesForUser() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);
        $res = $a->profiles_by_account('steve@epals.com');
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertGreaterThan(1, count($res['result']));
    }

    function testProfilesPending() {
        $a = new RestApiProfile($this->app_id, $this->app_key, $this->admin_session);
        $random_string = $this->generateRandomString();
        $json_test_temp_file = 'create_profile_' . $random_string . '.json';
        $a->_fileIn = $json_test_temp_file;
        $request_object = array('user_id' => 13862818, 'name' => $random_string, 'teacher_name' => $random_string, 'school_name' => 'test');
        file_put_contents($json_test_temp_file, json_encode($request_object));

        $res = $a->profile_create();
        $this->assertArrayHasKey('status', $res);
        $this->assertEquals('ok', $res['status']);
        $this->assertArrayHasKey('result', $res);
        $this->assertArrayHasKey('id', $res['result']);
        unlink($json_test_temp_file);

        $pp = $a->profiles_pending(10, 0);
        $this->assertArrayHasKey('status', $pp);
        $this->assertEquals('ok', $pp['status']);
        $this->assertEquals(10, count($pp['result']));
        $matched = 0;
        foreach ($pp['result'] as $id) {
            if ($id == $res['result']['id']) {
                $matched++;
            }
        }
        $this->assertEquals(1, $matched);
    }

}
