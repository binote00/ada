<?php
function GetRatio($Pilote,$Missions=0)
{
	$Ratio=0;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Raids_Bomb,Raids_Bomb_Nuit,Dive,Abattu FROM Pilote WHERE ID='$Pilote'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Raids_Bomb=$data['Raids_Bomb'];
			$Raids_Bomb_Nuit=$data['Raids_Bomb_Nuit'];
			$Dive=$data['Dive'];
			$Abattu=$data['Abattu'];
		}
	}
	mysqli_free_result($result);
	unset($data);	
	if(!$Missions)
	{
		$con=dbconnecti();
		$Mission_recce=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE Joueur='$Pilote'"),0);
		$Mission_escorte=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte WHERE Joueur='$Pilote'"),0);
		$Mission_patrouille=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille WHERE Joueur='$Pilote'"),0);
		$Mission_sauvetage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage WHERE PlayerID='$Pilote'"),0);
		$Mission_ravit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements WHERE PlayerID='$Pilote'"),0);
		$Mission_paras=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Parachutages WHERE Joueur='$Pilote'"),0);
		mysqli_close($con);
		$Missions=$Mission_recce+$Mission_escorte+$Mission_patrouille+$Mission_sauvetage+$Raids_Bomb+$Raids_Bomb_Nuit+$Dive+$Mission_ravit+$Mission_paras;
	}
	if($Missions >0)
	{
		$con=dbconnecti(4);
		$Abattu2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$Pilote'"),0);
		mysqli_close($con);
		if($Abattu2 >$Abattu)$Abattu=$Abattu2;
		$Ratio=round($Abattu/$Missions,2);
	}
	$array=array($Ratio,$Missions);
	return $array;
}

function GetMissions($Pilote)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Raids_Bomb,Raids_Bomb_Nuit,Dive FROM Pilote WHERE ID='$Pilote'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Raids_Bomb=$data['Raids_Bomb'];
			$Raids_Bomb_Nuit=$data['Raids_Bomb_Nuit'];
			$Dive=$data['Dive'];
		}
	}
	mysqli_free_result($result);
	unset($data);
	$con=dbconnecti();
	$Mission_recce=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE Joueur='$Pilote'"),0);
	$Mission_escorte=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte WHERE Joueur='$Pilote'"),0);
	$Mission_patrouille=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille WHERE Joueur='$Pilote'"),0);
	$Mission_sauvetage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage WHERE PlayerID='$Pilote'"),0);
	$Mission_ravit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements WHERE PlayerID='$Pilote'"),0);
	$Mission_para=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Parachutages WHERE Joueur='$Pilote'"),0);
	mysqli_close($con);
	$con=dbconnecti();
	$Atk_res=mysqli_query($con,"SELECT COUNT(*) FROM Attaque WHERE Joueur='$Pilote' AND Type >0 GROUP BY Lieu,Date");
	if(mysqli_num_rows($Atk_res))
	{
		$Atk=mysqli_result($Atk_res,0);
	}
	else
		$Atk=0;
	mysqli_close($con);
	$Missions=$Mission_recce+$Mission_escorte+$Mission_patrouille+$Mission_sauvetage+$Mission_ravit+$Mission_para+$Raids_Bomb+$Raids_Bomb_Nuit+$Dive+$Atk;
	return $Missions;
}

function DoUniqueSelect($table,$value,$label,$limit=1000,$sort=false)
{
	if(!$sort)$sort=$value;
	$query="SELECT DISTINCT $value,$label FROM $table ORDER BY $sort ASC LIMIT $limit";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			?>
			 <option value="<? echo $data[$value];?>"><? echo $data[$label];?></option>
			<?
		}
		mysqli_free_result($result);
	}
}

function DoSelect($table,$value,$label,$sort,$search,$isearch)
{
	$query="SELECT DISTINCT $value,$label FROM $table WHERE $search='$isearch' ORDER BY $sort ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			?>
			 <option value="<? echo $data[$value];?>"> <? echo $data[$label];?> </option>
			<?
		}
		mysqli_free_result($result);
	}
}

function DoSelect2($table,$value,$label,$sort,$search,$isearch,$search2,$isearch2)
{
	$query="SELECT DISTINCT * FROM $table WHERE $search='$isearch' AND $search2='$isearch2' ORDER BY $sort ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			?>
			 <option value="<? echo $data[$value];?>"> <? echo $data[$label];?> </option>
			<?
		}
		mysqli_free_result($result);
	}
}