<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	if($_SESSION['Pilote_pvp'] >0 and $_SESSION['Distance'] ==0)
	{
		include_once('./jfv_include.inc.php');
		$Battle=Insec($_POST['Battle']);
		$Faction=Insec($_POST['Camp']);
		$Type_Mission=Insec($_POST['Type']);
		if($Battle and $Type_Mission and $Faction)
		{
			include_once('./jfv_inc_pvp.php');
			$_SESSION['Decollage0']=false;
			$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
			$Front=GetFrontPVP($Battle);
			$Avions=GetAvionPVP($Battle,$Type_Mission,$Faction,$Premium);
			if(is_array($Avions))
			{
				//print_r(array_values($Avions));
                $Avions_txt.='<div class="row"><div class="col-md-6">';
				foreach($Avions as $Avion)
				{
					$Avions_txt.="<Input type='Radio' name='Avion' value='".$Avion."'>- <a href='avion.php?avion=".$Avion."' target='_blank'>".GetAvionIcon($Avion,0,0,0,$Front,'',0,true)."</a><br>";
                    $i++;
                    if($i%10 ==0)$Avions_txt.='</div><div class="col-md-6">';
				}
                $Avions_txt.='</div></div>';
				unset($Avions);
				unset($Avion);
			}
			else
				$Avions_txt.="<Input type='Radio' name='Avion' value='126'>- ".GetAvionIcon(126,0,0,0,$Front)."<br>";
			echo "<h1>Préparation de la mission</h1><div class='row'><div class='col-md-6'><h2><b>Choix de l'avion</b></h2>
			<form action='index.php?view=takeoff_pvp' method='post'>".$Avions_txt."
			<input type='hidden' name='Camp' value='".$Faction."'><input type='hidden' name='Type_M' value='".$Type_Mission."'><input type='hidden' name='Battle' value='".$Battle."'>
			<p><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form></div><div class='col-md-6'><img src='images/avions.jpg' style='width:100%'></div></div>";
		}
	}
	else
		echo "<p>Une fois le départ en mission confirmé, vous ne pouvez accéder aux autres menus du jeu sous peine de réinitialisation de la mission.</p>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';