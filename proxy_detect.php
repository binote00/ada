<?php
//
// Script php : Detection Proxy
// By : lemoussel - Aout 2009
//
// Copyright http://www.seoblackout.com
//
@set_time_limit(0);
@error_reporting(E_ALL | E_NOTICE);
 
function get_ip() {
  if($_SERVER) {
    if($_SERVER['HTTP_X_FORWARDED_FOR'])
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif($_SERVER['HTTP_CLIENT_IP'])
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    else
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  else {
    if(getenv('HTTP_X_FORWARDED_FOR'))
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif(getenv('HTTP_CLIENT_IP'))
      $ip = getenv('HTTP_CLIENT_IP');
    else
      $ip = getenv('REMOTE_ADDR');
  }
 
  return $ip;
}
 
function detect_proxy($myIP) {
   $scan_headers = array(
			'HTTP_VIA',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED',
			'HTTP_CLIENT_IP',
			'HTTP_FORWARDED_FOR_IP',
			'VIA',
			'X_FORWARDED_FOR',
			'FORWARDED_FOR',
			'X_FORWARDED',
			'FORWARDED',
			'CLIENT_IP',
			'FORWARDED_FOR_IP',
			'HTTP_PROXY_CONNECTION'
		);
 
   $flagProxy = false;
   $libProxy = 'No';
 
   foreach($scan_headers as $i)
			if($_SERVER[$i]) $flagProxy = true;
 
   if (    in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
        || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30))
      $flagProxy = true;
 
   // Proxy LookUp
   if ( $flagProxy == true &&
        isset($_SERVER['REMOTE_ADDR']) && 
        !empty($_SERVER['REMOTE_ADDR']) )
         // Transparent Proxy
         // REMOTE_ADDR = proxy IP
         // HTTP_X_FORWARDED_FOR = your IP   
         if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && 
              !empty($_SERVER['HTTP_X_FORWARDED_FOR']) &&
              $_SERVER['HTTP_X_FORWARDED_FOR'] == $myIP
            ) 
             $libProxy = 'Transparent Proxy';
               // Simple Anonymous Proxy            
              // REMOTE_ADDR = proxy IP
              // HTTP_X_FORWARDED_FOR = proxy IP
         else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && 
                   !empty($_SERVER['HTTP_X_FORWARDED_FOR']) &&
                   $_SERVER['HTTP_X_FORWARDED_FOR'] == $_SERVER['REMOTE_ADDR']
                 )
                 $libProxy = 'Simple Anonymous (Transparent) Proxy';
              // Distorting Anonymous Proxy            
              // REMOTE_ADDR = proxy IP
              // HTTP_X_FORWARDED_FOR = random IP address
              else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && 
                        !empty($_SERVER['HTTP_X_FORWARDED_FOR']) &&
                        $_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER['REMOTE_ADDR']
                      )
                      $libProxy = 'Distorting Anonymous (Transparent) Proxy';
                   // Anonymous Proxy
                   // HTTP_X_FORWARDED_FOR = not determined
                   // HTTP_CLIENT_IP = not determined
                   // HTTP_VIA = determined
                   else if ( $_SERVER['HTTP_X_FORWARDED_FOR'] == '' &&
                             $_SERVER['HTTP_CLIENT_IP'] == '' &&
                             !empty($_SERVER['HTTP_VIA'])
                           )
                           $libProxy = 'Anonymous Proxy';
                        // High Anonymous Proxy            
                        // REMOTE_ADDR = proxy IP
                        // HTTP_X_FORWARDED_FOR = not determined                    
                        else
                           $libProxy = 'High Anonymous Proxy';
 
   return $libProxy;
}
 
$ip = get_ip();
 
echo 'Proxy Server Detection<br>';
echo '=================<br><br>';
$typeProxy = detect_proxy($ip);
echo 'Use Proxy Server : '.$typeProxy.'<br>';
echo '<br>';
echo 'Brief IP Information'.'<br>';
echo '--------------------'.'<br>'; 
echo 'Your IP : '.$ip.'<br>';
echo 'Language : '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'<br>';
echo '<br>';
echo 'Detail IP Information'.'<br>';
echo '---------------------'.'<br>'; 
echo 'HTTP_ACCEPT : '.$_SERVER['HTTP_ACCEPT'].'<br>';
echo 'HTTP_ACCEPT_ENCODING : '.$_SERVER['HTTP_ACCEPT_ENCODING'].'<br>';
echo 'HTTP_ACCEPT_LANGUAGE : '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'<br>';
echo 'HTTP_ACCEPT_CHARSET : '.$_SERVER['HTTP_ACCEPT_CHARSET'].'<br>';
echo 'HTTP_CONNECTION : '.$_SERVER['HTTP_CONNECTION'].'<br>';
echo 'HTTP_HOST : '.$_SERVER['HTTP_HOST'].'<br>';
echo 'HTTP_KEEP_ALIVE : '.$_SERVER['HTTP_KEEP_ALIVE'].'<br>';
echo 'HTTP_USER_AGENT : '.$_SERVER['HTTP_USER_AGENT'].'<br>';
echo 'REMOTE_HOST : '.@gethostbyaddr($_SERVER['REMOTE_ADDR']).'<br>';
echo 'REMOTE_PORT : '.$_SERVER['REMOTE_PORT'].'<br>';
echo '<br>';
echo 'REMOTE_ADDR : '.$_SERVER['REMOTE_ADDR'].'<br>';
echo 'HTTP_VIA : '.$_SERVER['HTTP_VIA'].'<br>';
echo 'HTTP_X_FORWARDED_FOR : '.$_SERVER['HTTP_X_FORWARDED_FOR'].'<br>';
echo 'HTTP_PROXY_CONNECTION : '.$_SERVER['HTTP_PROXY_CONNECTION'].'<br>';
echo 'HTTP_CLIENT_IP : '.$_SERVER['HTTP_CLIENT_IP'].'<br>';
?>