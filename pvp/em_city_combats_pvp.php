<?php
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Pilote_pvp >0 or $Officier_pvp >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Battle=Insec($_POST['Battle']);
	$Cible=GetCiblePVP($Battle);
	$Officier_acces=true;
	if($Officier_acces)
	{
		if($Cible)
		{
			$con=dbconnecti();	
			$resultc=mysqli_query($con,"SELECT Nom,Latitude,Longitude FROM Lieu WHERE ID='$Cible'");
			mysqli_close($con);
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
			$mes="<h1>".$Lieu_Nom."</h1>";
			/*if($Type ==2)
			{
				$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats_pvp.php?type=2&id=".$Cible."'>Bombardements tactiques</a>";
				$query="SELECT a.Date,a.Unite,a.Avion,a.Lieu,a.Pays,a.Cible_id,a.Joueur,a.Cycle,p.Nom
				FROM Attaque_PVP as a,Pilote_PVP as p WHERE a.Lieu='$Cible' AND a.Cible_id NOT IN (8,18,52) AND a.Joueur=p.ID GROUP BY a.Date,a.Joueur,a.Cible_id
				ORDER BY a.ID DESC LIMIT 25";
			}
			else
				$menu_cat_list.="<a class='btn btn-default' href='em_city_combats_pvp.php?type=2&id=".$Cible."'>Bombardements tactiques</a>";
			if($Type ==8)
			{
				$menu_cat_list.="<a class='btn btn-primary' href='em_city_combats_pvp.php?type=8&id=".$Cible."'>Bombardements stratégiques</a>";
				$query="SELECT b.*,u.Nom as Unite_s,u.Pays as Pays_s FROM Bombardement_PVP as b,Unit as u WHERE b.Unite=u.ID AND b.Lieu='$Cible' ORDER BY b.ID DESC LIMIT 25";
			}
			else
				$menu_cat_list.="<a class='btn btn-default' href='em_city_combats_pvp.php?type=8&id=".$Cible."'>Bombardements stratégiques</a>";*/
			$liste_ia="";
			$con=dbconnecti();
			$result1=mysqli_query($con,"SELECT `Date`,Reg_a,Veh_a,Veh_Nbr_a,Pos_a,Reg_b,Veh_b,Veh_Nbr_b,Pos_b,Kills,Place,Distance,Off_a,Off_b FROM Ground_Cbt_PVP WHERE Lieu='$Cible' ORDER BY ID DESC LIMIT 25");
			$result3=mysqli_query($con,"SELECT `Date`,Pilote_a,Avion_a,Pilote_b,Avion_b,Alt,Distance FROM Air_Cbt_PVP WHERE Lieu='$Cible' ORDER BY ID DESC LIMIT 25");
			$result5=mysqli_query($con,"SELECT `Date`,Pilote,Avion,Alt,Cycle,Veh,Arme,Degats FROM DCA_Cbt_PVP WHERE Lieu='$Cible' ORDER BY ID DESC LIMIT 25");
			mysqli_close($con);
			if($result5)
			{
				while($data2=mysqli_fetch_array($result5))
				{
					$Date=substr($data2['Date'],0,16);
					$Pilote_dca=GetData("Pilote_PVP","ID",$data2['Pilote'],"Nom");
					$Pays_avion=GetData("Avion","ID",$data2['Avion'],"Pays");
					$Pays_veh=GetData("Cible","ID",$data2['Veh'],"Pays");
					$liste_dca.="<tr><td>".$Date."</td><td>".$icon."</td>
					<td><img src='".$Pays_avion."20.gif'></td><td>".$Pilote_dca."</td><td>".GetAvionIcon($data2['Avion'],$Pays_avion,0,0,$Front_Lieu)."</td>
					<td>".GetVehiculeIcon($data2['Veh'],$Pays_veh,0,0,$Front_Lieu)."</td><td><img src='".$Pays_veh."20.gif'></td></tr>";
				}
				mysqli_free_result($result5);
				if($liste_dca)
				{
					$liste_ia.="<h2>Avions abattus par la DCA</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
					<thead><tr><th>Date</th><th>Pays Avion</th><th>Pilote</th><th>Avion</th>
					<th>DCA</th><th>Pays DCA</th></thead>".$liste_dca."</table></div>";
				}
			}
			if($result3)
			{
				while($data2=mysqli_fetch_array($result3))
				{
					$Date=substr($data2['Date'],0,16);
					$Pilote_win=GetData("Pilote_PVP","ID",$data2['Pilote_a'],"Nom");
					$Pilote_loss=GetData("Pilote_PVP","ID",$data2['Pilote_b'],"Nom");
					$Pays_win=GetData("Avion","ID",$data2['Avion_a'],"Pays");
					$Pays_loss=GetData("Avion","ID",$data2['Avion_b'],"Pays");
					$liste_air.="<tr><td>".$Date."</td><td>".$icon."</td>
					<td><img src='".$Pays_win."20.gif'></td><td>".$Pilote_win."</td><td>".GetAvionIcon($data2['Avion_a'],$Pays_win,0,0,$Front_Lieu)."</td>
					<td>".GetAvionIcon($data2['Avion_b'],$Pays_loss,0,0,$Front_Lieu)."</td><td>".$Pilote_loss."</td><td><img src='".$Pays_loss."20.gif'></td></tr>";
				}
				mysqli_free_result($result3);
				if($liste_air)
				{
					$liste_ia.="<h2>Combats aériens</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
					<thead><tr><th>Date</th><th>Action</th><th>Pays</th><th>Pilote</th><th>Avion</th>
					<th>Avion Abattu</th><th>Pilote</th><th>Pays</th></thead>".$liste_air."</table></div>";
				}
			}
			if($result1)
			{
				while($data2=mysqli_fetch_assoc($result1))
				{
					$Dist=false;
					$Date=substr($data2['Date'],0,16);
					if($data2['Reg_a'])
						$Reg_a=$data2['Reg_a']."e Cie";
					else
						$Reg_a="Garnison/IA";
					if($data2['Reg_b'])
						$Reg_b=$data2['Reg_b']."e Cie";
					else
						$Reg_b="Garnison/IA";
					$DB_Reg="Regiment_PVP";
					$DB_Regb="Regiment_PVP";
					$Pays_win=GetData("Cible","ID",$data2['Veh_a'],"Pays");
					$Pays_loss=GetData("Cible","ID",$data2['Veh_b'],"Pays");
					if($data2['Off_a'] >0)
						$Officier_win=GetData("Officier_PVP","ID",$data2['Off_a'],"Nom");
					else
						$Officier_win="Inconnu";
					if($data2['Off_b'] >0)
						$Officier_loss=GetData("Officier_PVP","ID",$data2['Off_b'],"Nom");
					else
						$Officier_loss="Inconnu";
					$Veh_Nbr_a=$data2['Veh_Nbr_a'];
					$Veh_Nbr_b=$data2['Veh_Nbr_b'];
					$Pos_b=GetPosGr($data2['Pos_b']);
					$Pos_a=GetPosGr($data2['Pos_a']);
					$Place=GetPlace($data2['Place']);
					$Veh_a=GetVehiculeIcon($data2['Veh_a'],$Pays_win,0,0,$Front_Lieu);
					$Veh_b=GetVehiculeIcon($data2['Veh_b'],$Pays_loss,0,0,$Front_Lieu);
					$Dist=true;
					if($Dist and $Premium)
					{
						if($data2['Distance'] >5000 and !$Admin)
							$Dist_txt="+5000m";
						else
							$Dist_txt=$data2['Distance']."m";
					}
					else
						$Dist_txt="<div class='i-flex premium20'></div>";
					$liste_ground.="<tr><td>".$Date."</td><td>".$Place."</td>
						<td><img src='".$Pays_win."20.gif'></td><td>".$Reg_a."</td><td>".$Officier_win."</td><td>".$Pos_a."</td><td>".$Veh_Nbr_a." ".$Veh_a."</td>
						<td>".$Veh_Nbr_b." ".$Veh_b."</td><td>".$Pos_b."</td><td>".$Officier_loss."</td><td>".$Reg_b."</td><td><img src='".$Pays_loss."20.gif'></td>
						<td>".$data2['Kills']."</td><td>".$Dist_txt."</td></tr>";
				}
				mysqli_free_result($result1);
				if($liste_ground)
				{
					$liste_ia.="<h2>Combats</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
					<thead><tr><th>Date</th><th>Zone</th><th>Pays</th><th>Unité</th><th>Officier victorieux</th><th>Position</th><th>Troupes</th>
					<th>Troupes</th><th>Position</th><th>Officier défait</th><th>Unité</th><th>Pays</th><th>Pertes</th><th>Distance</th></tr></thead>".$liste_ground."</table></div>";
				}
			}
			/*if($result and $Type ==2)
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
			}*/
			$mes.=$menu_cat_list.$liste_ia;
			include_once('./default.php');
		}
		else
			echo "Tsss!";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}?>