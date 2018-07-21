<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['Pilote_pvp'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	?>
	<h1>Tableau de Chasse PVP</h1>
	<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Cycle</th>
			<th>Lieu</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Pilote crédité</th>
			<th>Avion</th>
			<th>Avion Abattu</th>
			<th>Pilote Abattu</th>
			<th>Unité</th>
			<th>Pays</th>
		</tr></thead>
	<?
	$messagesParPage=50;
	$con=dbconnecti();
	$query=mysqli_query($con,'SELECT COUNT(*) AS total FROM Chasse WHERE PVP IN (2,3)');
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
	$query2=mysqli_query($con, 'SELECT * FROM Chasse WHERE (PVP=2) OR (PVP=3 AND Arme_win >0) ORDER BY ID DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'');
	while($data2=mysqli_fetch_assoc($query2))
	{
		$Date=substr($data2['Date'],0,16);
		$Avion_win=GetData("Avion","ID",$data2['Avion_win'],"Nom");
		$Unite_win=GetData("Unit","ID",$data2['Unite_win'],"Nom");
		$Pays_win=GetData("Unit","ID",$data2['Unite_win'],"Pays");
		$Unite_loss=GetData("Unit","ID",$data2['Unite_loss'],"Nom");
		$Pays_loss=GetData("Unit","ID",$data2['Unite_loss'],"Pays");
		$Lieu=GetData("Lieu","ID",$data2['Lieu'],"Nom");
		if(!$Lieu)$Lieu="Inconnu";
		$as_win=false;
		$as_loss=false;
		$Avion_img_win=false;
		$Avion_img_loss=false;
		$Pilote_win="<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
		$Pilote_loss="<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";
		$Avion_loss=GetData("Avion","ID",$data2['Avion_loss'],"Nom");				
			//Profil avion Joueur
			//$Avion_img="images/avionj".$data2['Joueur_win'].".gif";
			if(!is_file($Avion_img_win))
				$Avion_img_win="images/avions/avion".$data2['Avion_win'].".gif";
			if(!is_file($Avion_img_loss))
				$Avion_img_loss="images/avions/avion".$data2['Avion_loss'].".gif";
			if(!$as_win and !$as_loss)
			{
				$Avion_img_loss="images/avions/avion".$data2['Avion_loss'].".gif";
				$Avion_img_win="images/avions/avion".$data2['Avion_win'].".gif";
			}
			$Avion_unit_win_img="images/unit/unit".$data2['Unite_win']."p.gif";
			$Avion_unit_loss_img="images/unit/unit".$data2['Unite_loss']."p.gif";
			if(is_file($Avion_img_win))
				$Avion_win="<img src='".$Avion_img_win."' title='".$Avion_win."'>";
			if(is_file($Avion_img_loss))
				$Avion_loss="<img src='".$Avion_img_loss."' title='".$Avion_loss."'>";
			if(is_file($Avion_unit_win_img))
				$Unite_win="<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
			if(is_file($Avion_unit_loss_img))
				$Unite_loss="<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
			if($Cycle)
				$Cycle_txt="Nuit";
			else
				$Cycle_txt="Jour";
		?>
		<tr>
				<td><? echo $Date;?></td>
				<td><? echo "<img src='images/meteo".$data2['Cycle'].".gif' title='".$Cycle_txt."'>";?></td>
				<td><? echo $Lieu;?></td>
				<td><img src='<? echo $Pays_win;?>20.gif'></td>
				<td><? echo $Unite_win;?></td>
				<td><? echo $Pilote_win;?></td>
				<td><? echo $Avion_win;?></td>
				<td><? echo $Avion_loss;?></td>
				<td><? echo $Pilote_loss;?></td>
				<td><? echo $Unite_loss;?></td>
				<td><img src='<? echo $Pays_loss;?>20.gif'></td>
		</tr>
		<?
	}
	$skills='<p align="center">Page : ';
	for($i=1;$i<=$nombreDePages;$i++)
	{
		 if($i==$pageActuelle)
			$skills+=' [ '.$i.' ] '; 
		 else
			  $skills+=' <a href="output.php?page='.$i.'">'.$i.'</a> ';
	}
	$skills+='</p>';
	echo '</table>';
}