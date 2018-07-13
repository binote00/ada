<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$ID=Insec($_GET['pilote']);
	$mes="<h1>Tableau des Bombardements</h1>
	<table class='table table-hover'>
		<thead><tr>
		<th>Date</th>
		<th>Unité</th>
		<th>Avion</th>
		<th>Cible détruite</th>
		<th>Lieu</th></tr></thead>";
		$messagesParPage=25;
		$con=dbconnecti();
		$ID=mysqli_real_escape_string($con,$ID);
		$query=mysqli_query($con,"SELECT COUNT(*) AS total FROM Bombardement WHERE Joueur='$ID'");
		$data=mysqli_fetch_assoc($query);
		$total=$data['total'];
		$nombreDePages=ceil($total/$messagesParPage);		
		if(isset($_GET['page']))
		{
			 $pageActuelle=intval($_GET['page']);			 
			 if($pageActuelle>$nombreDePages) 
				  $pageActuelle=$nombreDePages;
		}
		else 
			 $pageActuelle=1;  
		$premiereEntree=($pageActuelle-1)*$messagesParPage;
		$query2=mysqli_query($con,"SELECT DISTINCT `Date`,b.Nom,b.Cible_id,b.Avion,b.Unite,a.Nom AS Avion_win,u.Nom AS Unite_win,l.Nom AS Lieu_nom,l.Pays AS Lieu_pays,u.Pays AS Unite_pays
		FROM Bombardement as b
		INNER JOIN Avion AS a ON b.Avion=a.ID
		INNER JOIN Unit AS u ON b.Unit=u.ID
		INNER JOIN Lieu AS l ON b.Lieu=l.ID
		WHERE b.Joueur='$ID' ORDER BY b.ID DESC LIMIT ".$premiereEntree.", ".$messagesParPage."");
		if($query2)
		{
			while($data2=mysqli_fetch_assoc($query2))
			{
				$Date=substr($data2['Date'],0,16);
				$Cible_detruite=ucfirst($data2['Nom']);
				$Avion_win=$data2['Avion_win'];
				$Unite_win=$data2['Unite_win'];
				$Lieu=$data2['Lieu_nom'];
				$Lieu_Pays=$data2['Lieu_pays'];				
				$Cible_img="images/vehicules/vehicule".$data2['Cible_id'].".gif";
				$Avion_img="images/avions/avion".$data2['Avion'].".gif";
				$Avion_unit_img="images/unit/unit".$data2['Unite']."p.gif";
				if(is_file($Cible_img))
					$Cible_detruite="<img src='".$Cible_img."' title='".$Cible_detruite."'>";
				if(is_file($Avion_img))
					$Avion_win="<img src='".$Avion_img."' title='".$Avion_win."'>";
				if(is_file($Avion_unit_img))
					$Unite_win="<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
				if($country ==$data2['Unite_pays'] or $Renseignement >200 or $PlayerID ==1)
					$Infos_show=true;
				else
					$Infos_show=false;
				$mes.="<tr>
					<td>".$Date."</td>
					<td>".$Unite_win."</td>
					<td>".$Avion_win."</td>
					<td>".$Cible_detruite."</td>
					<td>".$Lieu."</td>
				</tr>";
			}
			$mes.='<p align="center">Page : ';
			for($i=1;$i<=$nombreDePages;$i++)
			{
				 if($i==$pageActuelle)
					$mes.=' [ '.$i.' ] '; 
				 else
					  $mes.='<a href="bombs.php?pilote='.$ID.'&page='.$i.'">'.$i.'</a>';
			}
			$mes.='</p></table>';
			include_once('./default_blank.php');
		}
		else
			echo "<h6>Désolé, aucun bombardement enregistrée à ce jour.</h6>";
}
?>