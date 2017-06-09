<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','PhpCaptcha',array('file'=>'phpcaptcha/php-captcha.inc.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);
class CaptchaComponent extends Object{
    var $controller;
    function startup( &$controller ) {
        $this->controller = &$controller;
    }
    function image(){
        //error_reporting( E_ALL );
       $imagesPath = APP . 'vendors' . DS . 'phpcaptcha'.'/fonts/';
		//			$imagesPath = "";
		//		echo $imagesPath;
		//	exit();        
        $aFonts = array($imagesPath.'VeraBd.ttf',$imagesPath.'VeraIt.ttf', $imagesPath.'Vera.ttf');
        $oVisualCaptcha = new PhpCaptcha($aFonts, 200, 60);
        $oVisualCaptcha->UseColour(true);
        //$oVisualCaptcha->SetOwnerText('Source: '.FULL_BASE_URL);
        $oVisualCaptcha->SetNumChars(5);
        $oVisualCaptcha->Create();
    }
    function audio(){
        $oAudioCaptcha = new AudioPhpCaptcha('/usr/bin/flite', '/tmp/');
        $oAudioCaptcha->Create();
    }
    function check($userCode, $caseInsensitive = true){
        if ($caseInsensitive) {
            $userCode = strtoupper($userCode);
        }
        if (!empty($_SESSION[CAPTCHA_SESSION_ID]) && $userCode == $_SESSION[CAPTCHA_SESSION_ID]) {
            // clear to prevent re-use
            unset($_SESSION[CAPTCHA_SESSION_ID]);
            return true;
        }
        else return false;
    }
}
?>