<?php
include_once '../jfv_include.inc.php';
include_once '../jfv_txt.inc.php';
include_once '../view/menu_infos.php';
$Tri = Insec($_POST['Tri']);
if(!$Tri)$Tri=1;
?>
	<h2>Les infrastructures</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
	<thead><tr>
		<th>Nom</th>
		<th><form action='index.php?view=infos/cibles' method='post'><input type='hidden' name='Tri' value="2"><input type='submit' class="btn btn-sm btn-default" value='Objectif'></form></th>
		<th><form action='index.php?view=infos/cibles' method='post'><input type='hidden' name='Tri' value="3"><input type='submit' class="btn btn-sm btn-default" value='Protection'></form></th>
		<th><form action='index.php?view=infos/cibles' method='post'><input type='hidden' name='Tri' value="4"><input type='submit' class="btn btn-sm btn-default" value='Robustesse'></form></th>
		<th><form action='index.php?view=infos/cibles' method='post'><input type='hidden' name='Tri' value="5"><input type='submit' class="btn btn-sm btn-default" value='Camouflage'></form></th>
		<th><form action='index.php?view=infos/cibles' method='post'><input type='hidden' name='Tri' value="6"><input type='submit' class="btn btn-sm btn-default" value='DCA'></form></th>
		<th>Détail</th>
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
			$Tri="Defense";
		break;
		case 4:
			$Tri="HP";
		break;
		case 5:
			$Tri="Camouflage";
		break;
		case 6:
			$Tri="Arme";
		break;
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Pays,Nom,Defense,Arme,HP,Reput,Camouflage FROM Cible WHERE Unit_ok=0 AND Pays=0 ORDER BY $Tri DESC, Nom ASC");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			if($data['Reput'] >29)
				$Importance="Stratégique";
			elseif($data['Reput'] >19)
				$Importance="Majeur";
			elseif($data['Reput'] >9)
				$Importance="Principal";
			elseif($data['Reput'] >4)
				$Importance="Secondaire";
			elseif($data['Reput'] >1)
				$Importance="Mineur";
			else
				$Importance="Insignifiant";
			if($data['Defense'] >39)
				$Defense="Excellente";
			elseif($data['Defense'] >29)
				$Defense="Très bonne";
			elseif($data['Defense'] >19)
				$Defense="Bonne";
			elseif($data['Defense'] >14)
				$Defense="Moyenne";
			elseif($data['Defense'] >7)
				$Defense="Légère";
			elseif($data['Defense'] >0)
				$Defense="Très légère";
			else
				$Defense="Aucune";
			if($data['HP'] >9999)
				$HP="Extraordinaire";
			elseif($data['HP'] >4999)
				$HP="Excellente";
			elseif($data['HP'] >1999)
				$HP="Très bonne";
			elseif($data['HP'] >999)
				$HP="Bonne";
			elseif($data['HP'] >400)
				$HP="Moyenne";
			elseif($data['HP'] >200)
				$HP="Faible";
			elseif($data['HP'] >100)
				$HP="Très faible";
			else
				$HP="Désastreuse";
			$Camouflage=$data['Camouflage'];
			if($Camouflage >49)
				$Camouflage_txt="Supérieur";
			elseif($Camouflage >39)
				$Camouflage_txt="Amélioré";
			elseif($Camouflage >29)
				$Camouflage_txt="Avancé";
			elseif($Camouflage >19)
				$Camouflage_txt="Classique";
			elseif($Camouflage >9)
				$Camouflage_txt="Basique";
			else
				$Camouflage_txt="Faible";
			$DCA=GetData("Armes","ID",$data['Arme'],"Calibre");
			if($DCA >89)
				$DCA_txt="Excellente";
			elseif($DCA >74)
				$DCA_txt="Très bonne";
			elseif($DCA >39)
				$DCA_txt="Bonne";
			elseif($DCA >19)
				$DCA_txt="Moyenne";
			elseif($DCA >11)
				$DCA_txt="Légère";
			elseif($DCA >7)
				$DCA_txt="Très légère";
			else
				$DCA_txt="Aucune";
			/*onclick="window.open('infra.php?infra=<?=$data['ID']?>','Fiche','width=1024,height=800,scrollbars=1')*/
            /*$modal.='<div class="modal fade" id="modal-pont" tabindex="-1" role="dialog" aria-labelledby="'.$data['Nom'].'" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            '.$data['Nom'].'
                        </div>
                        <div class="modal-body">
                            <img src="images/vehicules/vehicule'.$data['ID'].'.gif">                        
                            <p>Le pont permet aux unités terrestres de traverser un fleuve rapidement. 
                            <br>Si le pont est détruit, les unités ennemies ne peuvent se rendre sur les autres zones du lieu.
                            <br>Un pont en bon état est nécessaire pour pouvoir atteindre la caserne d\'un lieu, et ainsi le revendiquer.
                            <br>Les troupes du génie terrestre peuvent réparer ou saboter un pont.</p>
                        </div>
                    </div>
                </div>
            </div>';*/
?>		<tr>
			<td><img src="images/vehicules/vehicule<?=$data['ID']?>.gif" title="<?=$data['Nom']?>"></td>
			<td><?=$Importance?></td>
			<td><?=$Defense?></td>
			<td><?=$HP?></td>
			<td><?=$Camouflage_txt?></td>
			<td><?=$DCA_txt?></td>
			<td><button type="button" class="btn btn-sm btn-primary" onclick="window.open('infra.php?infra=<?=$data['ID']?>','Fiche','width=1024,height=800,scrollbars=1')">Voir la fiche</button></td>
		</tr>
<?		}
	}
	echo '</table></div>'.$modal;
