<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$AccountID=$_SESSION['AccountID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Officier_Front=99;
	$con=dbconnecti();
	$resultp=mysqli_query($con,"SELECT Pays,Front,Credits,Missions_Jour,Reputation,Avancement,Bombardement,Vue,Tir,Tactique,Navigation,Pilotage,Missions,Abattu FROM Pilote WHERE ID='$PlayerID'");
	$Brevet=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Skills_Pil WHERE PlayerID='$PlayerID' AND Skill=120"),0);
	/*$resultj=mysqli_query($con,"SELECT j.Parrain,j.Officier,o.Front FROM Joueur as j
	LEFT JOIN Officier AS o ON j.Officier=o.ID WHERE ID='$AccountID'");*/
	//mysqli_close($con);
	/*$con=dbconnecti(4);
	$Crash=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND PlayerID='$PlayerID' AND Avion_Nbr=1"),0);
	$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$PlayerID'"),0);
	mysqli_close($con);*/
	/*if($resultj)
	{
		while($dataj=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
		{
			$Parrain=$dataj['Parrain'];
			$Officier=$dataj['Officier'];
			$Officier_Front=$dataj['Front'];
		}
		mysqli_free_result($resultj);
	}*/
	if($resultp)
	{
		while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
		{
			$Front=$datap['Front'];
			$Pays=$datap['Pays'];
			$Credits=$datap['Credits'];
			$Missions_Jour=$datap['Missions_Jour'];
			$Reput=$datap['Reputation'];
			$Avancement=$datap['Avancement'];
			$Bomb=$datap['Bombardement'];
			$Vue=$datap['Vue'];
			$Tir=$datap['Tir'];
			$Tactique=$datap['Tactique'];
			$Navigation=$datap['Navigation'];
			$Pilotage=$datap['Pilotage'];
			$Missions=$datap['Missions'];
			$Abattu=$datap['Abattu'];
		}
		mysqli_free_result($resultp);
		unset($datap);
	}
	$Sqn=GetSqn($Pays);
	$Cred_Min=5;
	/*$Cred_Min=15+ceil(($Crash+$Perdu)/10);
	if($Note >100)$Cred_Min=10;*/
	if(($Credits >=$Cred_Min and $Missions_Jour <6) or $Admin)
	{
		$Parrain_Front=99;
		/*if($Parrain >0)
		{
			//$con=dbconnecti();
			$resultp=mysqli_query($con,"SELECT Pays,Pilote_id,Actif FROM Joueur WHERE ID='$Parrain'");
			//mysqli_close($con);
			if($resultp)
			{
				while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
				{
					if($datap['Actif'] !=1 and $Pays ==$datap['Pays'])
					{
						$Parrain_Pil=$datap['Pilote_id'];
					}
				}
				mysqli_free_result($resultp);
			}
			if($Parrain_Pil >0)$Parrain_Front=GetData("Pilote","ID",$Parrain_Pil,"Front");
		}*/
		$Rep_titre='Reput';
		$txt=false;
		$cibles=false;
		$infos="<p>Il est conseillé de prendre contact avec votre <a href='index.php?view=em_actus' class='lien'>état-major de front</a> <b>avant</b> de valider votre demande de mutation, ceci afin de vous faire économiser des heures ou des jours d'attente.</p>";
		if($Brevet and $Reput >49)
		{
			if($Avancement >2999){

				$query="SELECT DISTINCT u.ID,u.Nom,u.Base,u.Type,u.Reputation,u.Commandant,u.Avion1,u.Avion2,u.Avion3,l.Longitude,l.Latitude,l.Nom as Terrain FROM Unit as u,Lieu as l 
				WHERE u.Base=l.ID AND u.Pays='$Pays' AND u.Etat=1 AND u.Type<>8 AND u.Commandant IS NULL AND u.Armee=0 AND u.Reputation <='$Reput' ORDER BY u.Reputation DESC,u.Type DESC,u.Nom ASC";
                $Fonction_txt=GetStaff($Pays,1);
            }
			elseif($Avancement >1499){
				$query="SELECT DISTINCT u.ID,u.Nom,u.Base,u.Type,u.Reputation,u.Commandant,u.Avion1,u.Avion2,u.Avion3,l.Longitude,l.Latitude,l.Nom as Terrain FROM Unit as u,Lieu as l 
				WHERE u.Base=l.ID AND u.Pays='$Pays' AND u.Etat=1 AND u.Type<>8 AND u.Officier_Adjoint IS NULL AND u.Armee=0 AND u.Reputation <='$Reput' AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) ORDER BY u.Reputation DESC,u.Type DESC,u.Nom ASC";
				$Rep_titre='Commandant';
                $Fonction_txt=GetStaff($Pays,2);

            }
			else{
				$query="SELECT DISTINCT u.ID,u.Nom,u.Base,u.Type,u.Reputation,u.Commandant,u.Avion1,u.Avion2,u.Avion3,l.Longitude,l.Latitude,l.Nom as Terrain FROM Unit as u,Lieu as l 
				WHERE u.Base=l.ID AND u.Pays='$Pays' AND u.Etat=1 AND u.Type<>8 AND u.Officier_Technique IS NULL AND u.Armee=0 AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) ORDER BY u.Reputation DESC,u.Type DESC,u.Nom ASC";
				$Rep_titre='Commandant';
                $Fonction_txt=GetStaff($Pays,3);

            }
			$base_nom_txt=true;
		}
		else{
			$query="SELECT DISTINCT u.ID,u.Nom,u.Base,u.Type,u.Reputation,u.Avion1,u.Avion2,u.Avion3,l.Longitude,l.Latitude,l.Nom as Terrain FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Type=8 AND u.Pays='$Pays' ORDER BY u.Nom ASC";
			$info_new="<br>Pour accéder aux unités de combat, vous devez posséder votre brevet de pilote et jouir d'une réputation minimum d'ailier (élève-pilote n'est pas suffisant)</a>";
		}
		//$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$ID=$data['ID'];
				/*$con=dbconnecti();
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$ID'"),0);
				mysqli_close($con);*/
				//($Reput >4999 or $Avancement >4999 or $Pilotes >1)
					$Type=GetAvionType($data['Type']);
					$Cdt='';
					/*$OT='';
					$OA='';*/
					$Unite_Nom=$data['Nom'];
					$Unite_Base=$data['Base'];
					/*if($base_nom_txt)
					{
						if($country ==9){
							$Front_txt='Pacifique';
							$Front_unit=3;
						}
						else{
							$Front_unit=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
							$Front_txt=GetFront($Front_unit);
						}
						if($Front ==$Front_unit)
							$Unit_ok=true;
						elseif($Front_unit ==$Parrain_Front or ($Front_unit ==$Officier_Front))
							$Unit_ok=false;
					}
					else*/
                    if($country ==9){
                        $Front_txt='Pacifique';
                        $Front_unit=3;
                    }
                    else{
                        $Front_unit=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
                        $Front_txt=GetFront($Front_unit);
                    }
                    $Unit_ok=true;
					/*$Commandant=$data['Commandant'];
					$Officier_Technique=$data['Officier_Technique'];
					$Officier_Adjoint=$data['Officier_Adjoint'];
					$Recrutement=$data['Recrutement'];
					$Ratio=$data['Ratio'];
					if(!$Recrutement or $Pilotes >11)
						$Recrutement="<img src='images/closed.gif'>";
					if($data['Priorite'])
						$Recrutement="<img src='images/prior.gif'>";
					else
						$Recrutement="<img src='images/open.gif'>";
					if($Commandant)
					{
						$Av1=GetAvancement(GetData("Pilote","ID",$Commandant,"Avancement"),$Pays); 
						$Cdt=$Av1[0]."<br>".GetData("Pilote","ID",$Commandant,"Nom");
					}
					if($Officier_Technique)
					{
						$Av2=GetAvancement(GetData("Pilote","ID",$Officier_Technique,"Avancement"),$Pays); 
						$OT=$Av2[0]."<br>".GetData("Pilote","ID",$Officier_Technique,"Nom");
					}
					if($Officier_Adjoint)
					{
						$Av3=GetAvancement(GetData("Pilote","ID",$Officier_Adjoint,"Avancement"),$Pays); 
						$OA=$Av3[0]."<br>".GetData("Pilote","ID",$Officier_Adjoint,"Nom");
					}*/
					if($Unit_ok)
					{
						if($Avancement >2999)
							$Rep_txt=$data['Reputation'];
						else
						{
							if($data['Commandant']){
								$Av1=GetAvancement(GetData("Pilote","ID",$data['Commandant'],"Avancement"),$Pays); 
								$Cdt=$Av1[0]."<br>".GetData("Pilote","ID",$data['Commandant'],"Nom");
							}
							$Rep_txt=$Cdt;
						}
						$img_unit=Afficher_Icone($data['ID'],$Pays,$Unite_Nom);
						/*$txt.="<tr><th><Input type='Radio' value=".$data['ID']." name='unit'>".$img_unit."<br>".$Unite_Nom."</th><td>".$Type."</td>
						<td>".$Front_txt."</td><td>".$Rep_txt."</td>
						<td>".GetAvionIcon($data['Avion1'],$Pays,0,$data['ID'],$Front_unit)."</td><td>".GetAvionIcon($data['Avion2'],$Pays,0,$data['ID'],$Front_unit)."</td><td>".GetAvionIcon($data['Avion3'],$Pays,0,$data['ID'],$Front_unit)."</td>
						</tr>";*/	 //<td>".$Ratio."</td><td>".$OA."</td><td>".$OT."</td>
                        $txt.="
                        <div class='row'>
                            <div class='col-xs-3 col-md-1'><Input type='Radio' value=".$data['ID']." name='unit'>".$img_unit."<br>".$Unite_Nom."</div>
                            <div class='col-xs-3 col-md-1'>".$Type."</div>
                            <div class='col-xs-3 col-md-1'>".$Front_txt."</div>
                            <div class='col-xs-3 col-md-1'>".$Rep_txt."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion1'],$Pays,0,$data['ID'],$Front_unit)."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion2'],$Pays,0,$data['ID'],$Front_unit)."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion3'],$Pays,0,$data['ID'],$Front_unit)."</div>
                        </div>";
					}
					elseif($Admin){
                        $txt.="
                        <div class='row'>
                            <div class='col-xs-3 col-md-1'>".$img_unit."<br>".$Unite_Nom."</div>
                            <div class='col-xs-3 col-md-1'>".$Type."</div>
                            <div class='col-xs-3 col-md-1'>".$Front_txt."</div>
                            <div class='col-xs-3 col-md-1'>".$Rep_txt."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion1'],$Pays,0,$data['ID'],$Front_unit)."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion2'],$Pays,0,$data['ID'],$Front_unit)."</div>
                            <div class='col-xs-4 col-md-2'>".GetAvionIcon($data['Avion3'],$Pays,0,$data['ID'],$Front_unit)."</div>
                        </div>";
                    }
			}
			mysqli_free_result($result);
			unset($data);
		}
		?><h1>Demande de mutation <img src='/images/CT<?=$Cred_Min;?>.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'></h1><?
		if(!$txt)
			echo "<img src='images/unites.jpg'><div class='alert alert-danger'>Aucune unité n'est disponible soit parce que vous possédez déjà un autre personnage sur le même front, soit parce qu'aucune unité n'est disponible dans un rayon suffisamment proche.<br>Dans ce dernier cas, contactez votre planificateur stratégique.".$info_new."</div>";
		else
		{
			?>
			<form action="choix_unite1.php" method="post">
				<input type="hidden" name="Pilote" value="<?=$PlayerID;?>">
				<div class="row">
					<div class="col-md-6"><img src="images/unites.jpg" style="width:100%;"></div>
					<div class="col-md-6">
						<div class='alert alert-warning'>Changer d'unité coûte <img src='/images/CT<?=$Cred_Min;?>.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>
						<br>Tout changement d'unité doit être validé par votre hiérarchie!
						<?if($Reput <500){?>
						<br><i><?echo $info_new;?>.</i>
						<?}if($Reput >9999){?>
						<br><i>Votre avion personnel sera également transféré.</i>
						<?}
						echo $infos;?>
						</div>
					</div>
				</div>
				<?/*<div style='overflow:auto; height: 400px;'>
					<table class='table table-striped'>
						<thead><tr><th>Unité</th><th>Type</th><th>Front</th><th><?=$Rep_titre;?></th><th><?=$Sqn;?> 1</th><th><?=$Sqn;?> 2</th><th><?=$Sqn;?> 3</th></tr></thead>
						<?=$txt;?>
					</table>
				</div>*/?>
                <div class='row'>
                    <div class='col-xs-3 col-md-1'><h3>Unité</h3></div>
                    <div class='col-xs-3 col-md-1'><h3>Type</h3></div>
                    <div class='col-xs-3 col-md-1'><h3>Front</h3></div>
                    <div class='col-xs-3 col-md-1'><h3><?=$Rep_titre;?></h3></div>
                    <div class='col-xs-4 col-md-2'><h3><?=$Sqn;?> 1</h3></div>
                    <div class='col-xs-4 col-md-2'><h3><?=$Sqn;?> 2</h3></div>
                    <div class='col-xs-4 col-md-2'><h3><?=$Sqn;?> 3</h3></div>
                </div>
                <div class='text-left striped' style='overflow-y:auto; overflow-x:hidden; height:400px; width:97%;'>
                    <?=$txt;?>
                </div>
                <p><input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></p>
			</form>
            <div class='alert alert-info'>Votre pilote ne peut postuler que dans les unités dont la réputation est égale ou inférieure à la sienne.<br>Au regard de son grade, il ne peut également postuler que pour le poste de <b><?=$Fonction_txt?></b>. Si ce poste est déjà occupé par un autre joueur, l'unité n'est pas accessible.
            <br>Les unités sous le commandement d'un commandant d'armée, ne possédant pas un stock suffisant d'avions ou réservées par le GHQ ne sont pas accessibles aux joueurs.</div>
			<?
		}
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont inaccessibles.<br>Vous ne disposez pas de suffisamment de temps pour vous occuper de cela maintenant.</div>";
}
else
	header("Location: ./tsss.php");
?>