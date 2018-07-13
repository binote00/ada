<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 xor $OfficierID >0 xor $OfficierEMID >0)
{	
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_access.php');
	include_once('./jfv_inc_em.php');
	if($PlayerID)
	{
		$con=dbconnecti();	
		$result=mysqli_query($con,"SELECT Unit,Avancement,Renseignement,Front FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Avancement=$data['Avancement'];
				$Renseignement=$data['Renseignement'];
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}
		$Base=GetData("Unit","ID",$Unite,"Base"); 
	}
	elseif($OfficierID)
	{
		$con=dbconnecti();	
		$result=mysqli_query($con,"SELECT Avancement,Front FROM Officier WHERE ID='$OfficierID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}
	}
	if($Admin ==1 or $Avancement >4999 or $Renseignement >100 or $GHQ)
		$Officier_acces=true;
	elseif($Avancement >499)
		$Pilote_acces=true;
	if($Officier_acces or $Pilote_acces)
	{
		if(!$Cible)
			$Cible=Insec($_POST['id']);
		if(!$Cible)
			$Cible=Insec($_GET['id']);
		$Type=Insec($_GET['type']);
		if($Cible)
		{
			$con=dbconnecti();
			$Cible=mysqli_real_escape_string($con,$Cible);
			$resultc=mysqli_query($con,"SELECT Nom,Latitude,Longitude FROM Lieu WHERE ID='$Cible'");
			if($resultc)
			{
				while($data=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
				{
					$Lieu_Nom=$data['Nom'];
					$Lat=$data['Latitude'];
					$Long=$data['Longitude'];
				}
				mysqli_free_result($resultc);
			}
			$Front_Lieu=GetFrontByCoord($Cible,$Lat,$Long);
			$mes='<h1>'.$Lieu_Nom.'</h1>';
			if($Front_Lieu ==$Front or $Admin)
			{
				$limit=50;
				if($Admin)$limit=100;
				if($Type ==6)
				{
					$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats.php?type=6&id=".$Cible."'>Attaques aériennes</a>";
					$query="SELECT a.*,u.Nom as Unite_s,u.Pays FROM Attaque_ia as a,Unit as u WHERE a.Unite=u.ID AND a.Lieu='$Cible' ORDER BY a.ID DESC LIMIT 25";
				}
				else
					$menu_cat_list.="<a class='btn btn-default' href='em_city_combats.php?type=6&id=".$Cible."'>Attaques aériennes</a>";
				if($Type ==2)
				{
					$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats.php?type=2&id=".$Cible."'>Bombardements tactiques</a>";
					$query="SELECT Attaque.Date,Attaque.Unite,Attaque.Avion,Attaque.Lieu,Attaque.Pays,Attaque.Cible_id,Attaque.Joueur,Attaque.Cycle,Pilote.Nom,Unit.Nom as Unite_s,Unit.Pays as Pays_s
					FROM Attaque,Pilote,Unit WHERE Attaque.Lieu='$Cible' AND Attaque.Cible_id NOT IN (8,18,52) AND Attaque.Joueur=Pilote.ID AND Attaque.Unite=Unit.ID GROUP BY Attaque.Date,Attaque.Joueur,Attaque.Cible_id
					ORDER BY Attaque.ID DESC LIMIT 25";
				}
				else
					$menu_cat_list.="<a class='btn btn-default' href='em_city_combats.php?type=2&id=".$Cible."'>Bombardements tactiques</a>";
				if($Type ==8)
				{
					$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats.php?type=8&id=".$Cible."'>Bombardements stratégiques</a>";
					$query="SELECT b.*,u.Nom as Unite_s,u.Pays as Pays_s FROM Bombardement as b,Unit as u WHERE b.Unite=u.ID AND b.Lieu='$Cible' ORDER BY b.ID DESC LIMIT 25";
				}
				else
					$menu_cat_list.="<a class='btn btn-default' href='em_city_combats.php?type=8&id=".$Cible."'>Bombardements stratégiques</a>";
				if($Type ==3)
				{
					$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats.php?type=3&id=".$Cible."'>Combats aériens</a>";
					$query="SELECT e.* FROM gnmh_aubedesaiglesnet4.Events_em as e WHERE e.Event_Type IN (280,281,282,283,284,285) AND e.Lieu='$Cible' ORDER BY e.ID DESC LIMIT ".$limit."";
				}
				else
					$menu_cat_list.="<a class='btn btn-default' href='em_city_combats.php?type=3&id=".$Cible."'>Combats aériens</a>";
				if($Type ==1 or !$Type)
				{
					$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats.php?type=1&id=".$Cible."'>Combats</a>";
					$query="SELECT `Date`,Reg_a,Veh_a,Veh_Nbr_a,Pos_a,Reg_b,Veh_b,Veh_Nbr_b,Pos_b,Kills,Place,Distance,Reg_a_ia,Reg_b_ia FROM Ground_Cbt WHERE Lieu='$Cible' ORDER BY ID DESC LIMIT 25";
				}
				else
					$menu_cat_list.="<a class='btn btn-default' href='em_city_combats.php?type=1&id=".$Cible."'>Combats</a>";
				$liste_ia='';
				$result=mysqli_query($con,$query);
				if($result and $Officier_acces and $Type ==6)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Date=substr($data['Date'],0,16);
						if($data['Cycle'])
							$Cycle_txt="Nuit";
						else
							$Cycle_txt="Jour";
						if(!$data['Pilotes'])
							$icon="<img src='images/ia_down.png' title='Attaque sans effet'>";
						else
							$icon="<img src='images/ia_bomb.png' title='Attaque réussie'>";
						if($data['Target'])
							$icon_target=GetVehiculeIcon($data['Target'],0,0,0,$Front_Lieu);
						elseif(!$data['Arme'])
							$icon_target="<img src='images/ia_para.png' title='Parachutage de ravitaillement'>";
						else
							$icon_target="<img src='images/ia_troops.png' title='Troupes au sol'>";
						if($data['DCA'])
							$icon_dca="<img src='images/dca_shoot.png' title='DCA active'>";
						else
							$icon_dca="<img src='images/dca_sleep.png' title='DCA inactive'>";
						if($OfficierEMID >0 or $Admin or $Premium)
							$Pilotes_txt=$data['Pilotes'];
						else
						{
							$data['Couverture']='?';
							$data['Escorte']='?';
							$data['DCA']='?';
							$Pilotes_txt='?';
						}
						$liste_ia.="<tr><td>".$Date."</td><td><img src='images/meteo".$data['Cycle'].".gif' title='".$Cycle_txt."'></td><td>".$icon."</td>
						<td><img src='".$data['Pays']."20.gif'></td><td>".Afficher_Icone($data['Unite'],$data['Pays'],$data['Unite_s'])."</td><td>".$Pilotes_txt." ".GetAvionIcon($data['Avion'],$data['Pays'],0,$data['Unite'],$Front_Lieu)."</td>
						<td>".$icon_target."</td><td>".$data['DCA']." ".$icon_dca."</td><td>".$data['Couverture']."</td><td>".$data['Escorte']."</td></tr>";
					}
					mysqli_free_result($result);
					if($liste_ia)
					{
						$liste_ia="<h2>Attaques aériennes</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
						<thead><tr><th>Date</th><th>Cycle</th><th>Action</th><th>Pays</th><th>Unité</th><th>Avions</th><th>Cible</th><th>DCA</th><th>Patrouilles</th><th>Escortes</th></tr></thead>".$liste_ia."</table></div>";
					}
				}
				if($result and $Officier_acces and $Type ==3)
				{
					while($data2=mysqli_fetch_array($result))
					{
						if($Admin)
						{
							$avion1_legend='';
							$avion2_legend='';
							if($data2['Event_Type'] ==280)
							{
								$avion1_legend='<br><i>Couverture</i>';
								$avion2_legend='<br><i>Escorte</i>';
							}
							elseif($data2['Event_Type'] ==281)
							{
								$avion1_legend='<br><i>Escorte</i>';
								$avion2_legend='<br><i>Couverture</i>';
							}
							elseif($data2['Event_Type'] ==282)
							{
								$avion1_legend='<br><i>Attaque</i>';
								$avion2_legend='<br><i>Couverture</i>';
							}
							elseif($data2['Event_Type'] ==283)
							{
								$avion1_legend='<br><i>Couverture</i>';
								$avion2_legend='<br><i>Attaque</i>';
							}
						}
						$Date=substr($data2['Date'],0,16);
						$icon="<img src='images/ia_down.png' title='Avion abattu'>";
						if($data2['Event_Type'] ==283 or $data2['Event_Type'] ==284 or $data2['Event_Type'] ==285)
						{
							if($data2['Event_Type'] ==285)
								$icon="<img src='images/ia_return.png' title='Avion forcé à faire demi-tour'>";
							elseif($data2['Event_Type'] ==284)
								$icon="<img src='images/ia_combat.png' title='Interception'>";
							$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']);
							$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']);
							if($resultp1)
							{
								while($datap1=mysqli_fetch_array($resultp1, MYSQLI_ASSOC))
								{
									$Pilote_win=$datap1['Nom'];
									$Pays_win=$datap1['Pays'];
									$Unit_win=$datap1['Unit'];
								}
								mysqli_free_result($resultp1);
							}
							if($resultp)
							{
								while($datap=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
								{
									$Pilote_loss=$datap['Nom'];
									$Pays_loss=$datap['Pays'];
									$Unit_loss=$datap['Unit'];
								}
								mysqli_free_result($resultp);
							}
							$liste_ia.="<tr><td>".$Date."</td><td>".$icon."</td>
							<td><img src='".$Pays_loss."20.gif'></td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td>".$Pilote_loss."</td><td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front_Lieu).$avion1_legend."</td>
							<td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front_Lieu).$avion2_legend."</td><td>".$Pilote_win."</td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td><img src='".$Pays_win."20.gif'></td></tr>";
						}
						else
						{
							$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']);
							$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']);
							if($resultp1)
							{
								while($datap1=mysqli_fetch_array($resultp1, MYSQLI_ASSOC))
								{
									$Pilote_win=$datap1['Nom'];
									$Pays_win=$datap1['Pays'];
									$Unit_win=$datap1['Unit'];
								}
								mysqli_free_result($resultp1);
							}
							if($resultp)
							{
								while($datap=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
								{
									$Pilote_loss=$datap['Nom'];
									$Pays_loss=$datap['Pays'];
									$Unit_loss=$datap['Unit'];
								}
								mysqli_free_result($resultp);
							}
							$liste_ia.="<tr><td>".$Date."</td><td>".$icon."</td>
							<td><img src='".$Pays_win."20.gif'></td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td>".$Pilote_win."</td><td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front_Lieu).$avion1_legend."</td>
							<td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front_Lieu).$avion2_legend."</td><td>".$Pilote_loss."</td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td><img src='".$Pays_loss."20.gif'></td></tr>";
						}
					}
					mysqli_free_result($result);
					/*if($liste_ia)
					{*/
						$liste_ia="<h2>Combats aériens</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
						<thead><tr><th>Date</th><th>Action</th><th>Pays</th><th>Unité</th><th>Pilote</th><th>Avion 1</th>
						<th>Avion 2</th><th>Pilote</th><th>Unité</th><th>Pays</th></thead>".$liste_ia."</table></div>";
					//}
				}
				mysqli_close($con);
				if($result and $Type ==1)
				{
					while($data2=mysqli_fetch_assoc($result))
					{
						$Dist=false;
						$Date=substr($data2['Date'],0,16);
						if($data2['Reg_a'])
							$Reg_a=$data2['Reg_a'].'e Cie';
						else
							$Reg_a="Garnison/IA";
						if($data2['Reg_b'])
							$Reg_b=$data2['Reg_b'].'e Cie';
						else
							$Reg_b="Garnison/IA";
						if($data2['Reg_a_ia'])
							$DB_Reg="Regiment_IA";
						else
							$DB_Reg="Regiment";
						if($data2['Reg_b_ia'])
							$DB_Regb="Regiment_IA";
						else
							$DB_Regb="Regiment";
						$Pays_win=GetData($DB_Reg,"ID",$data2['Reg_a'],"Pays");
						$Pays_loss=GetData($DB_Regb,"ID",$data2['Reg_b'],"Pays");
						$off_win=GetData($DB_Reg,"ID",$data2['Reg_a'],"Officier_ID");
						$off_loss=GetData($DB_Regb,"ID",$data2['Reg_b'],"Officier_ID");
						if($off_win >0)
							$Officier_win=GetData("Officier","ID",$off_win,"Nom");
						else
							$Officier_win='Inconnu';
						if($off_loss >0)
							$Officier_loss=GetData("Officier","ID",$off_loss,"Nom");
						else
							$Officier_loss='Inconnu';
						if($OfficierEMID >0 or $Admin ==1)
						{
							$Veh_Nbr_a=$data2['Veh_Nbr_a'];
							$Veh_Nbr_b=$data2['Veh_Nbr_b'];
							$Pos_b=GetPosGr($data2['Pos_b']);
							$Pos_a=GetPosGr($data2['Pos_a']);
							$Place=GetPlace($data2['Place']);
							$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win,0,0,$Front_Lieu);
							$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss,0,0,$Front_Lieu);
							$Dist=true;
						}
						elseif($off_win ==$Officier and $off_win >0)
						{
							$Veh_Nbr_a=$data2['Veh_Nbr_a'];
							$Veh_Nbr_b="";
							$Pos_a=GetPosGr($data2['Pos_a']);
							$Pos_b=GetPosGr($data2['Pos_b']);
							$Place=GetPlace($data2['Place']);
							$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win,0,0,$Front_Lieu);
							$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss,0,0,$Front_Lieu);
							$Dist=true;
						}
						elseif($off_loss ==$Officier and $off_loss >0)
						{
							$Veh_Nbr_a="";
							$Veh_Nbr_b=$data2['Veh_Nbr_b'];
							$Pos_b=GetPosGr($data2['Pos_b']);
							$Pos_a=GetPosGr($data2['Pos_a']);
							$Place=GetPlace($data2['Place']);
							$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win,0,0,$Front_Lieu);
							$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss,0,0,$Front_Lieu);
							$Dist=true;
						}
						elseif($PlayerID >0 or $Officier >0)
						{
							$Veh_Nbr_a='';
							$Veh_Nbr_b='';
							$Pos_a='Inconnue';
							$Pos_b='Inconnue';
							$Place='Inconnu';
							$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win,0,0,$Front_Lieu);
							$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss,0,0,$Front_Lieu);
						}
						else
						{
							$Veh_Nbr_a='';
							$Veh_Nbr_b='';
							$Pos_b='Inconnue';
							$Pos_a='Inconnue';
							$Place='Inconnu';
							$Veh_a='Inconnu';
							$Veh_b='Inconnu';
						}
						if($Dist and $Premium)
						{
							if($data2['Distance'] >5000 and !$Admin)
								$Dist_txt='+5000m';
							else
								$Dist_txt=$data2['Distance'].'m';
						}
						else
							$Dist_txt="<div class='i-flex premium20'></div>";
						$liste_ia.="<tr><td>".$Date."</td><td>".$Place."</td>
							<td><img src='".$Pays_win."20.gif'></td><td>".$Reg_a."</td><td>".$Officier_win."</td><td>".$Pos_a."</td><td>".$Veh_Nbr_a." ".$Veh_a."</td>
							<td>".$Veh_Nbr_b." ".$Veh_b."</td><td>".$Pos_b."</td><td>".$Officier_loss."</td><td>".$Reg_b."</td><td><img src='".$Pays_loss."20.gif'></td>
							<td>".$data2['Kills']."</td><td>".$Dist_txt."</td></tr>";
					}
					mysqli_free_result($result);
					if($liste_ia)
					{
						$liste_ia="<h2>Combats</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
						<thead><tr><th>Date</th><th>Zone</th><th>Pays</th><th>Unité</th><th>Officier victorieux</th><th>Position</th><th>Troupes</th>
						<th>Troupes</th><th>Position</th><th>Officier défait</th><th>Unité</th><th>Pays</th><th>Pertes</th><th>Distance</th></tr></thead>".$liste_ia."</table></div>";
					}
				}
				if($result and $Type ==2)
				{
					while($data2=mysqli_fetch_assoc($result))
					{
						$Date=substr($data2['Date'],0,16);
						if($data2['Cycle'])
							$Cycle_txt="Nuit";
						else
							$Cycle_txt="Jour";
						$liste_ia.="<tr><td>".$Date."</td><td><img src='images/meteo".$data2['Cycle'].".gif' title='".$Cycle_txt."'></td><td><img src='".$data2['Pays_s']."20.gif'></td><td>";
						if($Officier_acces)
							$liste_ia.=Afficher_Icone($data2['Unite'],$data2['Pays_s'],$data2['Unite_s'])."</td><td>";
						else
							$liste_ia.="Inconnu</td><td>";
						$liste_ia.=GetAvionIcon($data2['Avion'],$data2['Pays_s'],$data2['Joueur'],$data2['Unite'],$Front_Lieu)."</td><td>";
						if($Officier_acces)
							$liste_ia.=GetVehiculeIcon($data2['Cible_id'],$data2['Pays'],0,0,$Front_Lieu);
						else
							$liste_ia.="Inconnu";
						$liste_ia.="</td><td><img src='".$data2['Pays']."20.gif'></td></tr>";
					}
					mysqli_free_result($result);
					if($liste_ia)
					{
						$liste_ia="<h2>Bombardements tactiques</h2>
						<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
								<th>Date</th>
								<th>Cycle</th>
								<th>Pays</th>
								<th>Unité</th>
								<th>Avion</th>
								<th>Cible détruite</th>
								<th>Pays cible</th>
							</tr></thead>".$liste_ia."</table></div>";
					}
				}
				if($result and $Type ==8)
				{
					while($data2=mysqli_fetch_assoc($result))
					{
						$Date=substr($data2['Date'],0,16);
						if($data2['Cycle'])
							$Cycle_txt="Nuit";
						else
							$Cycle_txt="Jour";
						$liste_ia.="<tr><td>".$Date."</td><td><img src='images/meteo".$data2['Cycle'].".gif' title='".$Cycle_txt."'></td><td><img src='".$data2['Pays_s']."20.gif'></td><td>";
						if($Officier_acces)
							$liste_ia.=Afficher_Icone($data2['Unite'],$data2['Pays_s'],$data2['Unite_s'])."</td><td>";
						else
							$liste_ia.="Inconnu</td><td>";
						$liste_ia.=GetAvionIcon($data2['Avion'],$data2['Pays_s'],0,$data2['Unite'],$Front_Lieu)."</td><td>";
						if($Officier_acces)
							$liste_ia.=GetVehiculeIcon($data2['Cible_id'],$data2['Pays'],0,0,$Front_Lieu);
						else
							$liste_ia.="Inconnu";
						$liste_ia.="</td><td><img src='".$data2['Pays']."20.gif'></td></tr>";
					}
					mysqli_free_result($result);
					if($liste_ia)
					{
						$liste_ia="<h2>Bombardements stratégiques</h2>
						<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
								<th>Date</th>
								<th>Cycle</th>
								<th>Pays</th>
								<th>Unité</th>
								<th>Avion</th>
								<th>Cible détruite</th>
								<th>Pays cible</th>
							</tr></thead>".$liste_ia."</table></div>";
					}
				}
				$mes.=$menu_cat_list.$liste_ia;
			}
			else
				$mes.="<p class='lead'>Les informations ne sont pas accessibles car ce front n'est pas celui de votre personnage</p>";
			include_once('./default_blank.php');
		}
		else
			echo "Tsss!";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}?>