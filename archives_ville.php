<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($_SESSION['AccountID'] >0)
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		include_once('./jfv_include.inc.php');
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_access.php');
		$country=$_SESSION['country'];
		$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
		if($PlayerID >0)
			$Front=GetData("Pilote","ID",$PlayerID,"Front");
		elseif($OfficierID >0)
			$Front=GetData("Officier","ID",$OfficierID,"Front");
		elseif($OfficierEMID)
			$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		$con=dbconnecti();	
		$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Officier_Terre,Adjoint_Terre,Officier_Mer FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Adjoint_EM'];
				$Officier_EM=$data['Officier_EM'];
				$Officier_Rens=$data['Officier_Rens'];
				$Officier_Terre=$data['Officier_Terre'];
				$Adjoint_Terre=$data['Adjoint_Terre'];
				$Officier_Mer=$data['Officier_Mer'];
			}
			mysqli_free_result($result2);
		}
		if($Admin >0 or $OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $OfficierEMID ==$Adjoint_Terre)
		{
			$Lieu=Insec($_GET['ville']);
			$con=dbconnecti();
			$Lieu=mysqli_real_escape_string($con,$Lieu);
			$result=mysqli_query($con,"SELECT Nom,Port_Ori,Pont_Ori,NoeudF_Ori,NoeudR,Industrie,BaseAerienne,Impass,Plage,Detroit,Flag FROM Lieu WHERE ID='$Lieu'");
			//mysqli_close($con);
			if($result)
			{
				while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Lieu_Nom=$dataa['Nom'];
					$Lieu_Port=$dataa['Port_Ori'];
					$Lieu_Pont=$dataa['Pont_Ori'];
					$Lieu_NoeudF=$dataa['NoeudF_Ori'];
					$Lieu_NoeudR=$dataa['NoeudR'];
					$Lieu_Industrie=$dataa['Industrie'];
					$Lieu_Piste=$dataa['BaseAerienne'];
					$Impass=$dataa['Impass'];
					$Plage=$dataa['Plage'];
					$Detroit=$dataa['Detroit'];
					$Pays=$dataa['Flag'];
				}
				mysqli_free_result($result);
				unset($dataa);
			}
			/*if($Lieu_Port)
				$Infos .="-Ville portuaire ";
			if($Lieu_Pont)
				$Infos .="-Pont Stratégique ";
			if($Lieu_NoeudF)
				$Infos .="-Noeud ferroviaire ";
			if($Lieu_NoeudR)
				$Infos .="-Noeud routier ";
			if($Lieu_Industrie)
				$Infos .="-Zone industrielle ";
			if($Lieu_Piste ==3)
				$Infos .="<br>Piste en herbe ou en terre";
			elseif($Lieu_Piste ==2)
				$Infos .="<br>Piste en dur et mouillage pour hydravions";
			elseif($Lieu_Piste ==1)
				$Infos .="<br>Piste en dur";
			else
				$Infos .="<br><b>Pas de terrain d'aviation</b>";
			if($Impass >0)
				$Infos .="<br>Infranchissable par ".GetImpass($Impass);
			if($Detroit)
				$Infos .="<br>Terrain propice à un minage";
			if($Plage or $Lieu_Port)
				$Infos .="<br>Terrain propice à un débarquement";*/
			//$Piste_infos="<p>".$Infos."</p>";
			//Journal
			//$con=dbconnecti();
			$result_unit=mysqli_query($con,"SELECT `Date`,Lieu,Pays,Unite,Type,Avion,Avion_Nbr FROM Event_Historique WHERE Lieu='$Lieu' AND Type IN(40,41,51,55) ORDER BY `Date` DESC");
			mysqli_close($con);
			if($result_unit)
			{
				while($Data=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
				{
					$Date=$Data['Date'];
					$Unite=$Data['Unite'];
					$Pays=$Data['Pays'];
					$Type_e=$Data['Type'];
					$Long=$Data['Avion'];
					$Type_p=$Data['Avion_Nbr'];
					if($Type_e ==41)
					{
						$Unite_Nom=GetData("Unit","ID",$Unite,"Nom");
						$Unite_Pays=GetData("Unit","ID",$Unite,"Pays");
						if($Unite_Pays ==$country or $PlayerID ==1 or $PlayerID ==2)
							$Event .="<br>".$Date." : <img src='images/unit/unit".$Unite."p.gif'> <b>".$Unite_Nom."</b> fait mouvement dans les environs de ".$Lieu_Nom; 
					}
					elseif($Type_e ==51)
					{
						$Unite_Nom=GetData("Unit","ID",$Unite,"Nom");
						$Unite_Pays=GetData("Unit","ID",$Unite,"Pays");
						if($Unite_Pays ==$country or $PlayerID ==1 or $PlayerID ==2)
							$Event .="<br>".$Date." : <img src='images/unit/unit".$Unite."p.gif'> <b>".$Unite_Nom."</b> formé dans les environs de ".$Lieu_Nom; 
					}
					elseif($Type_e ==40)
					{
						$Nom_Pays=GetPays($Pays);
						$Event .="<br>".$Date." : <img src='".$Pays."20.gif' title='".$Nom_Pays."'> Occupation par <b>".$Nom_Pays."</b>"; 
					}
					elseif($Type_e ==55)
					{
						if($Type_p ==1)
							$Type_p="e piste en dur longue";
						else
							$Type_p=" terrain long";
						$Event .="<br>".$Date." : ".$Lieu_Nom." se voit doté <b>d'un".$Type_p." de ".$Long."m</b>"; 
					}
				}
				mysqli_free_result($result_unit);
			}	
			$con=dbconnecti(4);
			$result=mysqli_query($con,"SELECT Event_Type,Avion,Lieu,DATE_FORMAT(Date,'%d/%m/%Y : %Hh%i') as Heure FROM Events_Feed WHERE Lieu='$Lieu' AND Event_Type IN (44,116,200)");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($data['Event_Type'] ==44)
						$mes.="<p>".$data['Heure']." <img src='images/zone7.jpg' alt='ville'> La ville de <b>".$Lieu_Nom."</b> a été revendiquée par les troupes ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'></p>";
					elseif($data['Event_Type'] ==116)
						$mes.="<p>".$data['Heure']." Des troupes ont débarqué dans les environs de <b>".$Lieu_Nom."</b></p>";
					elseif($data['Event_Type'] ==200)
						$mes.="<p>".$data['Heure']." Des troupes ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'> ont été repérées faisant mouvement dans les environs de <b>".$Lieu_Nom."</b></p>";
				}
				mysqli_free_result($result);
			}
			$Event.=$mes;
		}
		else
		{
			$mes="<p>Ces données sont classifiées. <br>Votre rang ne vous permet pas d'accéder à ces informations.</p>";
			$img="<img src='images/top_secret.gif'>";
		}
		$titre=$Lieu_Nom;
		$mes=$Event;
		include_once('./default_blank.php');
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>