<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		$em_ok=false;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Credits,Missions_Max,Equipage FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_at-ply');
        $results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				$Unite=$data['Unit'];
				$Credits=$data['Credits'];
				$Missions_Max=$data['Missions_Max'];
				$Equipage=$data['Equipage'];
			}
			mysqli_free_result($result);
			unset($data);
		}
        if($results)
        {
            while($data=mysqli_fetch_array($results,MYSQLI_ASSOC)){
                $Skills_Pil[]=$data['Skill'];
            }
            mysqli_free_result($results);
        }
		$result=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique,Avion1,Avion2,Avion3,U_Chargeurs,U_Graisse,U_Purge,U_Moteurs,U_Blindage,U_Camo,
		Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_at-unit');
        mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Chargeurs=$data['U_Chargeurs'];
				$Graisse=$data['U_Graisse'];
				$Purge=$data['U_Purge'];
				$U_Moteurs=$data['U_Moteurs'];
				$U_Blindage=$data['U_Blindage'];
				$U_Camo=$data['U_Camo'];
				$Pers1=$data['Pers1'];
				$Pers2=$data['Pers2'];
				$Pers3=$data['Pers3'];
				$Pers4=$data['Pers4'];
				$Pers5=$data['Pers5'];
				$Pers6=$data['Pers6'];
				$Pers7=$data['Pers7'];
				$Pers8=$data['Pers8'];
				$Pers9=$data['Pers9'];
				$Pers10=$data['Pers10'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$Bonus_Pers=1;
		if(is_array($Skills_Pil))
		{
			if(in_array(106,$Skills_Pil))
				$Organisateur1=true;
			if(in_array(130,$Skills_Pil))
				$Pers_Sup=1;
		}
		if(!$Graisse)
			$Atelier.="<img src='images/graisse0.gif' alt='Armes dégraissées' title='Armes dégraissées'>";
		else
			$Atelier.=" <img src='images/graisse1.gif' alt='Armes graissées' title='Armes graissées'>";
		if($Purge)
			$Atelier.=" <img src='images/flaps.gif' alt='Circuits purgés' title='Circuits purgés'>";
		if($U_Moteurs)
			$Atelier.=" <img src='images/moteur.gif' alt='Moteurs réglés' title='Moteurs réglés'>";
		if($U_Blindage)
			$Atelier.=" <img src='images/blindage.gif' alt='Blindage partiel' title='Blindage partiel'>";
		if($U_Camo)
			$Atelier.=" <img src='images/camo.gif' alt='Camouflage temporaire' title='Camouflage temporaire'>";			
		if($Equipage){
			$Eq_Nom=GetData("Equipage","ID",$Equipage,"Nom");
			$Eq_mec=GetData("Equipage","ID",$Equipage,"Mecanique");
		}
		if($Organisateur1){
			$CT_2=1;
			$CT_4=2;
			$CT_8=4;
			$CT_G=1;
		}
		else{
			$CT_2=2;
			$CT_4=4;
			$CT_8=8;
			$CT_G=2;
		}
		$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
		$Personnel=array_count_values($Pers);
		if($Personnel[6] >0 or $Pers_Sup)
		{
			$CT_8-=$Personnel[6]+$Pers_Sup;
			$CT_4-=$Personnel[6]+$Pers_Sup;
			$CT_2-=floor(($Personnel[6]+$Pers_Sup)/2);
			if($CT_8 <1)$CT_8=1;
			if($CT_4 <1)$CT_4=1;
			if($CT_2 <1)$CT_2=1;
		}
		if($Personnel[1] >0 or $Pers_Sup)
		{
			$CT_G-=$Personnel[1]+$Pers_Sup;
			if($CT_G <1)$CT_G=1;
		}		
include_once('./menu_escadrille.php');
?>
<h2>Atelier</h2>
<?if($Credits <1){?>
<div class='alert alert-danger'>Vous ne disposez pas de suffisamment de Crédits Temps pour bénéficier de votre temps libre !</div>
<?}else{?>
	<form action='esc_atelier2.php' method='post'>
	<input type='hidden' name='CT8' value="<?echo $CT_8;?>">
	<input type='hidden' name='CT4' value="<?echo $CT_4;?>">
	<input type='hidden' name='CT2' value="<?echo $CT_2;?>">
	<p><?echo $Atelier;?></p>
		<table class='table'>
		<?if($PlayerID >0 and $Commandant ==$PlayerID){?>				
		<thead><tr><th>Mécano d'unité <a href='#' class='popup'><img src='images/help.png'><span>Ces modifications s'appliqueront aux avions de série uniquement!</span></a></th></tr></thead>
		<tr><td>
				<?if($Credits >=$CT_G){?>
				<Input type='Radio' name='Action' value='1' title="Diminue le risque d'enrayage à basse altitude, augmente à haute altitude"><img src='/images/CT<?echo $CT_G;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Graisser les armes<br>
				<Input type='Radio' name='Action' value='2' title="Diminue le risque d'enrayage à haute altitude, augmente à basse altitude"><img src='/images/CT<?echo $CT_G;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Dégraisser les armes<br>
				<Input type='Radio' name='Action' value='3' title="Diminue le risque de stress des commandes et du train"><img src='/images/CT<?echo $CT_2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Purger les systèmes hydrauliques<br>
				<Input type='Radio' name='Action' value='4' title="Pris sur le stock de l'unité"><img src='/images/CT<?echo $CT_2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Doubler la capacité des chargeurs des mitrailleurs<br>
				<?}if($Credits >=$CT_4){?>
				<Input type='Radio' name='Action' value='5' title="Diminue le risque de stress moteur et d'incidents mécaniques"><img src='/images/CT<?echo $CT_4;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Optimiser les réglages des moteurs<br>
				<Input type='Radio' name='Action' value='6' title="Camouflage temporaire adapté à la région"><img src='/images/CT<?echo $CT_4;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Appliquer un camouflage temporaire<br>
				<Input type='Radio' name='Action' value='7' title="Protège le pilote d'une blessure mortelle"><img src='/images/CT<?echo $CT_4;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Installer une plaque de blindage rudimentaire<br>
				<?}?>
		</td></tr>
		</table>
		<?}if($Equipage){?>
		<table class='table'>
		<thead><tr><th>Votre mécano <?echo $Eq_Nom;?> <a href='#' class='popup'><img src='images/help.png'><span>Ces modifications s'appliqueront pour votre prochaine mission, si vous utilisez un avion de série uniquement!</span></a></th></tr></thead>
		<tr><td align='left'>
				<?if($Credits >=$CT_8 and $Eq_mec >19){?>
				<Input type='Radio' name='Action' value='41' title='Cette action, si réussie, permet de sauver un avion endommagé dans les limites des capacités de production'><img src='/images/CT<?echo $CT_8;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Charger <?echo $Eq_Nom;?> de réparer un <?echo GetData("Avion","ID",$Avion1,"Nom");?><br>
				<Input type='Radio' name='Action' value='42' title='Cette action, si réussie, permet de sauver un avion endommagé dans les limites des capacités de production'><img src='/images/CT<?echo $CT_8;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Charger <?echo $Eq_Nom;?> de réparer un <?echo GetData("Avion","ID",$Avion2,"Nom");?><br>
				<Input type='Radio' name='Action' value='43' title='Cette action, si réussie, permet de sauver un avion endommagé dans les limites des capacités de production'><img src='/images/CT<?echo $CT_8;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Charger <?echo $Eq_Nom;?> de réparer un <?echo GetData("Avion","ID",$Avion3,"Nom");?><br>
				<?}if($Credits >=1){?>
				<Input type='Radio' name='Action' value='12' title="Diminue le risque d'enrayage à basse altitude, augmente à haute altitude"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Graisser les armes<br>
				<Input type='Radio' name='Action' value='13' title="Diminue le risque d'enrayage à haute altitude, augmente à basse altitude"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Dégraisser les armes<br>
				<Input type='Radio' name='Action' value='14' title="Diminue le risque de stress des commandes et du train"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Purger le système hydraulique<br>
				<Input type='Radio' name='Action' value='15' title="Pris sur le stock de l'unité"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Doubler la capacité des chargeurs des mitrailleurs<br>
				<?}if($Credits >=$CT_2){?>
				<Input type='Radio' name='Action' value='16' title="Diminue le risque de stress moteur et d'incidents mécaniques"><img src='/images/CT<?echo $CT_2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Optimiser les réglages du moteur<br>
				<Input type='Radio' name='Action' value='17' title="Camouflage temporaire adapté à la région"><img src='/images/CT<?echo $CT_2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Appliquer un camouflage temporaire<br>
				<Input type='Radio' name='Action' value='18' title="Protège le pilote d'une blessure mortelle"><img src='/images/CT<?echo $CT_2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Installer une plaque de blindage rudimentaire<br>
				<?}?>
		</td></tr></table>
		<?}?>
		<table class='table'>
		<thead><tr><th>Renseignement</th></tr></thead>
		<tr><td align='left'>
				<?if($Credits >=1){?>
				<Input type='Radio' name='Action' value='8' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rendre visite à l'armurier de l'escadrille<br>
				<Input type='Radio' name='Action' value='9' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rendre visite au mécano de l'escadrille<br>
				<Input type='Radio' name='Action' value='11' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Examiner les avions de l'escadrille<br>
				<?}?>
		</td></tr></table>
	<input type='submit' value='VALIDER' class="btn btn-default" onclick='this.disabled=true;this.form.submit();'>
	</form>
<?}
	}
	else{
		echo 'Peut-être la reverrez-vous un jour votre escadrille...';
		echo "<table border='1' align='center'><tr><td><img src='images/unites".$country.".jpg'></td></tr></table>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';