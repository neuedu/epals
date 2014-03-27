<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{
    
    public function indexAction()
    {   
        
        return new ViewModel();
    }
    
    
    
    /**
     * return value of Cache by key
     * @param type $keyName
     * @return boolean
     */
    public function getCacheValue($keyName)
    {
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'plugins' => array(
                // Don't throw exceptions on cache errors
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
            )
        ));
        $result = $cache->getItem($keyName, $success);
        if($success){
            return $result ;
        }else{
            return false ;
        }
    }
    
    public function setCacheItem($key,$value)
    {
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'plugins' => array(
                // Don't throw exceptions on cache errors
                'exception_handler' => array(
                    'throw_exceptions' => false
                )
            )
        ));
        $cache->setItem($key, $value);
        echo 'set';
    }
    
    public function homeAction()
    {
        //$userAttribute = new \UserAttribute("test"."@epals.com");
        //$userAttribute ->add("key1", "value1");
        //$userAttribute = $userAttribute->get("cc");
        //var_dump($userAttribute);exit;
        
        session_start();
        $s='english.php';
        if($_SESSION['lan'])
        {
        $s=$_SESSION['lan'];
        }
        include_once $s; 
        return array('form'=> $tra);

        $key    = 'userProfile';

        
        $cacheInfo = $this->getCacheValue($key) ;

        if ($cacheInfo) {
            
            $viewInfo = array('message'=>'Last Visitor to our site : '.$cacheInfo);
            return new ViewModel($viewInfo);
            
        }else{
            $viewInfo = array('message'=>'Welcome new user!');
            return new ViewModel($viewInfo);
        }
        
        return new ViewModel($viewInfo);
    }
    
    //identify username and password
    public function loginAction()
    {
        
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        
        $post_data = $request->getPost();

        $username = $post_data['username'] ;
        $password = $post_data['password'] ;
        //template use: add @apals.com
        $account = $post_data['username']."@epals.com";
        $user = new \User();
        $user->setAccount($account);
        
        if($user->verifyPassword($password)){
            
            //save userinfo into session
            $_SESSION['username'] = $username;
            
            //save userinfo into cache
            //$this->setCacheItem("userProfile",$username);
            

            $userAttribute = new \UserAttribute($username."@epals.com");
            //$userAttribute ->add("key1", "value1");
            //$userAttribute = $userAttribute->get("cc");
            var_dump($userAttribute);
            $viewInfo = array('message'=>'Welcome you visit our site ! ');
            return new ViewModel($viewInfo);

        }else{
            $viewInfo = array('message'=>'login error');
            return new ViewModel($viewInfo);
            //跳转到错误页面
        }
       
    }
    
     function testAction()
    {
        session_start();
        $s='english.php';
        if($_SESSION['lan'])
        {
        $s=$_SESSION['lan'];
        }
        
        $request = $this->getRequest();
        $response = $this->getResponse();
        $post_data = $request->getPost();
        $lan = $post_data['language'];
        if($lan)
        {
            $_SESSION['lan']=$lan;
            include_once $lan; 
            return array('form'=> $tra);
        } 
        else
        {
            include_once $s; 
            return array('form'=> $tra);
        }      
    }
    
    
}
