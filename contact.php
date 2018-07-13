<?php
include_once('./jfv_include.inc.php');
include_once('./jfv_msg.inc.php');

$exp=Insec($_POST['email']);
$sujet=Insec($_POST['sujet']);
$message=Insec($_POST['msg']);

if(!IsValidEmail($exp))
{
	$mes = "Votre adresse e-mail n'est pas valide!";
	include_once('./index.php');
}
else
{

     $to      = 'admin@aubedesaigles.net';
     $headers = 'De: '. $exp . ' "\r\n" ' . phpversion();

     mail($to, $sujet, $exp.' : '.$message, $headers);
}
?>