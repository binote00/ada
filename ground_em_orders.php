<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID)
	{
		include_once('./jfv_include.inc.php');
		$Officier=Insec($_POST['Officier']);
		$country=Insec($_POST['Pays']);
		$Front=Insec($_POST['Front']);
		$Atk1=Insec($_POST['patk1']);
		$Atk2=Insec($_POST['patk2']);
		$Def=Insec($_POST['pdef']);	
		if($Atk1 !=999999)
			SetDoubleData("Pays","lieu_atk1",$Atk1,"Pays_ID",$country,"Front",$Front);
		if($Atk2 !=999999)
			SetDoubleData("Pays","lieu_atk2",$Atk2,"Pays_ID",$country,"Front",$Front);
		if($Def !=999999)
			SetDoubleData("Pays","lieu_def",$Def,"Pays_ID",$country,"Front",$Front);
		if(GetData("Officier_em","ID",$OfficierEMID,"Orders") ==0)
		{
			UpdateData("Officier_em","Avancement",10,"ID",$OfficierEMID);
			UpdateData("Officier_em","Note",2,"ID",$OfficierEMID);
			SetData("Officier_em","Orders",1,"ID",$OfficierEMID);
		}
		else
			UpdateData("Officier_em","Avancement",1,"ID",$OfficierEMID);
		$mes="Vos ordres ont été transmis.";
	}
	$titre="Ordres à l'Armée";
	include_once('./default.php');
}
?>