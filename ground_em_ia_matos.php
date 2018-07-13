<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	$Reg=Insec($_POST['Reg']);
	if($Reg >0)
	{
		include_once('./jfv_ground.inc.php');
		include_once('./jfv_txt.inc.php');
		$Ordre_ok=false;
		$country=$_SESSION['country'];
		$con=dbconnecti();
		if($OfficierID >0)
		{
			$resulto=mysqli_query($con,"SELECT Front,Credits FROM Officier WHERE ID='$OfficierID'");
			if($resulto)
			{
				while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
				{
					$Front=$datao['Front'];
					$Credits=$datao['Credits'];
				}
				mysqli_free_result($resulto);
			}
		}
		elseif($OfficierEMID)
		{
			$resulto=mysqli_query($con,"SELECT Front,Credits,Trait,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
			if($resulto)
			{
				while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
				{
					$Front=$datao['Front'];
					$Credits=$datao['Credits'];
					$Trait=$datao['Trait'];
					$Armee=$datao['Armee'];
				}
				mysqli_free_result($resulto);
			}
		}
		if($Front ==99)
		{
			$Planificateur=GetData("GHQ","Pays",$country,"Planificateur");
			if($Planificateur >0 and $OfficierEMID ==$Planificateur)
				$GHQ=true;
		}
		else
		{
			$result2=mysqli_query($con,"SELECT Commandant,Officier_Terre,Adjoint_Terre,Officier_Mer,Officier_Log FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Commandant=$data['Commandant'];
					$Officier_Terre=$data['Officier_Terre'];
					$Adjoint_Terre=$data['Adjoint_Terre'];
					$Officier_Mer=$data['Officier_Mer'];
					$Officier_Log=$data['Officier_Log'];
				}
				mysqli_free_result($result2);
			}
		}
		if(($Officier_Terre >0 and ($Officier_Terre ==$OfficierEMID))
		or ($Commandant >0 and ($Commandant ==$OfficierEMID))
		or ($Adjoint_Terre >0 and ($Adjoint_Terre ==$OfficierEMID))
		or ($Officier_Mer >0 and ($Officier_Mer ==$OfficierEMID))
		or ($Officier_Log >0 and ($Officier_Log ==$OfficierEMID))
		or $Admin ==1 or $GHQ)
			$Ordre_ok=true;
		else
		{
			$reg_pre=mysqli_query($con,"SELECT Bataillon,Division FROM Regiment_IA WHERE ID='$Reg'");
			if($reg_pre)
			{
				while($datarp=mysqli_fetch_array($reg_pre,MYSQLI_ASSOC))
				{
					$Bataillono=$datarp['Bataillon'];
					$Divisiono=$datarp['Division'];
				}
				mysqli_free_result($reg_pre);
			}
			if($OfficierID >0)
			{
				if($Bataillono ==$OfficierID)
					$Ordre_ok=true;
				else
				{
					$Division_Cdt=mysqli_result(mysqli_query($con,"SELECT Cdt FROM Division WHERE ID='$Divisiono'"),0);
					if($Division_Cdt ==$OfficierID)$Ordre_ok=true;
					$menu="<a href='index.php?view=ground_div' class='btn btn-default' title='Retour'>Retour</a>";
				}
			}
			elseif($Armee >0)
			{
				$Division_Armee=GetData("Division","ID",$Divisiono,"Armee");
				if($Division_Armee ==$Armee)$Ordre_ok=true;
			}
		}	
		if($Ordre_ok and $Reg >0)
		{
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$result3=mysqli_query($con,"SELECT r.Move,c.Categorie,c.Type,c.mobile,c.Arme_AT FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg'")
			or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gemiamat-reg');
			mysqli_close($con);
			if($result3)
			{
				while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Categorie=$data3['Categorie'];
					$Type_Veh=$data3['Type'];
					$Move=$data3['Move'];
					$mobile=$data3['mobile'];
					$Arme_AT=$data3['Arme_AT'];
				}
				mysqli_free_result($result3);
			}
			$list_matos="3,11";
			if($Categorie ==1)
				$list_matos.=",10,15,24,30";
			elseif($Categorie ==2)
				$list_matos.=",1,2,6,9,10,13,15,25,27,30";
			elseif($Categorie ==3)
				$list_matos.=",1,2,6,7,8,9,10,12,13,15,16,17,25,27,30";
			elseif($Categorie ==5 and $mobile ==3)
				$list_matos.=",28";
			elseif($Categorie ==8)
				$list_matos.=",1,2,6,8,9,12,27";
			elseif($Categorie ==9)
				$list_matos.=",1,2,6,7,8,9,14,24,25,27";
			elseif($Categorie ==17)
				$list_matos.=",9,12,13,18,22,23,26,27";
			elseif($Categorie ==21)
				$list_matos.=",1,2,9,12,13,22,26,27";
			elseif($Categorie ==22)
				$list_matos.=",1,2,9,12,13,19,20,21,22,23,26,27";
			elseif($Categorie ==20 or $Categorie ==23 or $Categorie ==24)
				$list_matos.=",1,2,9,12,13,22,26,27";
			if($Type_Veh ==3)
				$list_matos.=",24";
			elseif($Type_Veh ==6)
				$list_matos.=",14,24";
			elseif($Type_Veh ==8)
				$list_matos.=",10,13,15,30";
			elseif($Type_Veh ==11)
				$list_matos.=",1,2,9,10,12,13,15,25,27,30";
			elseif($Type_Veh ==12)
			{
				$list_matos.=",1,2,9,12,25,27";
				if($Arme_AT)$list_matos.=",6,7";
			}
			$con=dbconnecti(1);
			$result_s=mysqli_query($con,"SELECT * FROM Skills_m WHERE ID IN(".$list_matos.") AND Service <='$Date_Campagne' ORDER BY Nom ASC");
			mysqli_close($con);
			if($result_s)
			{
				while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
				{
					$skill_txt.="<tr><td><img src='/images/skills/skille".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$datas['Infos']."</td><td><Input type='Radio' name='matos' value='".$datas['ID']."'></td></tr>";
				}
				mysqli_free_result($result_s);
			}
			if(!$Move)
			{
				$mes="<form action='index.php?view=ground_em_ia_matos_do' method='post'><input type='hidden' name='Reg' value='".$Reg."'><div class='text-left' style='overflow:auto; height:640px;'>
				<table class='table'><thead><tr><th>Compétence</th><th>Description</th><th>Choisir</th></tr></thead>".$skill_txt."</table></div>
				<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			else
				$mes="Cette unité n'est pas disponible car elle a déjà effectué son action du jour!";
			if(!$mes)$mes="Non, vraiment, vous ne pouvez pas!";
			$img="<img src='images/scenes/skills_m.jpg'>";
			$titre=$Reg.'e Compagnie IA';
			if(!$menu)
			{
				if($OfficierEMID)
					$menu="<form action='index.php?view=ground_em_ia_list' method='post'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				$menu="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			include_once('./default.php');
		}
		else
			echo "Vous n'êtes pas autorisé à commander cette unité";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';