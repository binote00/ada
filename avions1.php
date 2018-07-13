<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_infos.php');
	$PlayerID=$_SESSION['PlayerID'];
	$i=0;
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['type']);
	$Rating=Insec($_POST['level']);
	$Premium=GetData("Joueur","ID",$AccountID,"Premium");
	if($PlayerID >0)
	{
		$con=dbconnecti();	
		$result=mysqli_query($con,"SELECT Pays,Reputation,Avancement FROM Pilote WHERE ID='$PlayerID'");
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
		if($Avancement >$Reputation)
			$Level=$Avancement;
		else
			$Level=$Reputation;
		$Level/=5000;
	}
	if($Pays =="all")$Pays="%";
	if($Type =="all")$Type="%";
	if($Rating =="all")$Rating="%";
	if($Pays >9 or $Pays ==1)
		$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(Engagement,'%d-%b-%Y') as Engage,Premium,Etat,Rating,Prototype,Fin_Prod FROM Avion WHERE Pays='$Pays' AND Rating LIKE '$Rating' AND Type LIKE '$Type' ORDER BY Engagement,Pays,Nom ASC LIMIT 100";
	else
		$query="SELECT ID,Pays,Type,Nom,DATE_FORMAT(Engagement,'%d-%b-%Y') as Engage,Premium,Etat,Rating,Prototype,Fin_Prod FROM Avion WHERE Pays LIKE '$Pays' AND Rating LIKE '$Rating' AND Type LIKE '$Type' ORDER BY Engagement,Pays,Nom ASC LIMIT 100";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Type_nom=GetAvionType($data['Type']);
			if($data['Rating'] >10)
				$Level_txt="<img src='images/air_level10.png'>";
			else
				$Level_txt="<img src='images/air_level".$data['Rating'].".png' title='Niveau ".$data['Rating']."' alt='Niveau ".$data['Rating']."'>";
			if($data['Premium'])
				$Premium_txt="<div class='i-flex premium20'></div>";
			else
				$Premium_txt="";
			if($data['Etat'] ==1 and $data['Pays'] ==$country and ($data['Premium'] ==$Premium or !$data['Premium']) and $data['Rating'] <=$Level)
				$Perso_txt="<div class='i-flex led_green' title='Accessible en avion perso pour votre pilote' alt='Accessible en avion perso pour votre pilote'></div>";
			elseif($data['Etat'] ==1 and $data['Pays'] ==$country and ($data['Premium'] ==$Premium or !$data['Premium']) and !$data['Prototype'] and $data['Fin_Prod'] <=$Date_Campagne)
				$Perso_txt="<div class='i-flex led_orange' title='Modèle de réserve accessible en avion perso à cette date' alt='Modèle de réserve accessible en avion perso pour tous'></div>";
			else
				$Perso_txt="<div class='i-flex led_red' title='Pas encore accessible en avion perso pour votre pilote' alt='Pas encore accessible en avion perso pour votre pilote'></div>";				
			if(strlen($data['Nom']) >18)
				$class_name_veh="s-12";
			else
				$class_name_veh="";
			$img_cible_veh="images/avions/vol".$data['ID'].".jpg";
			if(is_file($img_cible_veh))
				$popup_txt="<img src='".$img_cible_veh."' style='width:300px;'>";
			else
				$popup_txt="Image à venir";
			$veh_txt.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
				<fieldset class='veh'>
					<div class='row veh_header'>
						<div class='col-md-2 col-xs-2'>
							<img src='images/".$data['Pays']."20.gif'>
						</div>
						<div class='col-md-7 col-xs-7 name_veh ".$class_name_veh."'>".$data['Nom']."</div>
						<div class='col-md-3 col-xs-3'>
							".$Level_txt."
						</div>
					</div>
					<div class='row veh_body'>
						<div class='col-md-12'>
						<a href='#' class='popup-light'><img src='images/avions/avion".$data['ID'].".gif'><span>".$popup_txt."</span></a>
						</div>
					</div>
					<div class='row veh_footer'>
						<div class='col-md-3 col-xs-3'>".$Premium_txt." ".$Perso_txt."</div>
						<div class='col-md-6 col-xs-6 text-center'>".$data['Engage']."</div>
						<div class='col-md-3 col-xs-3'><a href='avion.php?avion=".$data['ID']."' target='_blank' rel='noreferrer'><div class='help_icon'></div></a></div>
					</div>
				</fieldset></div>";
			$i++;
		}
		echo "<h2>Avions</h2><div class='row'>".$veh_txt."</div>";
	}
	else
		echo "<h6>Désolé, aucun avion ne correspond à votre recherche</h6>";
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
?>