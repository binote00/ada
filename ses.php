<?php
function open() 
{
	global $host,$user,$pass,$db,$connect;
  
	if($_SERVER['SERVER_NAME'] == 'www.cheratte.net' or $_SERVER['SERVER_NAME'] == 'cheratte.net')
	{
		$user="cherattedb1";
		$pass="09VdjyQp";
		$db="cherattedb1";
		//$connect = mysql_connect("mysql5-5.perso",$user,$pass,1);
		$connect = mysqli_connect('mysql5-5.perso', $user,$pass,$db);
	}
	else
	{
		$user="aubedesasec2";
		$pass="TYp2B9gq";
		$db="aubedesasec2";
		$connect = mysqli_connect('mysql51-42.pro', $user,$pass,$db);
	}
	$bdd = mysqli_select_db($connect,$db);
	
	return $bdd;
}

function close() 
{
	global $connect;
	$bdd = mysqli_close($connect);
	return $bdd;
}

function read($id) 
{
	global $connect;
    
	$sid = mysqli_real_escape_string($connect,$id);
	$sql = "SELECT PlayerID FROM Sessions WHERE ID ='$id'";
	$query = mysqli_query($connect,$sql) or die (mysqli_error($connect));			
	$data = mysqli_fetch_array($query);
		
	if(empty($data)) return FALSE;
	else return $data['PlayerID'];
}

function write($id, $data) 
{
	global $connect;
	
	$expire = intval(time() + 43200);
	$data = mysqli_real_escape_string($connect,$data); 
		
	$sql = "SELECT COUNT(ID) AS total FROM Sessions WHERE ID ='$id'";
		
	$query = mysqli_query($connect,$sql) or exit(mysqli_error($connect));
	$return = mysqli_fetch_array($query);
	if($return['total'] == 0)
	{
		$sql = "INSERT INTO Sessions VALUES('$id','$data','$expire')";
	}
	else
	{
		$sql = "UPDATE Sessions SET PlayerID='$data', Expire='$expire' WHERE ID ='$id'";
	}		
	$query = mysqli_query($connect,$sql) or exit(mysqli_error($connect));
		
	return $query;
}

function destroy($id) 
{
	global $connect;
	$sql = "DELETE FROM Sessions WHERE ID ='$id'";
	$query = mysqli_query($connect,$sql) or exit(mysqli_error($connect));
	return $query;
}

function gc()
{
	global $connect;
	$sql = "DELETE FROM Sessions WHERE Expire < ".time();			
	$query = mysqli_query($connect,$sql) or exit(mysqli_error($connect));
	return $query;
}

ini_set('session.save_handler', 'user');
session_set_save_handler ("open", "close", "read", "write", "destroy", "gc");
session_start();
?>