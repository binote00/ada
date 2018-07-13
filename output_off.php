<?php
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if($PlayerID or $OfficierID or $OfficierEMID)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	//include_once('./menu_classement.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$Tri=Insec($_POST['Tri']);
		if(!$Tri)$Tri=9;
		?>
		<h1>Officiers</h1>
		<div style='overflow:auto; width: 100%;'>
		<table class='table table-striped'>
			<thead><tr>
				<th>N°</th>
				<th>Nom</th>
				<th>Pays</th>
				<th>Unité</th>
				<th><form action='index.php?view=officiers' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Grade'></form></th>
				<th><form action='index.php?view=officiers' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Reputation'></form></th>
				<th colspan="20">Décorations</th>
			</tr></thead>
		<?
		switch($Tri)
		{
			case 9:
				$Tri="Reputation";
			break;
			case 10:
				$Tri="Avancement";
			break;
		}
		if($PlayerID ==1 or $OfficierID ==1 or $OfficierEMID ==1)
			$query="SELECT ID,Nom,Pays,Avancement,Reputation,Division,medal,kreta,afrika,Ost FROM Officier WHERE Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 200";
		else
			$query="SELECT ID,Nom,Pays,Avancement,Reputation,Division,medal,kreta,afrika,Ost FROM Officier WHERE Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 100";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			$i=1;
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Grade=GetAvancement($data['Avancement'],$data['Pays'],0,1);
				$medal="";
				$Division="";
				if($data['medal'])
				{
					$u=1;
					for($u=1;$u<=$data['medal'];$u++)
					{
						$medal_txt=GetMedal_Name($data['Pays'],$u,1);
						if(($data['Pays'] ==1 and $u ==3) or ($data['Pays'] ==2 and $u >2 and $u <6))
							$medal.="<img title='".$medal_txt."' src='images/mmedal".$data['Pays'].$u."t.gif'>";
						else
							$medal.="<img title='".$medal_txt."' src='images/mmedal".$data['Pays'].$u.".gif'>";
					}
				}
				if($data['Ost'])
				{
					if($data['Pays'] ==1)
						$medal.="<img title='Ost Front' src='images/mmedal118.gif'>";
					elseif($data['Pays'] ==8)
						$medal.="<img title='Orden Khmelnitsky' src='images/mkhmelnitsky".$data['Ost'].".png'>";
				}
				if($data['afrika'])
				{
					if($data['Pays'] ==1)
						$medal.="<img title='Armelband Afrika' src='images/mafrika.png'>";
					elseif($data['Pays'] ==8)
						$medal.="<img title='Orden Alexander Nevsky' src='images/mnevsky.png'>";
				}
				if($data['kreta'])
				{
					if($data['Pays'] ==1)
						$medal.="<img title='Armelband Kreta' src='images/mkreta.png'>";
					elseif($data['Pays'] ==8)
						$medal.="<img title='Orden Kutuzova' src='images/mkutuzov".$data['kreta'].".png'>";
				}
				if($data['Division'])
					$Division="<img src='images/div/div".$data['Division'].".png'>";
				if($PlayerID ==1 or $OfficierID ==1 or $OfficierEMID ==1)
				{
					$Pilote=GetData("Joueur","Officier",$data['ID'],"Pilote_id");
					$Officier_em=GetData("Joueur","Officier",$data['ID'],"Officier_em");
					if($Pilote)
						$Pilote=GetData("Pilote","ID",$Pilote,"Nom");
					if($Officier_em)
						$Officier_em=GetData("Officier_em","ID",$Officier_em,"Nom");
					echo "<tr><td>".$i."</td><td align='left'>".$data['Nom']." (".$data['ID']." - ".$Pilote." - ".$Officier_em.")</a></td>
						<td><img src='".$data['Pays']."20.gif'></td><td>".$Division."</td>
						<td><img title='".$Grade[0]."(".$data['Avancement'].")' src='images/grades/ranks".$data['Pays'].$Grade[1].".png'></td>
						<td>".$data['Reputation']."</td><td>".$medal."</td></tr>";
				}
				else
				{
					$Rep=GetReputOfficier($data['Reputation']);
					echo "<tr><td>".$i."</td><td align='left'>".$data['Nom']."</a></td>
						<td><img src='".$data['Pays']."20.gif'></td><td>".$Division."</td>
						<td><img title='".$Grade[0]."' src='images/grades/ranks".$data['Pays'].$Grade[1].".png'></td>
						<td><img title=\"".$Rep[0]."\" src='images/general".$Rep[1].".png'></td><td>".$medal."</td></tr>";
				}
				$i++;
			}
		}
		echo "</table></div>";
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/acces_premium.png'></td></tr>
			<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
		</table>";
	}
}
?>