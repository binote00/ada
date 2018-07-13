<head>
	<title>Aube des Aigles : Détail de Bataille Historique</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
<body background="images/bg_papier1.gif">
<div>
<table bgcolor="#999999" border="0" rules=rows cellspacing="1" cellpadding="5">
	<th colspan="9" bgcolor="LightSteelBlue">Détail des attaques</th>
	<tr bgcolor="CadetBlue">
		<th>Date</th>
		<th>Unité</th>
		<th>Avion</th>
		<th>Cible détruite</th>
		<th>Lieu</th>
		<?
		include_once('./jfv_include.inc.php');
		include_once('./jfv_txt.inc.php');
				
		$ID=Insec($_GET['id']);
		//$Date=Insec($_GET['date']);
		$Date=date('Y-m-d');
		
			$messagesParPage = 25;
			$con = dbconnecti();
			$query=mysqli_query($con, "SELECT COUNT(*) AS total FROM Attaque WHERE Lieu = '$ID' AND DATEDIFF(Date,'$Date') = 0");
			/*$query=mysqli_query($con, "SELECT COUNT(*) AS total FROM ((SELECT Type,Nom,Avion,Joueur,Unite,Lieu,Arme FROM Attaque WHERE Lieu='$ID') 
			UNION (SELECT Type,Nom,Avion,Joueur,Unite,Lieu,Arme FROM Bombardement WHERE Lieu='$ID')) AS T WHERE Lieu='$ID'");*/
			$data=mysqli_fetch_assoc($query);
			$total=$data['total'];
			$nombreDePages=ceil($total/$messagesParPage);
			
			if(isset($_GET['page']))
			{
				 $pageActuelle=intval($_GET['page']);
				 
				 if($pageActuelle>$nombreDePages) 
				 {
					  $pageActuelle=$nombreDePages;
				 }
			}
			else 
			{
				 $pageActuelle=1;  
			}
			$premiereEntree=($pageActuelle-1)*$messagesParPage;
			$query2=mysqli_query($con, "SELECT DISTINCT * FROM Attaque WHERE Lieu = '$ID' AND Type > 0 AND DATEDIFF(Date,'$Date') = 0 ORDER BY ID ASC LIMIT ".$premiereEntree.", ".$messagesParPage."");
			/*$query=mysqli_query($con, "SELECT * FROM ((SELECT Type,Nom,Avion,Joueur,Unite,Lieu,Arme FROM Attaque WHERE Lieu='$ID') 
		UNION (SELECT Type,Nom,Avion,Joueur,Unite,Lieu,Arme FROM Bombardement WHERE Lieu='$ID')) as t 
		WHERE Lieu='$ID' ORDER BY Date DESC, Joueur ASC LIMIT ".$premiereEntree.", ".$messagesParPage."");*/
			if($query2)
			{
				while($data2=mysqli_fetch_assoc($query2))
				{
					$Date = str_replace("2012","1940",substr($data2['Date'],0,16));
					$Cible_detruite = ucfirst($data2['Nom']);
					$Avion_win = GetData("Avion","ID",$data2['Avion'],"Nom");
					$Unite_win = GetData("Unit","ID",$data2['Unite'],"Nom");
					$Lieu = GetData("Lieu","ID",$data2['Lieu'],"Nom");
					$Lieu_Pays = GetData("Lieu","ID",$data2['Lieu'],"Pays");
					
					$Cible_id = GetVehicule($Cible_detruite);
					
					$Cible_img = "images/vehicule".$Cible_id.".gif";
					$Avion_img = "images/avion".$data2['Avion'].".gif";
					$Avion_unit_img = "images/unit".$data2['Unite']."p.gif";
					if(is_file($Cible_img))
					{
						$Cible_detruite = "<img src='".$Cible_img."' title='".$Cible_detruite."'>";
					}
					if(is_file($Avion_img))
					{
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					}
					if(is_file($Avion_unit_img))
					{
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					}

					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? echo $Cible_detruite;?></td>
						<td><? echo $Lieu;?></td>
					</tr>
					<?
				}
				echo '<p align="center">Page : ';
				for($i=1; $i<=$nombreDePages; $i++)
				{
					 if($i==$pageActuelle)
					 {
						 echo ' [ '.$i.' ] '; 
					 }	
					 else
					 {
						  echo ' <a href="detail_campagne.php?id='.$ID.'&page='.$i.'">'.$i.'</a> ';
					 }
				}
				echo '</p>';
			}
			else
			{
				echo "<b>Désolé, aucune victoire enregistrée à ce jour.</b>";
			}
?>
</table>
<hr>
</div>
</body>
