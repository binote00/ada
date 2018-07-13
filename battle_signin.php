<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	if($_SESSION['Pilote_pvp'] >0 and $_SESSION['Distance'] ==0)
	{
		include_once('./jfv_include.inc.php');
		$Battle=Insec($_POST['Battle']);
		$Faction=Insec($_POST['Camp']);
		if($Battle and $Faction)
		{
			include_once('./jfv_inc_pvp.php');
			$_SESSION['Decollage0']=false;
			$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
			$Front=GetFrontPVP($Battle);
			$Avions=GetAvionPVP($Battle,98,$Faction,$Premium);
			if(is_array($Avions))
			{
				//print_r(array_values($Avions));
				foreach($Avions as $Avion)
				{
					$Avions_txt.="<Input type='Radio' name='Avion' value='".$Avion."'>- ".GetAvionIcon($Avion,0,0,0,$Front,"",0,true)."<br>";
				}
				unset($Avions);
				unset($Avion);
			}
			else
				$Avions_txt.="<Input type='Radio' name='Avion' value='126'>- ".GetAvionIcon(126,0,0,0,$Front)."<br>";
			if($Faction ==1)
				$Battle_Pts=GetData("Battle_score","ID",$Battle,"Pts_Bat_Axe");
			else
				$Battle_Pts=GetData("Battle_score","ID",$Battle,"Pts_Bat_Allies");
			echo "<h1>Inscription à la bataille</h1><p class='lead'>Votre faction dispose encore de <b>".$Battle_Pts." Points</b> pour cette bataille</p><div class='row'><div class='col-md-6'><h2><b>Choix de l'avion</b></h2>
			<form action='index.php?view=battle_in' method='post'>".$Avions_txt."
			<input type='hidden' name='Camp' value='".$Faction."'><input type='hidden' name='Battle' value='".$Battle."'>
			<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form></div><div class='col-md-6'><img src='images/avions.jpg'></div></div>
			<a href='index.php?view=battles' class='btn btn-warning' title='Retour'>Annuler</a>";
		}
	}
	else
		echo "<p>Votre pilote n'est pas prêt!</p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>