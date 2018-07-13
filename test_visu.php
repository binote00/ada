<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$OfficierID=1;
	$country=1;
	$Placement=0;
	$Cible=618;
			$query="(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement'
			AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr > 0 AND r.Officier_ID <> '$OfficierID' AND r.Pays <> '$country')
			UNION (SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment_IA as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement'
			AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr > 0 AND r.Pays <> '$country')";
			//Scan Pos
			$con=dbconnecti();
			$result=mysqli_query($con, $query);
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($data['Position'] == 8)
						$Pos_icon="<img src='images/mortar.png' title='Sous le feu'>";
					else
						$Pos_icon="";
					if($data['Portee'] <500)
						$Range_fix="100";
					elseif($data['Portee'] >5000)
						$Range_fix="5500";
					else
					{
						$data['Portee'] = round($data['Portee']/500)*500;
						$Range_fix = $data['Portee'];
					}
					if($data['Placement'] != $Placement)
					{
						$Pos_icon.="<img src='images/strat0.png' title='Zone adjacente'>";
						$Range_fix="5500";
					}
					$choix="choix_".$Range_fix;
					if($data['Portee'] <=$Range)
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
					else
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."' disabled title='Hors de portée'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
				}
				mysqli_free_result($result);
			}
			$menu="<form action='test_visu.php' method='post'>
				<h2>Cibles repérées ".GetPlace($Placement)."</h2><div style='overflow:auto; height: 640px;'><div class='row'><div class='col-md-10'><div class='col-md-1'><p><b>Ligne de front</b></p>".$choix_100."</div><div class='col-md-1'><p><b>500m</b></p>".$choix_500."</div><div class='col-md-1'><p><b>1000m</b></p>".$choix_1000."</div><div class='col-md-1'><p><b>1500m</b></p>".$choix_1500."</div>
				<div class='col-md-1'><p><b>2000m</b></p>".$choix_2000."</div><div class='col-md-1'><p><b>2500m</b></p>".$choix_2500."</div><div class='col-md-1'><p><b>3000m</b></p>".$choix_3000."</div><div class='col-md-1'><p><b>3500m</b></p>".$choix_3500."</div>
				<div class='col-md-1'><p><b>4000m</b></p>".$choix_4000."</div><div class='col-md-1'><p><b>4500m</b></p>".$choix_4500."</div><div class='col-md-1'><p><b>5000m</b></p>".$choix_5000."</div></div><div class='col-md-2'><p><b>+5000m</b></p>".$choix_5500."</div></div></div>
				<Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
				".$Distance_tir.$Repli.$Armement."
				<span><img src='images/help.png' title=\"".$Aide."\</span></form>";
			include_once('./default_blank.php');
}
?>