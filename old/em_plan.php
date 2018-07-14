<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($GHQ or $Admin)
	{
		$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.Nom as Base FROM Unit as u,Lieu as l 
		WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 ORDER BY u.Type DESC,u.Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Type=$data['Type'];
				if($Type >0)
				{
					$Front_unit=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
					$Front_nbr[$Front_unit][$Type] +=1;
				}
			}
			mysqli_free_result($result);
		}
		echo "<h1>Planificateur Stratégique</h1><h2>Répartition par fronts</h2><h3>Unités aériennes</h3><table class='table'><thead><tr><th>Front</th>";
		for($f=1;$f<13;$f++){echo "<th>".GetAvionType($f)."</th>";}
		echo "</tr></thead>";
		for($x=0;$x<6;$x++)
		{
			if($Front_nbr[$x])
			{
				echo "<tr><th>".GetFront($x)."</th>";
				for($i=1;$i<13;$i++)
				{
					/*if(!$Front_nbr[$x][$i])
						$Front_nbr[$x][$i] =0;*/
					echo "<td><span class='badge'>".$Front_nbr[$x][$i]."</span></td>";
				}
				echo "</tr>";
			}
		}
		echo "</table>";
	}
}
?>