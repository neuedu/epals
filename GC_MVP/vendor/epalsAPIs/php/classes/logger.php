<?php 

  require_once("vendor/autoload.php");
  
  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;
  /*
    require_once("vendor/monolog/monolog/src/Monolog/Logger");
    require_once("vendor/monolog/monolog/src/Monolog/Handler/StreamHandler");
   * 
   */
    
final class Log {
    
  private static $logger;
  private static $log;
  
    private function __construct($name) {
    
        // create a log channel
        if(empty($name))
            $name = 'epals';
                
        $ini = parse_ini_file('config.ini',TRUE);
        $path = $ini["log"]["file"];
        $level = $ini["log"]["level"];
        
        self::$log = new Logger($name);
        $log = self::$log;
        $log->pushHandler(new StreamHandler($path, Logger::WARNING));
        
        
    }
  
    public static function getLogger($name=NULL){
        
        if(empty(self::$logger))
            self::$logger = new Log($name);
        return self::$log; 
    }
}


?>
