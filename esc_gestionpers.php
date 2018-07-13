<?
require_once('./jfv_inc_sessions.php');
/*if(!$PlayerID or $PlayerID == 1)
{
echo"<pre>";
print_r($_POST);
print_r($_SESSION);
echo"</pre>";
}*/
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID > 0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_nomission.inc.php');
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Credits FROM Pilote WHERE ID='$PlayerID'");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				$Unite=$data['Unit'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
		}	
		$result2=mysqli_query($con,"SELECT Commandant,Officier_Adjoint FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
			}
			mysqli_free_result($result2);
		}
		if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint)
		{
            $CT4=4;
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Reputation,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'")
			or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_gestionpers-unit');
            $results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
					$Reputation=$data['Reputation'];
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
            if($results)
            {
                while($data=mysqli_fetch_array($results,MYSQLI_ASSOC)){
                    $Skills_Pil[]=$data['Skill'];
                }
                mysqli_free_result($results);
            }
            if(is_array($Skills_Pil)){
                if(in_array(107,$Skills_Pil))
                    $Organisateur2=true;
                if($Organisateur2){
                    $CT4=2;
                }
            }
			$Pers=floor($Reputation/20000)+1;
			if($Pers >10)$Pers=10;		
			include_once('./menu_escadrille.php');
?>
<h2>Personnel spécialisé</h2>
	<form action="esc_gestionpers1.php" method="post">
	<input type="hidden"  name="Unite"  value="<?=$Unite?>">
	<div class="row"><div class="col-md-6"><table class="table">
		<thead><tr><th>Spécialistes actuels</th></tr></thead>
		<tr><td>
			<?
			if($Pers)
			{ 
				for($p=1;$p<=$Pers;$p++)
				{
					$Pers_img='Pers'.$p;
			?>
					<img src='images/pers_<?echo $$Pers_img;?>.gif' title='Personnel n° <?=$p?> : <?echo GetPers_txt($$Pers_img);?>'><br><b><?=$p?></b>
			<?	}
			}else{echo 'Aucun';}?>
			</td>
		</tr>
	</table></div>
	<div class="col-md-6"><table class="table">
		<thead><tr><th colspan='2'>Affecter un spécialiste <img src='/images/CT<?=$CT4?>.png' title='Montant en Crédits Temps que nécessite cette action'></th></tr></thead>
		<?if($Credits >=$CT4){?>
		<tr>
			<td>Affecter un 
				<select name='Pers_char' class='form-control'>
					<option value='0' selected>Aucun</option>
					<?
					DoUniqueSelect("Personnel","ID","Nom");
					?>
				</select>
			</td>
			<td>
				en tant que personnel n°				
				<select name='Pers_slot' class='form-control' style='width: 100px'>
				<?
					for($ps=1;$ps<=$Pers;$ps++)
					{
				?>
					<option value='<?=$ps?>' selected><?=$ps?></option>
				<?
					}
				?>
				</select>
			</td>
		</tr>
		<?}?>
	</table>
	<input type="submit" class="btn btn-default" value="VALIDER" onclick="this.disabled=true;this.form.submit();"></form>
	</div></div>
<table class="table table-striped">
<thead><tr><th colspan="3">Spécialités</th></tr></thead>
<tr>
<th align="left">Armurier</th><td><img src='images/pers_1.gif'></td><td>Augmente le ravitaillement en munitions (bombes et charges compris) et réduit le coût des actions de (dé)graissage des armes en atelier</td>
</tr>
<tr>
<th align="left">Artilleur</th><td><img src='images/pers_2.gif'></td><td>Augmente l'efficacité de la DCA</td>
</tr>
<tr>
<th align="left">Barman</th><td><img src='images/pers_3.gif'></td><td>Augmente l'efficacité du mess</td>
</tr>
<tr>
<th align="left">Garde</th><td><img src='images/pers_4.gif'></td><td>Augmente les chances d'arrêter un espion ou un saboteur<br>Améliore l'escorte honorant les tombés au champ d'honneur</td>
</tr>
<tr>
<th align="left">Instructeur</th><td><img src='images/pers_5.gif'></td><td>Augmente l'efficacité des formations</td>
</tr>
<tr>
<th align="left">Mécano</th><td><img src='images/pers_6.gif'></td><td>Réduit le coût des actions en atelier et augmente l'efficacité de la réparation des avions personnels</td>
</tr>
<tr>
<th align="left">Médecin</th><td><img src='images/pers_7.gif'></td><td>Augmente l'efficacité de l'infirmerie et permet de recourir aux stimulants</td>
</tr>
<tr>
<th align="left">Officier</th><td><img src='images/pers_9.gif'></td><td>Augmente l'efficacité de potasser le règlement</td>
</tr>
<tr>
<th align="left">Officier de propagande</th><td><img src='images/pers_10.gif'></td><td>Augmente l'efficacité des actions de réputation</td>
</tr>
<tr>
<th align="left">Officier des renseignements</th><td><img src='images/pers_11.gif'></td><td>Augmente l'efficacité des actions de renseignement</td>
</tr>
<tr>
<th align="left">Ouvrier</th><td><img src='images/pers_12.gif'></td><td>Augmente l'efficacité des réparations de la base et réduit le coût de réparation totale de la piste (cumulatif)</td>
</tr>
<tr>
<th align="left">Personnel navigant</th><td><img src='images/pers_13.gif'></td><td>Augmente la compétence des équipages par défaut (IA)</td>
</tr>
<tr>
<th align="left">Pompier</th><td><img src='images/pers_17.gif'></td><td>Augmente la chance de récupérer un avion accidenté</td>
</tr>
<tr>
<th align="left">Prévisionniste</th><td><img src='images/pers_14.gif'></td><td>Augmente l'efficacité de la station météo</td>
</tr>
<tr>
<th align="left">Secrétaire</th><td><img src='images/pers_15.gif'></td><td>Augmente l'efficacité des actions de gestion, hors atelier</td>
</tr>
</table></div>
<?
		}
		else{
			include_once('./menu_escadrille.php');
			PrintNoAccessPil($country,1,2);
		}
	}
	else{
		$titre='MIA';
		$mes='Peut-être la reverrez-vous un jour votre escadrille...';
		$img='<img src="images/unites'.$country.'.jpg">';
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');