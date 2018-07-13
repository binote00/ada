<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID xor $OfficierID xor $OfficierEMID)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$Tri=Insec($_POST['Tri']);
		if(!$Tri)$Tri=10;
		?>
		<h1>Officiers d'état-major</h1>
		<div style='overflow:auto; width: 100%;'>
		<table class='table table-striped'>
			<thead><tr>
				<th>N°</th>
				<th>Nom</th>
				<th>Pays</th>
				<th><form action='index.php?view=officiers_em' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Grade'></form></th>
				<th><form action='index.php?view=officiers_em' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Reputation'></form></th>
				<th><form action='index.php?view=officiers_em' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Activité'></form></th>
				<th colspan="20">Décorations</th>
			</tr></thead>
		<?
		switch($Tri)
		{
			case 8:
				$Tri='Credits_Date';
			break;
			case 9:
				$Tri='Reputation';
			break;
			case 10:
				$Tri='Avancement';
			break;
		}
		if($PlayerID ==1 or $OfficierEMID ==1)
			$query="SELECT ID,Nom,Pays,Avancement,Reputation,medal,kreta,afrika,Ost,camp_fr,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 200";
		else
			$query="SELECT ID,Nom,Pays,Avancement,Reputation,medal,kreta,afrika,Ost,camp_fr,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE Actif=0 AND Pays NOT IN(3,5,10,15,16,17,18,19,20,35,36) AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 100";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			$i=1;
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Grade=GetAvancement($data['Avancement'],$data['Pays'],0,1);
				$medal='';
				$Division='';
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
                    elseif($data['Pays'] ==6)
                        $medal.='<img title="Campagne Med" src="images/mmedal'.$data['Pays'].'17.gif">';
                    else
                        $medal.='<img title="Campagne Med" src="images/mmedal'.$data['Pays'].'15.gif">';
				}
				if($data['kreta'])
				{
					if($data['Pays'] ==1)
						$medal.="<img title='Armelband Kreta' src='images/mkreta.png'>";
					elseif($data['Pays'] ==8)
						$medal.="<img title='Orden Kutuzova' src='images/mkutuzov".$data['kreta'].".png'>";
				}
                if($data['camp_fr'])
                {
                    if($data['Pays'] ==1)
                        $medal.='<img title="Campagne Ouest" src="images/mmedal116.gif">';
                    else
                        $medal.='<img title="Campagne Ouest" src="images/mmedal'.$data['Pays'].'14.gif">';
                }
				if($PlayerID ==1 or $OfficierID ==1 or $OfficierEMID ==1)
				{
					$Pilote=GetData("Joueur","Officier_em",$data['ID'],"Pilote_id");
					$Officier=GetData("Joueur","Officier_em",$data['ID'],"Officier");
					if($Pilote)
						$Pilote=GetData("Pilote","ID",$Pilote,"Nom");
					if($Officier)
						$Officier=GetData("Officier","ID",$Officier,"Nom");
					echo "<tr><td>".$i."</td><td align='left'>".$data['Nom']." (".$data['ID']." - ".$Pilote." - ".$Officier.")</a></td>
						<td><img src='".$data['Pays']."20.gif'></td>
						<td><img title='".$Grade[0]."(".$data['Avancement'].")' src='images/grades/ranks".$data['Pays'].$Grade[1].".png'></td>
						<td>".$data['Reputation']."</td><td>".$data['Activite']."</td><td>".$medal."</td></tr>";
				}
				else
				{
					$Rep=GetReputOfficier($data['Reputation']);
					echo "<tr><td>".$i."</td><td align='left'>".$data['Nom']."</a></td>
						<td><img src='".$data['Pays']."20.gif'></td>
						<td><img title='".$Grade[0]."' src='images/grades/ranks".$data['Pays'].$Grade[1].".png'></td>
						<td><img title='".$Rep[0]."' src='images/general".$Rep[1].".png'></td><td>".$data['Activite']."</td><td>".$medal."</td></tr>";
				}
				$i++;
			}
		}
		echo '</table></div>';
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/acces_premium.png'></td></tr>
			<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
		</table>";
	}
}