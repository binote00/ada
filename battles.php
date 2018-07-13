<?
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Pilote_pvp >0 or $Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Battle=Insec($_GET['i']);
	$Axe_Nbr=0;
	$Allies_Nbr=0;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Premium,Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	$result=mysqli_query($con,"SELECT ID,Nom,Pays,Front_sandbox,Avion_Sandbox FROM Pilote_PVP WHERE Front_sandbox='$Battle' AND Avion_Sandbox >0");
	$result2=mysqli_query($con,"SELECT ID,Nom,Pays,Front,Division,Note FROM Officier_PVP WHERE Front='$Battle' AND Division >0");
	$result3=mysqli_query($con,"SELECT Bat_Date,Pts_Bat_Axe,Pts_Bat_Allies,Allies_inscrits,Axe_inscrits,DATE_FORMAT(Bat_Date,'%d-%m-%Y %H:%i') as Date_txt FROM gnmh_aubedesaiglesnet2.Battle_score WHERE ID='$Battle'");
	mysqli_close($con);
    if($result)
    {
        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
        {
            $Premium=$data['Premium'];
            $Admin=$data['Admin'];
        }
        mysqli_free_result($result);
    }
	if($result3)
	{
		while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			$Depart=$data3['Bat_Date'];
			$Date_txt=$data3['Date_txt'];
			$Pts_Bat_Axe=$data3['Pts_Bat_Axe'];
			$Pts_Bat_Allies=$data3['Pts_Bat_Allies'];
			$Allies_inscrits=$data3['Allies_inscrits'];
			$Axe_inscrits=$data3['Axe_inscrits'];
		}
		mysqli_free_result($result3);
	}
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			if($data2['ID'] ==$Officier_pvp and $data2['Division'] >0)
				$block_o=true;
			if($data2['Pays'] ==1)
			{
				$Off_Axe.=$data2['Nom']." ".GetVehiculeIcon($data2['Note'],0,0,0,0,"",true)."<br>";
				$Axe_Nbr++;
			}
			elseif($data2['Pays'] ==2)
			{
				$Off_Allies.=$data2['Nom']." ".GetVehiculeIcon($data2['Note'],0,0,0,0,"",true)."<br>";
				$Allies_Nbr++;
			}
		}
		mysqli_free_result($result2);
	}
	if($result4)
	{
		while($data=mysqli_fetch_array($result4,MYSQLI_ASSOC))
		{
			if($data['ID'] ==$Pilote_pvp and $data['Avion_Sandbox'] >0)
				$block_p=true;
			if($data['Pays'] ==1)
			{
				$Pil_Axe.=$data['Nom']." ".GetAvionIcon($data['Avion_Sandbox'],0,0,0,0,"",false,true)."<br>";
				$Axe_Nbr++;
			}
			elseif($data['Pays'] ==2)
			{
				$Pil_Allies.=$data['Nom']." ".GetAvionIcon($data['Avion_Sandbox'],0,0,0,0,"",false,true)."<br>";
				$Allies_Nbr++;
			}
		}
		mysqli_free_result($result4);
	}
    $Cible=GetCiblePVP($Battle);
    $Front=GetFrontPVP($Battle);
    $Avions=GetAvionPVP($Battle,99,1,$Premium);
    $Vehs=GetVehPVP($Battle);
    if($Admin)$Legend=true;
    $i=0;
    if(is_array($Avions))
    {
        //print_r(array_values($Avions));
        foreach($Avions as $Avion)
        {
            if(!$i%2)$Avions_txt.='<tr>';
            $Avions_txt.='<td>'.GetAvionIcon($Avion,0,0,0,$Front,"",$Legend,true).'</td>';
            if($i&1)$Avions_txt.='</tr>';
            $i++;
        }
        unset($Avions);
        unset($Avion);
    }
    $i=0;
    if(is_array($Vehs))
    {
        //print_r(array_values($Avions));
        foreach($Vehs as $Veh)
        {
            if(!$i%2)$Veh_txt.='<tr>';
            $Veh_txt.='<td>'.GetVehiculeIcon($Veh,0,0,0,0,"",true).'</td>';
            if($i&1)$Veh_txt.='</tr>';
            $i++;
        }
        unset($Vehs);
        unset($Veh);
    }
	if($Pilote_pvp >0 and !$block_p)
		$page='battle_signin';
	elseif($Officier_pvp >0 and !$block_o)
		$page='battle_signino';
	else
		$page='battles';
	$date=date('Y-m-d G:i');
	$titre='Bataille historique';
	$img="<img src='images/battle/Battle".$Battle.".jpg'><br>
	<form action='index.php?view=em_city_combats_pvp' method='post'><input type='hidden' name='Battle' value='1'><input type='hidden' name='Camp' value='1'><input type='Submit' value='Résultat' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
	if($Battle ==1)
		$intro="<div class='alert alert-warning'>Le 10 mai 1940, lors de l'invasion de la Belgique et des Pays-Bas par l'armée allemande, les ponts sur la Meuse et sur le canal Albert représentaient un objectif de première importance.
		<br>L'Aéronautique Militaire belge, l'Armée de l'Air française et la Royal Air Force britannique tentèrent tour à tour de détruire ces ponts, défendus par la Luftwaffe et la FlaK allemande.</div>";
	elseif($Battle ==2)
		$intro="<div class='alert alert-warning'>Le 12 mai 1940, le XVI.Armee-Korps allemand du General Höpner progressant dans la plaine hesbignonne rencontre le corps de cavalerie français du Général Prioux couvrant le flanc nord de l'armée alliée.
		<br>Les alliés tentèrent de ralentir l'avance allemande afin de permettre au reste de l'armée de s'établir le long de la ligne de défense Dyle-Namur.</div>";
	if($date <$Depart)
	{
		$inscri_ok=true;
		$intro.="<h3>Prochaine bataille : ".$Date_txt."</h3>";
	}
	elseif($Allies_inscrits <2 or $Axe_inscrits <2)
	{
		$inscri_ok=true;
		$con=dbconnecti(2);
		$reset=mysqli_query($con,"UPDATE Battle_score SET Bat_Date=DATE_ADD(NOW(),INTERVAL 5 MINUTE) WHERE ID='$Battle'");
		mysqli_close($con);
		$intro.="<h3>Prochaine bataille : ".$Date_txt."</h3>";
	}
	else
	{
		$inscri_ok=false;
		$intro.='<h3>La bataille est en cours!</h3>';
	}
	$intro.="<div class='alert alert-danger'>Ce mode de jeu est actuellement en test.<br>En tant que testeur, vous êtes invité à signaler tout bug ou à faire part de vos remarques sur le forum</div><div class='row'><div class='col-md-6'><h2>".$Pts_Bat_Allies."/200 Points</h2>";
	if($Allies_Nbr <=$Axe_Nbr and $Pts_Bat_Allies >0 and $inscri_ok)
		$intro.="<form action='index.php?view=".$page."' method='post'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='2'><input type='Submit' value='Inscription Alliés' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
	$intro.="</div><div class='col-md-6'><h2>".$Pts_Bat_Axe."/200 Points</h2>";
	if($Axe_Nbr <=$Allies_Nbr and $Pts_Bat_Axe >0 and $inscri_ok)
		$intro.="<form action='index.php?view=".$page."' method='post'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='1'><input type='Submit' value='Inscription Axe' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
	$intro.="</div></div><h2>Pilotes inscrits pour cette bataille</h2><table class='table'><thead><tr><th>Alliés</th><th>Axe</th></tr></thead><tr><td>".$Pil_Allies."</td><td>".$Pil_Axe."</td></tr></table>
	<h2>Officiers inscrits pour cette bataille</h2><table class='table'><thead><tr><th>Alliés</th><th>Axe</th></tr></thead><tr><td>".$Off_Allies."</td><td>".$Off_Axe."</td></tr></table>";
	if($Pilote_pvp)
		$mes="<div class='alert alert-info'>Ce scénario vous permet de prendre les commandes d'avions suivants</div><table class='table'>".$Avions_txt."</table>";
	else
		$mes="<div class='alert alert-info'>Ce scénario vous permet de commander les unités suivantes</div><table class='table'>".$Veh_txt."</table>";
	include_once('./default.php');
}