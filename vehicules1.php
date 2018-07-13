<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_infos.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($OfficierID >0)
	{
		$con=dbconnecti();	
		$result=mysqli_query($con,"SELECT Pays,Reputation,Avancement FROM Officier WHERE ID='$OfficierID'");
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$country=$data['Pays'];
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
			}
			mysqli_free_result($result);
		}
	}
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['type']);
	if($Pays =="all")
		$Pays="%";
	if($Type =="all")
		$Type="%";
	if($Pays >9 or $Pays ==1)
	{
        if($Type ==100)
            $query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Categorie=5 AND Type=0 AND Pays='$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
		elseif($Type ==94)
			$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Categorie=5 AND Type=94 AND Pays='$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
		else
			$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Type LIKE '$Type' AND Pays='$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
	}
    elseif($Type ==100)
        $query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Categorie=5 AND Type=0 AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
	elseif($Type ==94)
		$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Categorie=5 AND Type=94 AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
	else
		$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(`Date`,'%d-%b-%Y') as Engage,Premium,HP,Reput,Lease FROM Cible WHERE Type LIKE '$Type' AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY `Date`,Pays,Nom ASC LIMIT 100";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		$num=mysqli_num_rows($result);
		if($num ==0)
			echo "<h6>Désolé, aucun véhicule ne correspond à votre recherche</h6>";
		else
		{
			$i=0;
			while($i <$num) 
			{
				$Premium_txt="";
				$ID=mysqli_result($result,$i,"ID");
				$Pays=mysqli_result($result,$i,"Pays");
				$Type=mysqli_result($result,$i,"Type");
				$Nom=mysqli_result($result,$i,"Nom");
				$Engagement=mysqli_result($result,$i,"Engage");
				$HP=mysqli_result($result,$i,"HP");
				$Reput=mysqli_result($result,$i,"Reput");
				$Premium_unit=mysqli_result($result,$i,"Premium");
				$Lease=mysqli_result($result,$i,"Lease");
				if($Lease)
					$Premium_txt="<img src='images/lendlease.png' title='Lend Lease requis ".$Lease." points' alt='Lend Lease requis ".$Lease." points'>";
				if($Premium_unit)
					$Premium_txt.="<div class='i-flex premium20'></div>";					
				if(($Pays == $country or $Pays ==0) and ($Premium_unit == $Premium or !$Premium_unit) and $Engagement <=$Date_Campagne)
				{
					if($Type ==10 and $Avancement >=25000 and $Reputation >=5000)
						$Cat=true;
					elseif($Type ==91 and $Avancement >=10000 and $Reputation >=2000)
						$Cat=true;
					elseif($Type ==7 and $Avancement >=5000 and $Reputation >=2000)
						$Cat=true;
					elseif($Type ==7 and $Avancement >=5000 and $Reputation >=750)
						$Cat=true;
					elseif(($Type ==2 or $Type ==3) and $Avancement >=5000 and $Reputation >=500)
						$Cat=true;
					elseif(($Type ==8 or $Type ==9) and $Avancement >=5000 and $Reputation >=1000)
						$Cat=true;
					elseif($Type ==11 and $Avancement >=5000 and $Reputation >=100)
						$Cat=true;
					elseif(($Type ==1 or $Type ==4 or $Type ==6 or $Type ==12 or $Type ==93) and $Avancement >=0 and $Reputation >=0)
						$Cat=true;
					elseif($Type >12 and $Type <19)
						$Cat=true;
					elseif(!$Type)
						$Cat=true;
					else
						$Cat=false;
					if($Cat)
					{
						if(($HP <=$Reputation) OR ($Reput <=$Avancement/1000))
							$Perso_txt="<div class='i-flex led_green' title='Accessible pour votre officier' alt='Accessible pour votre officier'></div>";
						else
							$Perso_txt="<div class='i-flex led_orange' title='Votre officier manque de réputation ou de grade' alt='Votre officier manque de réputation ou de grade'></div>";
					}
					else
						$Perso_txt="<div class='i-flex led_red' title='La catégorie de ce véhicule sera accessible plus tard pour votre officier' alt='La catégorie de ce véhicule sera accessible plus tard pour votre officier'></div>";
				}
				else
					$Perso_txt="<div class='i-flex led_red' title='Pas encore accessible pour votre officier' alt='Pas encore accessible pour votre officier'></div>";
				if($Reput>50)$Reput=50;
				$l_Nom=strlen($Nom);
				if($l_Nom >14)
					$class_name_veh="s-10";
				elseif($l_Nom >10)
					$class_name_veh="s-12";
				else
					$class_name_veh="";
				$img_cible_veh="images/cibles/cibles".$ID.".jpg";
				if(is_file($img_cible_veh))
					$popup_txt="<img src='images/cibles/cibles".$ID.".jpg' style='width:300px;'>";
				else
					$popup_txt="Image à venir";
				$veh_txt.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
					<fieldset class='veh'>
						<div class='row veh_header'>
							<div class='col-md-2 col-xs-2'>
								<img src='images/".$Pays."20.gif'>
							</div>
							<div class='col-md-7 col-xs-7 name_veh ".$class_name_veh."'>".$Nom."</div>
							<div class='col-md-3 col-xs-3'>
								<img src='images/CT".$Reput.".png'>
							</div>
						</div>
						<div class='row veh_body'>
							<a href='#' class='popup-light'><img src='images/vehicules/vehicule".$ID.".gif'><span>".$popup_txt."</span></a>
						</div>
						<div class='row veh_footer'>
							<div class='col-md-2 col-xs-2'>".$Premium_txt." ".$Perso_txt."</div>
							<div class='col-md-7 col-xs-7 text-center'>".$Engagement."</div>
							<div class='col-md-3 col-xs-3'><a href='cible.php?cible=".$ID."' target='_blank'><div class='help_icon'></div></a></div>
						</div>
					</fieldset></div>";
					//							<a href='#' class='popup-light'><img src='images/vehicules/vehicule".$ID.".gif'><span>".$popup_txt."</span></a>
				$i++;
			}
			echo "<h2>Véhicules</h2><div class='row'>".$veh_txt."</div>";
		}
	}
	else
		echo "<h6>Désolé, aucun véhicule ne correspond à votre recherche</h6>";
}
?>