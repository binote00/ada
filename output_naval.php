<?
require_once('./jfv_inc_sessions.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];
//$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID or $Officier or $OfficierEMID)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_access.php');
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($OfficierEMID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($Officier)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier WHERE ID='$Officier'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	$mes="tableau";
	$query="SELECT g.*,l.Latitude,l.Longitude,l.Zone,l.Nom as Lieu_Nom FROM Ground_Cbt as g,Lieu as l
	WHERE g.Lieu=l.ID AND (l.Zone=6 OR g.Place=8 OR (g.Veh_a >4999 AND g.Veh_b >4999)) ORDER BY g.ID DESC LIMIT 100";
	$con=dbconnecti();
	$query2=mysqli_query($con,$query);
	mysqli_close($con);
	while($data2=mysqli_fetch_assoc($query2))
	{
		$Front_Lieu=GetFrontByCoord(0,$data2['Latitude'],$data2['Longitude']);
		if($Admin or $Front ==$Front_Lieu or $Front ==99)
		{
			$Dist=false;
			$Date=substr($data2['Date'],0,16);
			if($data2['Reg_a'])
				$Reg_a=$data2['Reg_a']."e Cie";
			else
				$Reg_a="EM";
			if(!$data2['Reg_b'] or $data2['Reg_b_ia'] ==1)
			{
				$Reg_b="EM";
				$Pays_loss=GetData("Cible","ID",$data2['Veh_b'],"Pays");
				$off_loss=0;
			}
			else
			{
				$Reg_b=$data2['Reg_b']."e Cie";
				$Pays_loss=GetData("Regiment","ID",$data2['Reg_b'],"Pays");
				$off_loss=GetData("Regiment","ID",$data2['Reg_b'],"Officier_ID");
			}
			if($data2['Pos_a'] ==30 or $data2['Reg_a_ia'] ==1)
			{
				$Reg_a="EM";
				$DB_Ra="Regiment_IA";
				$off_win="Inconnu";
			}
			else
			{
				$DB_Ra="Regiment";
				$off_win=GetData("Regiment","ID",$data2['Reg_a'],"Officier_ID");
			}
			$Pays_win=GetData($DB_Ra,"ID",$data2['Reg_a'],"Pays");
			if($off_win >0)
				$Officier_win=GetData("Officier","ID",$off_win,"Nom");
			else
				$Officier_win="Inconnu";
			if($off_loss >0)
				$Officier_loss=GetData("Officier","ID",$off_loss,"Nom");
			else
				$Officier_loss="Inconnu";
			$Lieu_Nom=$data2['Lieu_Nom'];
			if($data2['Veh_b'] <5000)$data2['Veh_b']=5000;
			if($OfficierEMID >0)
			{
				$Veh_Nbr_a=$data2['Veh_Nbr_a'];
				$Veh_Nbr_b=$data2['Veh_Nbr_b'];
				$Pos_b=GetPosGr($data2['Pos_b']);
				$Pos_a=GetPosGr($data2['Pos_a']);
				$Place=GetPlace($data2['Place'],1);
				$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win);
				$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss);
				$Dist=true;
			}
			elseif($off_win ==$Officier and $off_win >0)
			{
				$Veh_Nbr_a=$data2['Veh_Nbr_a'];
				$Veh_Nbr_b="";
				$Pos_a=GetPosGr($data2['Pos_a']);
				$Pos_b=GetPosGr($data2['Pos_b']);
				$Place=GetPlace($data2['Place'],1);
				$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win);
				$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss);
				$Dist=true;
			}
			elseif($off_loss ==$Officier and $off_loss >0)
			{
				$Veh_Nbr_a="";
				$Veh_Nbr_b=$data2['Veh_Nbr_b'];
				$Pos_b=GetPosGr($data2['Pos_b']);
				$Pos_a=GetPosGr($data2['Pos_a']);
				$Place=GetPlace($data2['Place'],1);
				$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win);
				$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss);
				$Dist=true;
			}
			elseif($PlayerID >0 or $Officier >0) //(($Pays_win == $country and $Pays_win >0) or ($Pays_loss == $country and $Pays_loss >0))
			{
				$Veh_Nbr_a="";
				$Veh_Nbr_b="";
				$Pos_a="Inconnue";
				$Pos_b="Inconnue";
				$Place="Inconnu";
				$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win);
				$Veh_b=GetVehiculeIcon($data2['Veh_b'], $Pays_loss);
			}
			else
			{
				$Veh_Nbr_a="";
				$Veh_Nbr_b="";
				$Pos_b="Inconnue";
				$Pos_a="Inconnue";
				$Place="Inconnu";
				$Veh_a="Inconnu";
				$Veh_b="Inconnu";
			}
			if($Dist and $Premium)
			{
				if($data2['Distance'] >5000 and !$Admin)
					$Dist_txt="+5000m";
				else
					$Dist_txt=$data2['Distance']."m";
			}
			else
				$Dist_txt="<div class='i-flex premium20'></div>";
			$liste.="<tr><td>".$Date."</td><td>".$Lieu_Nom."</td><td>".$Place."</td>
			<td><img src='".$Pays_win."20.gif'></td><td>".$Reg_a."</td><td>".$Pos_a."</td><td>".$Veh_Nbr_a." ".$Veh_a."</td>
			<td>".$Veh_Nbr_b." ".$Veh_b."</td><td>".$Pos_b."</td><td>".$Reg_b."</td><td><img src='".$Pays_loss."20.gif'></td>
			<td>".$data2['Kills']."</td><td>".$Dist_txt."</td></tr>";
		}
	}
	echo "<h1>Combats navals</h2>
	<p class='lead'>Ce tableau n'est pas une liste exhaustive de toutes les pertes en combat. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
	<thead><tr><th>Date</th><th>Lieu</th><th>Zone</th>
	<th>Pays</th><th>Unité</th><th>Position</th><th>Navires</th>
	<th>Navires</th><th>Position</th><th>Unité</th><th>Pays</th><th>Pertes</th><th>Distance</th></tr></thead>";
	echo $liste;
	echo "</table></div>";
	include_once('./index.php');
}
?>