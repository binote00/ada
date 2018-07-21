<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1)
{
	include_once('./jfv_txt.inc.php');
    include_once __DIR__ . '/view/menu_infos.php';
    $Pays = Insec($_POST['land']);
	$Type = Insec($_POST['type']);
	$Tri = Insec($_POST['Tri']);
	if(!$Tri)
		$Tri=1;
	if($Pays =="all")
		$Pays="%";
	if($Type =="all")
		$Type="%";		
	//$Admin=false;
?>
<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped table-condensed'>
	<thead><tr>
		<th>Nom</th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="1"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Pays'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="2"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Reput'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="14"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Date'></form></th>
        <th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="16"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Prod'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="4"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='HP'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="3"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Blindage'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="5"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Vis'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="6"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Detec'></form></th>
        <th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="17"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Tac'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="7"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Vit'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="12"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Auto'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="15"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Carbu'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="13"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Fiab'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="9"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Portée'></form></th>
		<th>Arme MG</th>
		<th>Arme AT</th>
		<th>Soutien</th>
		<th>DCA</th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="8"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Charge'></form></th>
		<?if($Admin){?>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="10"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Type'></form></th>
		<th><form action='index.php?view=aveh' method='post'><input type='hidden' name='Tri' value="11"><input type='hidden' name='land' value="<?=$Pays;?>"><input type='hidden' name='type' value="<?=$Type;?>"><input type='Submit' value='Cat'></form></th>
		<?}?>
	</tr></thead>
<?
	switch($Tri)
	{
		case 1:
			$Tri="Pays";
		break;
		case 2:
			$Tri="Reput";
		break;
		case 3:
			$Tri="Blindage_f";
		break;
		case 4:
			$Tri="HP";
		break;
		case 5:
			$Tri="Taille";
		break;
		case 6:
			$Tri="Detection";
		break;
		case 7:
			$Tri="Vitesse";
		break;
		case 8:
			$Tri="Charge";
		break;
		case 9:
			$Tri="Portee";
		break;
		case 10:
			$Tri="Type";
		break;
		case 11:
			$Tri="Categorie";
		break;
		case 12:
			$Tri="Fuel";
		break;
		case 13:
			$Tri="Fiabilite";
		break;
		case 14:
			$Tri="DATE(`Date`)";
		break;
		case 15:
			$Tri="Carbu_ID";
		break;
		case 16:
			$Tri="Production";
		break;
        case 17:
            $Tri="Tourelle+Radio";
         break;
	}
	if($Pays >9 or $Pays ==1)
	{
		if($Type ==100)
			$query="SELECT * FROM Cible WHERE Categorie=5 AND Type=0 AND Pays='$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
        elseif($Type ==94)
            $query="SELECT * FROM Cible WHERE Categorie=5 AND Type=94 AND Pays='$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
		else
			$query="SELECT * FROM Cible WHERE Type LIKE '$Type' AND Pays='$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
	}
	elseif($Type ==100)
		$query="SELECT * FROM Cible WHERE Categorie=5 AND Type=0 AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
    elseif($Type ==94)
        $query="SELECT * FROM Cible WHERE Categorie=5 AND Type=94 AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
	else
		$query="SELECT * FROM Cible WHERE Type LIKE '$Type' AND Pays LIKE '$Pays' AND Unit_ok=1 ORDER BY ".$Tri." DESC,Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$AT_Perf=GetData("Armes","ID",$data['Arme_AT'],"Perf");
			$Flags="";
			$CT=false;
			$HP_max=false;
			$Inf_txt=GetData("Armes","ID",$data['Arme_Inf'],"Nom");
			$Art_txt=GetData("Armes","ID",$data['Arme_Art'],"Nom");
			$AT_txt=GetData("Armes","ID",$data['Arme_AT'],"Nom");
			$AA_txt=GetData("Armes","ID",$data['Arme_AA'],"Nom");
			if($Admin)
			{
				$veh_txt=" (".$data['ID'].")";
				$Art_Dg=GetData("Armes","ID",$data['Arme_Art'],"Degats");
				$AT_Dg=GetData("Armes","ID",$data['Arme_AT'],"Degats");
				if($data['mobile'] ==2 or $data['mobile'] ==6)$CT=1;
				$CT+=round((($data['Blindage_f']*50)/200)+($data['Portee']/1000)+($data['Vitesse']/50)+($data['Detection']/60)+($data['Charge']/1000)+($data['Fuel']/100)+(($Art_Dg+$AT_Dg)/1000)-($data['Taille']/50)+($data['Fiabilite']/10),1); //+Degats/1000
				$CT='/'.$CT;
				$mobile_txt=' ('.$data['mobile'].')';
				$HP_max='<br>'.round(($data['Blindage_f']*50)+100);
				$Inf_txt.='<br>('.$data['Arme_Inf'].' - '.$data['Arme_Inf_mun'].'muns - '.GetData("Armes","ID",$data['Arme_Inf'],"Degats")*GetData("Armes","ID",$data['Arme_Inf'],"Multi").'Dg)';
				$Art_txt.='<br>('.$data['Arme_Art'].' - '.$data['Arme_Art_mun'].'muns - '.$Art_Dg.'Dg)';
				$AT_txt.='<br>('.$data['Arme_AT'].' - '.$data['Arme_AT_mun'].'muns - '.$AT_Dg.'Dg)';
				$AA_txt.='<br>('.$data['Arme_AA'].' - '.$data['Arme_AA_mun'].'muns - '.GetData("Armes","ID",$data['Arme_AA'],"Degats")*GetData("Armes","ID",$data['Arme_AA'],"Multi").'Dg)';
                $Bonus_Tactique=(($data['Radio']*5)+($data['Tourelle']*5));
			}
			if($data['Type']==21)
				$data['Charge']=$data['Esc']." Esc";
			elseif(!$data['Charge'] and $Admin)
				$data['Charge']=$AT_Perf.'mm';
			else
				$data['Charge'].="kg";
			if($data['Premium'])$Flags.="<div class='i-flex premium20' title='Premium'>";
			if($data['Lend'])$Flags.="<img src='images/lendlease.png' title='Lend-Lease' alt='Lend-Lease'>";
			echo "<tr><td align='left'>".GetVehiculeIcon($data['ID'],$data['Pays'],0,0,0,$data['Nom'],$Battle=false,$Popup=true)."<br>".$veh_txt."</td>
				<td><img src='images/".$data['Pays']."20.gif'>".$Flags."</td>
				<td>".$data['Reput'].$CT."</td>
				<td>".$data['Date']."</td>
				<td class='text-center'>".$data['Production']."</td>
				<td>".$data['HP'].$HP_max."</td>
				<td>".$data['Blindage_f']."/".$data['Blindage_l']."/".$data['Blindage_a']."/".$data['Blindage_t']."</td>
				<td>".$data['Taille']."</td>
				<td>".$data['Detection']."</td>
				<td>".$Bonus_Tactique."</td>
				<td>".$data['Vitesse']."km/h".$mobile_txt."</td>
				<td>".$data['Fuel']."km"."</td>
				<td>".$data['Carbu_ID']."</td>
				<td>".$data['Fiabilite']."</td>
				<td>".$data['Portee']."m</td>
				<td>".$Inf_txt."</td>
				<td>".$AT_txt."</td>
				<td>".$Art_txt."</td>
				<td>".$AA_txt."</td>
				<td>".$data['Charge']."</td>";
			if($Admin)echo "<td>".$data['Type']."</td><td>".$data['Categorie']."</td>";
			echo '</tr>';
		}
	}?>
	<tr><thead>
		<th>Nom</th>
		<th>Pays</th>
		<th>Reput</th>
		<th>Date</th>
		<th>Robustesse</th>
		<th>Blindage</th>
		<th>Taille</th>
		<th>Détec</th>
		<th>Vitesse</th>
		<th>Autonomie</th>
		<th>Carbu</th>
		<th>Fiab</th>
		<th>Portée</th>
		<th>Arme MG</th>
		<th>Arme AT</th>
		<th>Soutien</th>
		<th>DCA</th>
		<th>Charge</th>
		<?if($Admin){?>
		<th>Type</th>
		<th>Cat</th><?}?>
		<th>Production</th>
	</thead></tr></table></div><hr>
<?
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
?>
