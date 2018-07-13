<?
$country=$_SESSION['country'];
$PlayerID=$_SESSION['PlayerID'];
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if(!$Front or $Front ==5)
{
	if($OfficierEMID >0)
		$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
	elseif($PlayerID >0)
		$Front=GetData("Pilote","ID",$PlayerID,"Front");
	elseif($OfficierID >0)
		$Front=GetData("Officier","ID",$OfficierID,"Front");
	else
		$Front=12;
}
if($Front ==3)
	$Front_txt='Pacifique';
elseif($Front ==2)
	$Front_txt='Méditerranée';
elseif($Front ==4)
    $Front_txt='Nord';
elseif($Front ==5)
    $Front_txt='Arctique';
elseif($Front ==12)
    $Front_txt='Réserve';
elseif($Front ==99)
    $Front_txt='Planification Stratégique';
elseif($Front ==0)
    $Front_txt='Ouest';
elseif($Front ==1)
	$Front_txt='Est';
else
	$Front_txt='Erreur';
//$Etat_Major=Get_EM($country);
//$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
if($Front ==99)
	echo "<h1><img src='images/flag".$country."p.jpg'> ".$Front_txt."</h1>";
else
{
	if($Unitet)
		$titre="<img src='images/flag".$country."p.jpg'> Front ".$Front_txt;
	else
		echo "<h1><img src='images/flag".$country."p.jpg'> Front ".$Front_txt."</h1>";
}
/*<div align="center">
	<table border="0" cellspacing="1" cellpadding="1">
		<tr><th colspan="7"><?echo $Date_Campagne;?></th></tr>
		<tr><td colspan='7'><img src="images/flag<? echo $country; ?>.jpg" title="Emblême"></td></tr>
		<tr><th colspan="7">Front <?echo $Front_txt;?></th></tr>
		<tr><th colspan="7"><a class='bouton' title="Accès réservé aux membres du Staff" href='index.php?view=em_staff'><?echo $Etat_Major;?></a></td>
		<tr>
			<td width="150px">
			<a class='bouton' title="Alertes Radar" href='index.php?view=em_radar'>Alertes Radar</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Tableau des missions" href='index.php?view=em_missions'>Tableau des Missions</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Rapports sur les différentes unités aériennes" href='index.php?view=rapports'>Rapports d'unités</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Rapports de l'état des infrastructures" href='index.php?view=villes'>Rapports des villes</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Rapports de production" href='index.php?view=em_production0'>Production</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Inventaires des dépôts" href='index.php?view=em_depots'>Dépôts</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Compte-rendu des Opérations" href='index.php?view=missions_cr'>Compte-rendu Op</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Evènements historiques futurs" href='index.php?view=events_histo'>Historique</a>
			</td>
		</tr>
		<tr>
			<td width="150px">
			<a class='bouton' title="Liste des pilotes" href='index.php?view=em_personnel'>Liste des Effectifs</a>
			</td>
			<td width="150px">
			<a class='bouton' title="En mission" href='index.php?view=em_online'>En mission</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Missions Commando" href='index.php?view=em_commando'>Commandos</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Pilotes MIA" href='index.php?view=em_mia'>Pilotes MIA</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Pilotes en Formation" href='index.php?view=em_formation'>Formations</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Demandes de mutation" href='index.php?view=em_mutation'>Mutations</a>
			</td>
			<td width="150px">
			<a class='bouton' title="Horaires des disponibilités des pilotes" href='index.php?view=em_calendrier'>Horaires</a>
			</td>
			<?if($PlayerID == 1){?>
			<td width="150px">
			<a class='bouton' title="Liste des troupes présentes sur le front" href='index.php?view=troupes'>Troupes</a>
			</td><?}?>
		</tr>
	</table>
</div>*/