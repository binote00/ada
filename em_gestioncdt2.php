<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Unitet=Insec($_POST['unitet']);
if(isset($_SESSION['AccountID']) AND isset($Unitet))
{	
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		include_once('./jfv_inc_em.php');
		include_once('./jfv_txt.inc.php');
		if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $GHQ or $Armee) // or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk))
		{		
			if($Credits >=2)
			{
				$Hydravion=Insec($_POST['hydra']);
                $Usines=Insec(unserialize($_POST['usines']));
                if(is_array($Usines))
                    $flipped_usines=array_flip($Usines); //plus rapide pour is_array
                $con=dbconnecti();
				$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
				$resultu=mysqli_query($con,"SELECT Base,Type,Avion1,Avion2,Avion3,Armee FROM Unit WHERE ID='$Unitet'");
				//mysqli_close($con);
				if($resultu)
				{
					while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
					{
						$Base=$datau['Base'];
						$Unite_Type=$datau['Type'];
						$Avion1=$datau['Avion1'];
						$Avion2=$datau['Avion2'];
						$Avion3=$datau['Avion3'];
						$Unit_Armee=$datau['Armee'];
					}
					mysqli_free_result($resultu);
				}
				//$con=dbconnecti();	
				$result=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Base'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Lat_base=$data['Latitude'];
						$Long_base=$data['Longitude'];
					}
					mysqli_free_result($result);
				}
				if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $GHQ or ($Armee >0 and ($Unit_Armee ==$Armee)))
				{
					$Front=GetFrontByCoord(0,$Lat_base,$Long_base);
					if($Unite_Type ==6 or $Unite_Type ==9 or $Unite_Type ==11)
						$Limite=2000;
					elseif($Long_base >235)
						$Limite=1500;
					elseif($Long_base <-8)
						$Limite=1250;
					else
						$Limite=1000;
					if($Front ==3)
						$Limite*=2;
					if($GHQ)
						$Limite*=1.5;
					$Lands=GetAllies($Date_Campagne);
					if(IsAxe($country))
						$pays_allies=$Lands[1];
					else
						$pays_allies=$Lands[0].",7";
					$LongPiste_mini=GetLongPisteMin($Unite_Type,$Avion1,$Avion2,$Avion3);
					if($Hydravion)
						$query_dest="SELECT ID,Nom,Longitude,Latitude,LongPiste,BaseAerienne,Zone,Flag FROM Lieu WHERE Flag IN (".$pays_allies.") AND Zone<>6 AND ID<>'$Base' AND ((Port_Ori >0 AND Flag_Port IN (".$pays_allies.")) OR (Plage=1 AND Flag_Plage IN (".$pays_allies.")) OR (BaseAerienne=2 AND Flag_Air IN (".$pays_allies.") AND Tour >49)) ORDER BY Nom ASC";
					else					
						$query_dest="SELECT ID,Nom,Longitude,Latitude,LongPiste,BaseAerienne,Zone,Flag FROM Lieu WHERE Flag IN (".$pays_allies.") AND Flag_Air IN (".$pays_allies.")
						AND Zone<>6 AND ID<>'$Base' AND QualitePiste >49 AND Tour >49 AND LongPiste >='$LongPiste_mini' AND 
						((SELECT COUNT(*) FROM Unit as u WHERE u.Base=Lieu.ID AND u.Etat=1 AND u.Type<>8 AND u.Porte_avions=0)<(Lieu.ValeurStrat+2)) ORDER BY Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query_dest);
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[2],$data[3]);
							if(($Dist[0] <$Limite) or ($Base ==1366 and $data[0] ==1849) or ($Base ==1849 and $data[0]==1366))
							{
								$coord=0;
								if($data['LongPiste'])
									$piste_txt=$data['LongPiste']."m";
								else
									$piste_txt="Bassin";
								$Front_Lieu=GetFront(GetFrontByCoord(0,$data[3],$data[2]));
								if($Long_base >$data[2])
									$coord+=2;
								elseif($Long_base <$data[2])
									$coord+=1;
								if($sensh)
								{
									if($Lat_base >$data[3]+0.25)
										$coord+=20;
									elseif($Lat_base <$data[3]-0.25)
										$coord+=10;
								}
								else
								{
									if($Lat_base >$data[3])
										$coord+=20;
									elseif($Lat_base <$data[3])
										$coord+=10;
								}
                                $choix="<tr><td><a href='#' class='text-primary' data-toggle='modal' data-target='#modal-dest-".$data[0]."'><img src='images/".$data['Flag']."20.gif'> ".$data[1]."</a></td><td>".$Dist[0]."km</td><td><img src='images/base".$data['BaseAerienne'].$data['Zone'].".png'>".$piste_txt." [Front ".$Front_Lieu."]</td></tr>";
                                $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data[0].'" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h2 class="modal-title">Déplacement
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img class="img-flex" src="images/gestion_avions'.$country.'.jpg">
                                                                <div class="alert alert-warning">L\'unité se déplacera vers <b>'.$data[1].'</b>, base aérienne située sur le Front '.$Front_Lieu.'</div>
                                                                <form action="em_gestioncdt3.php" method="post"><input type="hidden" name="unitet" value="'.$Unitet.'"><input type="hidden" name="Transfer_esc" value="'.$data[0].'"><input type="hidden" name="cr" value="2"><input type="hidden" name="Transfer_val" value="1"><input class="btn btn-danger" type="submit" value="confirmer"></form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
								if($coord ==1) //Est
									$Est_txt.=$choix;
								elseif($coord ==2) //Ouest
									$Ouest_txt.=$choix;
								elseif($coord ==10) //Nord
									$Nord_txt.=$choix;
								elseif($coord ==20) //Sud
									$Sud_txt.=$choix;
								elseif($coord ==11) //NE
									$NE_txt.=$choix;
								elseif($coord ==21) //SE
									$SE_txt.=$choix;
								elseif($coord ==12) //NO
									$NO_txt.=$choix;
								elseif($coord ==22) //SO
									$SO_txt.=$choix;
                                if(isset($flipped_usines[$data[0]])) //Shortcut usine prod
								    $Centre_txt.=$choix;
								//$dest_move.="<option value='".$data[0]."'>".$data[1]." (".$Dist[0]."km - Front ".$Front_Lieu.")</option>";
							}
							/*elseif($Admin or $GHQ)
								$menu.="-".$data[1]." (".$Dist[0]."km)<br>";*/
						}
						mysqli_free_result($result);
					}
					/*if($Base ==1366)
						$dest_move.="<option value='1849'>San Francisco (3850km)</option>";
					elseif($Base ==1849)
						$dest_move.="<option value='1366'>Pearl Harbor (3850km)</option>";*/
					if($choix or $dest_move)
					{
                        if($Centre_txt)$Centre_txt='<table class="table table-hover"><thead><tr><th colspan="4">Usines de production</th></tr></thead>'.$Centre_txt.'</table>';
						if($Long_base <-40)$Front=7; //USA
						$carte_txt="carte_ground.php?map=".$Front."&mode=11&cible=".$Base."&u=".$Unite_Type."&a1=".$Avion1."&a2=".$Avion2."&a3=".$Avion3;
						$mes="<h2>Destinations</h2>
                            <div class='row'>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord Ouest</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$NO_txt."</table></div></div></div></div>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Nord_txt."</table></div></div></div></div>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord Est</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$NE_txt."</table></div></div></div></div>
							</div>
							<div class='row'>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Ouest</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Ouest_txt."</table></div></div></div></div>
								<div class='col-md-12 col-lg-4'><div class='text-center'><div class='alert alert-info'>Autonomie max : ".$Limite."km</div><div class='btn btn-sm btn-primary'><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>".$Centre_txt."</div></div>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Est</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Est_txt."</table></div></div></div></div>
							</div>
							<div class='row'>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud Ouest</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$SO_txt."</table></div></div></div></div>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Sud_txt."</table></div></div></div></div>
								<div class='col-md-12 col-lg-4'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud Est</div>
								<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$SE_txt."</table></div></div></div></div>
							</div>";
					}
                    /*<form action='em_gestioncdt3.php' method='post'>
					<input type='hidden' name='unitet' value='<?=$Unitet;?>'>
					<input type='hidden' name='cr' value='2'>
					<table class='table'><thead><tr><th>Donner l'ordre de déplacer l'unité aérienne <img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'></th><th></th></thead>
					<td><select name='Transfer_esc' class='form-control' style='width: 200px'><option value='<?=$Base;?>' selected>Annuler</option><?=$dest_move;?></select></td>
					<td><Input type='Radio' name='Transfer_val' value='0' title='Pas bouger!' checked>- Non
					<Input type='Radio' name='Transfer_val' value='1' title='Mouvement!'>- Oui<br></td></tr></table>
                    <input type='submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
                    */
					?><h1>Déplacement d'unité aérienne</h1>
					<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>L'aérodrome de destination doit posséder une piste et une tour en bon état (supérieur à 49%) et être contrôlé par votre faction.
					<p><b>Le type d'unité à déplacer détermine la longueur minimum de piste nécessaire sur l'aérodrome d'arrivée :</b>
					<br>500m pour les unités de chasse, de reconnaissance et d'attaque monomoteurs
					<br>1000m pour les unités d'attaque, de reconnaissance et de chasse multimoteurs
					<br>1200m pour les unités de bombardement et de transport
					<br>1400m pour les unités de bombardement lourd ou de patrouille maritime (sauf si ces dernières ne comportent que des hydravions)
					<br>2000m pour les unités dotées d'avions à réaction
					<br>Les escadrilles de patrouille maritime possédant des hydravions nécessitent un port ou une plage ou un aérodrome doté d'un bassin pour hydravions.</p>
					<p><b>Le nombre d'unités pouvant stationner sur un aérodrome est de 2 + la valeur stratégique du lieu</b>
                        <br>Un aérodrome encombré ne peut plus recevoir de nouvelles escadrilles ni permettre leur transit</p></div>
					<?echo $mes.$lieux_modal;
				}
			}
		}
		else
			PrintNoAccess($country,1,2);
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>