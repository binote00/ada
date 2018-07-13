<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Unite=Insec($_POST['Unite']);
$Flak1=Insec($_POST['1_Flak']);
$Nbr1=Insec($_POST['1_Nbr']);
$Skill1=Insec($_POST['1_Skill']);
$Alt1=Insec($_POST['1_Alt']);
$Flak2=Insec($_POST['2_Flak']);
$Nbr2=Insec($_POST['2_Nbr']);
$Skill2=Insec($_POST['2_Skill']);
$Alt2=Insec($_POST['2_Alt']);
$Flak3=Insec($_POST['3_Flak']);
$Nbr3=Insec($_POST['3_Nbr']);
$Skill3=Insec($_POST['3_Skill']);
$Alt3=Insec($_POST['3_Alt']);
$Flak4=Insec($_POST['4_Flak']);
$Nbr4=Insec($_POST['4_Nbr']);
$Skill4=Insec($_POST['4_Skill']);
$Alt4=Insec($_POST['4_Alt']);
$Flak5=Insec($_POST['5_Flak']);
$Nbr5=Insec($_POST['5_Nbr']);
$Skill5=Insec($_POST['5_Skill']);
$Alt5=Insec($_POST['5_Alt']);
$Flak6=Insec($_POST['6_Flak']);
$Nbr6=Insec($_POST['6_Nbr']);
$Skill6=Insec($_POST['6_Skill']);
$Alt6=Insec($_POST['6_Alt']);
$Flak7=Insec($_POST['7_Flak']);
$Nbr7=Insec($_POST['7_Nbr']);
$Skill7=Insec($_POST['7_Skill']);
$Alt7=Insec($_POST['7_Alt']);
$Flak8=Insec($_POST['8_Flak']);
$Nbr8=Insec($_POST['8_Nbr']);
$Skill8=Insec($_POST['8_Skill']);
$Alt8=Insec($_POST['8_Alt']);
$Flak9=Insec($_POST['9_Flak']);
$Nbr9=Insec($_POST['9_Nbr']);
$Skill9=Insec($_POST['9_Skill']);
$Alt9=Insec($_POST['9_Alt']);
$Flak10=Insec($_POST['10_Flak']);
$Nbr110=Insec($_POST['10_Nbr']);
$Skill10=Insec($_POST['10_Skill']);
$Alt10=Insec($_POST['10_Alt']);
$Flak11=Insec($_POST['11_Flak']);
$Nbr11=Insec($_POST['11_Nbr']);
$Skill11=Insec($_POST['11_Skill']);
$Alt11=Insec($_POST['11_Alt']);
$Flak12=Insec($_POST['12_Flak']);
$Nbr12=Insec($_POST['12_Nbr']);
$Skill12=Insec($_POST['12_Skill']);
$Alt12=Insec($_POST['12_Alt']);
$Flak13=Insec($_POST['13_Flak']);
$Nbr13=Insec($_POST['13_Nbr']);
$Skill13=Insec($_POST['13_Skill']);
$Alt13=Insec($_POST['13_Alt']);
$Flak14=Insec($_POST['14_Flak']);
$Nbr14=Insec($_POST['14_Nbr']);
$Skill14=Insec($_POST['14_Skill']);
$Alt14=Insec($_POST['14_Alt']);
$Flak15=Insec($_POST['15_Flak']);
$Nbr15=Insec($_POST['15_Nbr']);
$Skill15=Insec($_POST['15_Skill']);
$Alt15=Insec($_POST['15_Alt']);
$Nbr16=Insec($_POST['16_Nbr']);
$Skill16=Insec($_POST['16_Skill']);
$Alt16=Insec($_POST['16_Alt']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $Unite >0)
{
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $Unite)
	{		
		$Credits=false;
		/*$con=dbconnecti();
		$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite' AND Actif=0"),0);
		mysqli_close($con);		
		if($Pilotes >2)
		{*/	
			$dca_ok=true;
			$Commandement=GetData("Pilote","ID",$PlayerID,"Commandement");		
			$Credits_ori=GetData("Pilote","ID",$PlayerID,"Credits");
			$Unite=GetData("Pilote","ID",$PlayerID,"Unit");			
			for($dca_nb=1;$dca_nb<16;$dca_nb++)
			{
				$Nbr_DCA="Nbr".$dca_nb;
				$total_pieces += $$Nbr_DCA;
			}
			if($total_pieces >10)
				$dca_ok=false;			
			if($dca_ok)
			{
				$con=dbconnecti();		
				$result=mysqli_query($con,"SELECT Base,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'");		
				$result2=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Citernes,Camions,Port_Ori,Port,NoeudF_Ori,NoeudF FROM Lieu WHERE ID='$Base'");	
				//mysqli_close($con);		
				if($result)		
				{		
					while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))	
					{	
						$Base=$data['Base'];
						$Pers1=$data['Pers1'];
						$Pers2=$data['Pers2'];
						$Pers3=$data['Pers3'];
						$Pers4=$data['Pers4'];
						$Pers5=$data['Pers5'];
						$Pers6=$data['Pers6'];
						$Pers7=$data['Pers7'];
						$Pers8=$data['Pers8'];
						$Pers9=$data['Pers9'];
						$Pers10=$data['Pers10'];
					}	
					mysqli_free_result($result);
					unset($data);
					unset($result);
				}		
				$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
				$Personnel=array_count_values($Pers);
				//GetData Lieu
				if($result2)		
				{		
					while($data=mysqli_fetch_array($result2, MYSQLI_ASSOC))	
					{
						$Zone=$data['Zone'];
						$Base_Lat=$data['Latitude'];
						$Base_Long=$data['Longitude'];
						$Citernes=$data['Citernes'];
						$Camions=$data['Camions'];
						$Port_ori_base=$data['Port_Ori'];
						$Gare_ori_base=$data['NoeudF_Ori'];
						$Port_base=$data['Port'];
						$Gare_base=$data['NoeudF'];
					}
					mysqli_free_result($result2);
					unset($data);
				}			
				if(!$Port_ori_base)
					$Port_base=100;
				if(!$Gare_ori_base)
					$Gare_base=100;
				if($Port_base != 100 and $Port_base >= $Gare_base)
					$Inf_base=$Port_base;
				elseif($Gare_base != 100 and $Gare_base > $Port_base)
					$Inf_base=$Gare_base;
				else
					$Inf_base=100;					
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus <>'' AND Flag_Usine='$country'");
				mysqli_close($con);
				if($result)
				{
					if($data=mysqli_fetch_array($result, MYSQLI_NUM))
					{
						if($data[0] > 0)
							$Efficacite_prod=round($data[1]/$data[0]);
						else
							$Efficacite_prod=0;
					}
					mysqli_free_result($result);
				}
				//Outre-Mer ou anglais
				if($Base_Lat <38.2 or $Base_Long >70 or $country ==2 or $Zone ==6)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
					$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat > 0 AND Flag_Gare='$country'");
					mysqli_close($con);
					if($result)
					{
						if($data=mysqli_fetch_array($result, MYSQLI_NUM))
						{
							if($data[0] > 0)
								$Efficacite_ravit_port=round($data[1]/$data[0]);
							else
								$Efficacite_ravit_port=0;
						}
						mysqli_free_result($result);
					}
					if($result2)
					{
						if($data=mysqli_fetch_array($result2, MYSQLI_NUM))
						{
							if($data[0] > 0)
								$Efficacite_ravit=round($data[1]/$data[0]);
							else
								$Efficacite_ravit=0;
						}
						mysqli_free_result($result2);
					}
					$Efficacite_ravit=round(($Efficacite_ravit + ($Efficacite_ravit_port*2))/3);
				}
				else
				{
					$Lat_base_min=$Base_Lat-1;
					$Lat_base_max=$Base_Lat+1;
					$Long_base_min=$Base_Long-3;
					$Long_base_max=$Base_Long+3;					
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
					$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country' 
					AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
					mysqli_close($con);
					if($result)
					{
						if($data=mysqli_fetch_array($result, MYSQLI_NUM))
						{
							if($data[0] > 0)
								$Efficacite_ravit1=round($data[1]/$data[0]);
							else
								$Efficacite_ravit1=0;
						}
						mysqli_free_result($result);
					}
					if($result2)
					{
						if($data2=mysqli_fetch_array($result2, MYSQLI_NUM))
						{
							if($data2[0] > 0)
								$Efficacite_ravit2=round($data2[1]/$data2[0]);
							else
								$Efficacite_ravit2=0;
						}
						mysqli_free_result($result2);
					}
					$Efficacite_ravit=round(($Efficacite_ravit1 + ($Efficacite_ravit2*2))/3);
				}
				unset($data);
				//Malus ravitaillement par saison ou terrain
				$Saison=$_SESSION['Saison'];
				if($Base_Long >20 and $Base_Lat >43)		//Front Est
				{
					if($Saison ==2)	// Printemps (boue dégel)
						$Camions +=20;
					elseif($Saison ==1) // Automne
						$Camions +=5;
					elseif($Saison ==0) // Hiver
						$Camions +=25;
				}
				elseif($Base_Lat > 55) // Europe du nord
				{
					if($Saison ==0) // Hiver
						$Camions +=25;
				}
				elseif($Base_Lat > 43) // Europe continentale
				{
					if($Saison ==0) // Hiver
						$Camions +=10;
				}
				elseif($Base_Lat <33) // Désert
				{
					if($Saison ==3) // Ete (chaleur, pannes)
						$Camions +=5;
				}
				if($Zone ==5 or $Zone ==9 or $Zone ==11)
					$Camions +=20;
				elseif($Zone ==4)
					$Camions +=15;
				elseif($Zone ==3)
					$Camions +=10;
				elseif($Zone ==2 or $Zone ==8)
					$Camions +=5;						
				$Efficacite_ravit_muns=($Efficacite_ravit-$Camions)*($Inf_base/100);
				$img_txt="flak15".$country;				
				$Corps=GetEM_Name($country);				
				if($Efficacite_prod >25 and $Efficacite_ravit_muns >25)
				{
					$Date=date('Y-m-d');
					for($dca_i=1;$dca_i<17;$dca_i++)
					{
						$chk_dca=false;
						$Alt=0;
						$Flak=0;
						$Skill=0;
						$Nbr=0;
						$DCA_Exp=0;
						$chk_dca_nbr=0;
						$Nbr_t="Nbr".$dca_i;
						$Flak_t="Flak".$dca_i;
						$Skill_t="Skill".$dca_i;
						$Alt_t="Alt".$dca_i;
						$Nbr=$$Nbr_t;
						$Flak=$$Flak_t;
						$Skill=$$Skill_t;
						$Alt=$$Alt_t;
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT ID,DCA_Nbr,DCA_Exp,Alt FROM Flak WHERE Lieu='$Base' AND Unit='$Unite' AND DCA_ID='$Flak'");
						mysqli_close($con);	
						if($result)
						{
							if($data=mysqli_fetch_array($result))
							{
								$Flak_ID=$data['ID'];
								$chk_dca_nbr=$data['DCA_Nbr'];
								$DCA_Exp=$data['DCA_Exp'];
								$DCA_Alt=$data['Alt'];
							}
							mysqli_free_result($result);
						}
						if($Nbr <$chk_dca_nbr and $chk_dca_nbr >0)
						{
							$Credits=0;
							$minus=$Nbr-$chk_dca_nbr;
							if($Nbr <1)
							{
								$mes.="<br>La batterie de <b>".GetData("Armes","ID",$Flak,"Nom")."</b> a été démantelée avec succès.";
								DeleteData("Flak", "ID", $Flak_ID);
							}
							else
							{
								$mes.="<br>La batterie de <b>".GetData("Armes","ID",$Flak,"Nom")."</b> a été réduite avec succès.";
								UpdateData("Flak","DCA_Nbr", $minus,"ID", $Flak_ID);
							}
							AddEvent("Avion",106,$Flak,$PlayerID,$Unite,$Base,$minus,$Flak);
						}
						elseif($Nbr >0)
						{
							$CT_upgrade=0;
							$CT_Exp=0;
							if($Nbr >$chk_dca_nbr)
							{
								$CT_unitaire=GetData("Armes","ID",$Flak,"Flak")*2;
								$CT_upgrade=($Nbr*$CT_unitaire)-($chk_dca_nbr*$CT_unitaire);
								if($CT_upgrade <1)$CT_upgrade=99;
							}
							if($Skill >$DCA_Exp)
							{
								$CT_Exp=$Skill*2;
								if($Personnel[2] >=$Skill)
									$CT_Exp=0;
								elseif($CT_Exp <1)
									$CT_Exp=99;
							}
							$CT=$CT_upgrade+$CT_Exp;
							if($Credits_ori >=$CT)
							{
								$Credits_ori-=$CT;
								$Credits-=$CT;
								$con=dbconnecti();
								$result=mysqli_query($con,"SELECT ID FROM Flak WHERE Lieu='$Base' AND Unit='$Unite' AND DCA_ID='$Flak'");
								mysqli_close($con);	
								if($result)
								{
									if($data=mysqli_fetch_array($result))
									{
										$chk_dca=$data['ID'];
									}
									mysqli_free_result($result);
								}
								if($chk_dca >0)
								{
									if($Nbr >$chk_dca_nbr or $Skill >$DCA_Exp or $Alt !=$DCA_Alt)
									{
										$mes.="<br>La batterie de <b>".GetData("Armes","ID",$Flak,"Nom")."</b> a été modifiée avec succès.";
										$con=dbconnecti();
										$reset=mysqli_query($con,"UPDATE Flak SET Date='$Date',DCA_Nbr='$Nbr',DCA_Exp='$Skill',Alt='$Alt' WHERE ID='$chk_dca'");
										mysqli_close($con);	
										AddEvent("Avion",105,$Flak,$PlayerID,$Unite,$Base,$Nbr,$Skill);
									}
									else
										$mes.="<br>La batterie de <b>".GetData("Armes","ID",$Flak,"Nom")."</b> n'a pas été modifiée.";								
								}
								else
								{
									$mes.="<br>La batterie de <b>".GetData("Armes","ID",$Flak,"Nom")."</b> a été installée avec succès.";
									$con=dbconnecti();
									$query="INSERT INTO Flak (Lieu,Unit,Date,DCA_ID,DCA_Nbr,DCA_Exp,Alt)";
									$query.="VALUES ('$Base','$Unite','$Date','$Flak','$Nbr','$Skill','$Alt')";
									$reset=mysqli_query($con,$query);
									mysqli_close($con);	
									AddEvent("Avion",104,$Flak,$PlayerID,$Unite,$Base,$Nbr,$Skill);
								}
							}
							else
							{
								$mes.="<br>Le ".$Corps." vous informe qu'une partie de votre demande a été refusée car elle était irréalisable.";
								break;
							}
						}			
					}
					//Chance de récupérer 25% des CT en fonction du score de Commandement
					if($Credits <0)
					{
						$mes.="<p>".$msg_prod."<br>".$msg_ravit."</p><p>Le ".$Corps." vous informe que votre demande a été acceptée.".$fournitures_txt."
							<br><br>Votre unité, le <b>".$Unite_Nom."</b> recevra sous peu les fournitures commandées.</p>";
						if($Credits <-3)
						{
							if(mt_rand(0,200) <$Commandement)
							{
								$Credits=ceil($Credits*0.75);
							}
						}
						$skills=MoveCredits($PlayerID,3,$Credits);
						UpdateCarac($PlayerID,"Avancement",-$Credits);
						//UpdateCarac($PlayerID,"Gestion",-$Credits);
					}
				}
				else
					$mes="<br>Le ".$Corps." vous informe que votre demande a été refusée car elle est irréalisable au vu de la situation actuelle; soit par manque de production de nos usines, soit par difficultés de ravitaillement.";
				$msg_prod="<img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'> ".$Efficacite_prod."% 
				<img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement général' title='Efficacité du ravitaillement général'> ".$Efficacite_ravit."%";
				$msg_ravit="<img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'> ".$Efficacite_ravit_muns." %";
				$img="<img src='images/".$img_txt.".jpg'>";
			}
			else
			{
				$mes="Votre demande dépasse le nombre maximum de pièces autorisées!";
				$img="<img src='images/unites".$country.".jpg'>";
			}
			$menu ="<p><a title='Retour' href='index.php?view=esc_dca' class='btn btn-default'>Retour au menu de gestion de dca</a></p>";
		/*}
		else
		{
			echo "<p>Votre unité manque de personnel pour cela.</p>";
		}*/
	}
	else
	{
		$titre="MIA";
		$mes="Peut-être la reverrez-vous un jour votre escadrille...";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>