<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Provisioning for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Provisioning\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
require_once ('class.phpmailer.php');
require_once ('module.php');
$apiDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/epalsAPIs/php/classes/';
require_once ($apiDir . 'SessionBroker.php');
require_once ($apiDir . 'Session.php');
require_once ($apiDir . 'CountryLookup.php');
require_once ($apiDir . 'SchoolTypeLookup.php');
require_once ($apiDir . 'GradeLookup.php');
require_once ($apiDir . 'AgeRangeLookup.php');
require_once ($apiDir . 'RoleLookup.php');

//use EPalsAPIs\User

class ProvisioningController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
           
    /*
     * ajax save user base info 
     */
    public function ajaxPostBaseInfoAction(){
        
        $post_data = $this->getRequest()->getPost();
        $response = $this->getResponse();
        //do ...
        $role = $post_data['role'];
        $step = $post_data['currentStep'] - 1;
        $err = '';
        $re = new \Register();
        switch ($role) {
            case 'teacher':
                $err = $re->add_teacher($post_data);
                break;
            case 'teacher_homeschool':
                $err = $re->add_teacher($post_data);
                break;
            case 'student':
                $err = $re->add_student($post_data);
                break;
            case 'parent':
                $err = $re->add_parent($post_data);
                break;
            default:
                echo "the role is not available";
        }

        if(!$err)
        {
            $err = $post_data;
        }
        
        $response->setContent(\Zend\Json\Json::encode($err));
        return $response;
    }

    public function ajaxregistAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $role = $post_data['role'];
            $step = $post_data['currentStep'] - 1;
            $err = '';
            $re = new \Register();
            switch ($role) {
                case 'teacher':
                    $err = $re->add_teacher_attr($post_data);
                    break;
                case 'teacher_homeschool':
                    $err = $re->add_hsteacher_attr($post_data);
                    break;
                case 'student':
                    $err = $re->add_student_attr($post_data);
                    break;
                case 'parent':
                    $err = $re->par_add_attr($post_data);
                    break;
                default:
                    echo "the role is not available";
            }
            if(!$err)
            {
                $err = $post_data;
            }
 
            $response->setContent(\Zend\Json\Json::encode($err));
        }
        return $response;
    }
    
    /**
     * ajax return roles
     * @return \Zend\View\Model\ViewModel
     * teacher : Teacher
     * teacher_homeschool : Home-School Teacher
     * student : Student
     * parent : Parent / Guardian
     * mentor : Mentor
     */
    public function ajaxLoadRolesAction() {
        $response = $this->getResponse();

        // get roles
        $session_broker = new \Epals\SessionBroker();
        $session = $session_broker->login(NULL, NULL);
        $lookup_table = new \Epals\RoleLookup($session);
        $roles = $lookup_table->getAllRoles();
        
        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($roles));
        return $response;
    }

    /**
     * ajax return countrys
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadCountrysAction() {
        $response = $this->getResponse();

        // get roles
        $session_broker = new \Epals\SessionBroker();
        $session = $session_broker->login(NULL, NULL);
        $lookup_table = new \Epals\CountryLookup($session);
        $countrys = $lookup_table->getAllCountries();
        
        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($countrys));
        return $response;
    }

    /**
     * ajax return SchoolType
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadSchoolTypeAction() {
        $response = $this->getResponse();

        // get roles
        $session_broker = new \Epals\SessionBroker();
        $session = $session_broker->login(NULL, NULL);
        $lookup_table = new \Epals\SchoolTypeLookup($session);
        $school_types = $lookup_table->getAllSchoolTypes();
        
        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($school_types));
        return $response;
    }

    /**
     * ajax return AgeRange
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadAgeRangeAction() {
        $response = $this->getResponse();

        // get roles
        $session_broker = new \Epals\SessionBroker();
        $session = $session_broker->login(NULL, NULL);
        $lookup_table = new \Epals\AgeRangeLookup($session);
        $age_ranges = $lookup_table->getAllAgeRanges();
        
        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($age_ranges));
        return $response;
    }

    /**
     * ajax return Grade
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadGradeAction() {
        $response = $this->getResponse();

        // get roles
        $session_broker = new \Epals\SessionBroker();
        $session = $session_broker->login(NULL, NULL);
        $lookup_table = new \Epals\GradeLookup($session);
        $grades = $lookup_table->getAllGrades();
        
        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($grades));
        return $response;
    }
    
    //if user exists
    public function ifUserNotExistAction()
    {
        $username = $_GET['username'];
        $response = $this->getResponse();
        
        $email = $username.'@epals.com';

        $flag = new \User();
        $result = $flag->userExists($email);
        
        
        $response->setContent(\Zend\Json\Json::encode(!$result));
        return $response;    
    }

    public function DashboardAction()
    {
        $username = $_GET['username'];
        $userAttribute = new \UserAttribute($username."@epals.com");
        var_dump($userAttribute);
    }
    
    public function mailAction($email)
    {
        require 'class.phpmailer.php';

        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'jswan';                            // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'from@example.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('josh@example.net', 'Josh Adams');  // Add a recipient
        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }

        echo 'Message has been sent';
    }
}