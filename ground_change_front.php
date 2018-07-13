<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
$OfficierID=$_SESSION['Officier'];
if(isset($_SESSION['AccountID']) AND ($OfficierEMID >0 or $OfficierID >0))
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Regiment=Insec($_POST['Reg']);
	$Front_Ori=Insec($_POST['Front']);
	$Lieu_Ori=Insec($_POST['Transit']);
	if($Lieu_Ori ==2 or $Lieu_Ori ==603)
	{
		if($Front_Ori ==4)
			$Front_Dest=10;
		else
			$Front_Dest=4;
	}
	elseif($Lieu_Ori ==189 or $Lieu_Ori ==198 or $Lieu_Ori ==201 or $Lieu_Ori ==344 or $Lieu_Ori ==586)
	{
		if($Front_Ori ==2)
			$Front_Dest=10;
		else
			$Front_Dest=2;
	}
	elseif($Lieu_Ori ==199 or $Lieu_Ori ==218)
	{
		if($Front_Ori ==1)
			$Front_Dest=10;
		else
			$Front_Dest=1;
	}
	elseif($Lieu_Ori ==614)
	{
		if($Front_Ori ==4)
			$Front_Dest=5;
		else
			$Front_Dest=4;
	}
	elseif($Lieu_Ori ==615 or $Lieu_Ori ==619 or $Lieu_Ori ==621 or $Lieu_Ori ==967 or $Lieu_Ori ==1280)
	{
		if($Front_Ori ==4)
			$Front_Dest=1;
		else
			$Front_Dest=4;
	}
	elseif($Lieu_Ori ==704 or $Lieu_Ori ==898 or $Lieu_Ori ==2079)
	{
		if($Front_Ori ==5)
			$Front_Dest=10;
		else
			$Front_Dest=5;
	}
	elseif($Lieu_Ori ==709)
	{
		if($Front_Ori ==2)
			$Front_Dest=1;
		else
			$Front_Dest=2;
	}
	elseif($Lieu_Ori ==1896)
	{
		if($Front_Ori ==2)
			$Front_Dest=3;
		else
			$Front_Dest=2;
	}
	elseif($Lieu_Ori ==1567 or $Lieu_Ori ==1577)
		$Front_Dest=10;
	elseif($Lieu_Ori ==2079)
		$Front_Dest=3;
	if($Front_Dest >0)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_msg.inc.php');
		$GHQ_Off=GetData("GHQ","Pays",$country,"Planificateur");
		if($OfficierEMID >0)
		{
			$Off_ID=$OfficierEMID;
			$exp_em=1;
		}
		else
		{
			$Off_ID=$OfficierID;
			$exp_em=2;
		}
		SendMsgOff($GHQ_Off,$Off_ID,$Msg,"Demande de changement de front",$exp_em,1);
		SetData("Regiment_IA","Dem_Front",$Front_Dest,"ID",$Regiment);
		if($Front_Dest ==10)$Front_Dest=0;
		$Msg="La ".$Regiment."e Cie demande son transfert du front ".GetFront($Front_Ori)." vers le front <b>".GetFront($Front_Dest)."</b>";
		$msg_txt="La demande de changement de front de la ".$Regiment."e Cie demande son transfert du front ".GetFront($Front_Ori)." vers le front ".GetFront($Front_Dest)." a été envoyée!";
	}
	else
		$msg_txt="Erreur!";
	echo "<h1>Changement de front</h1>".$msg_txt."<br><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>";
}
?>