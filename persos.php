<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0) and $Premium >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	function GetFronts($Front,$Pays,$country,$Admin=0)
	{
		if($Pays ==$country or $Admin ==1)
		{
			if($Front ==3)
				$Front="Pacifique";
			elseif($Front ==2)
				$Front="Med";
			elseif($Front ==4)
				$Front="Nord";
			elseif($Front ==1)
				$Front="Est";
			elseif($Front ==5)
				$Front="Arctique";
			elseif($Front ==12)
				$Front="Réserve";
			elseif($Front ==98)
				$Front="";
			elseif($Front ==99)
				$Front="Planification Stratégique";
			else
				$Front="Ouest";
		}
		else
			$Front="Inconnu";
		return $Front;
	}
	function GetCrDate($date,$Pays,$country,$Date_Actuelle,$Admin=0)
	{
		if($Pays !=$country and !$Admin)
			$date="Inconnu";
		else
		{
			$con=dbconnecti();
			$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date_Actuelle','$date')"),0);
			mysqli_close($con);
			if($Datediff >30)
				$date="<span class='text-danger'>".$date."</span>";
			elseif($Datediff >15)
				$date="<font color='#cc6600'>".$date."</font>";
			elseif($Datediff >7)
				$date="<font color='#ff9900'>".$date."</font>";
			elseif($Datediff <2)
				$date="<font color='#32CD32'>".$date."</font>";
		}
		return $date;
	}
	echo "<h1>Personnages</h1>
	<div>
	<table class='table table-striped'>
		<thead><tr>
			<th>Nation</th>
			<th>Officier</th>
			<th>Front O</th>
			<th>Activité O</th>
			<th>Pilote</th>
			<th>Front P</th>
			<th>Activité P</th>
			<th>Officier 2</th>
			<th>Front 2</th>
			<th>Activité 2</th>
		</tr></thead>";
	if($Admin ==1 or $Premium)
	{
		$Date_Actuelle=date("Y-m-d");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT j.* FROM Joueur as j WHERE j.Actif=0 AND j.Admin=0 AND DATE(j.Con_date) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND (Pilote_id >0 OR Officier_em >0 OR Officier_bonus >0) ORDER BY j.Pays ASC,j.Front ASC");
		/*$result=mysqli_query($con,"SELECT j.*,p.Nom,p.Pays,p.Front,p.Credits_date,o.Nom as Off_em,o.Front as Front_o,o.Credits_date as date_o,o.Pays as Pays_o
		FROM Joueur as j,Pilote as p,Officier_em as o
		WHERE j.Officier_em=o.ID AND j.Pilote_id=p.ID AND j.Actif=0 AND j.ID>1 AND (p.Credits_date >'2017-01-01' OR o.Credits_date >'2017-01-01')
		ORDER BY j.Pays ASC,j.Front ASC");*/
		/*$result=mysqli_query($con,"SELECT p.Nom,p.Pays,p.Front,p.Credits_date,o.Nom as Off,o.Front as Front_o,o.Credits_date as date_o,o.Pays as Pays_o,e.Nom as Off_em,e.Front as Front_e,e.Credits_date as date_e,e.Pays as Pays_e
		FROM Joueur as j,Pilote as p,Officier as o,Officier_em as e
		WHERE j.Officier=o.ID AND j.Officier_em=e.ID AND j.Pilote_id=p.ID AND j.Actif=0 AND j.ID>1 ORDER BY j.Pays ASC,j.Front ASC");*/
		$persos_af=mysqli_affected_rows($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				if($data['Pilote_id'] >0)
				{
					$resultp=mysqli_query($con,"SELECT Nom,Front,Credits_date,Pays FROM Pilote WHERE ID='".$data['Pilote_id']."'");
					if($resultp)
					{
						while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
						{
							$data['Nom']=$datap['Nom'];
							$data['Front']=$datap['Front'];
							$data['Credits_date']=$datap['Credits_date'];
						}
						mysqli_free_result($resultp);
					}
				}
				else
				{
					$data['Nom']=false;
					$data['Front']=98;
					$data['Credits_date']=false;				
				}
				/*if($data['Officier'] >0)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Front,Credits_date FROM Officier WHERE ID='".$data['Officier']."'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$data['Off']=$datao['Nom'];
							$data['Front_o']=$datao['Front'];
							$data['date_o']=$datao['Credits_date'];
						}
						mysqli_free_result($resulto);
					}
				}
				else
				{
					$data['Off']=false;
					$data['Front_o']=98;
					$data['date_o']=false;				
				}*/
				if($data['Officier_em'] >0)
				{
					$resultem=mysqli_query($con,"SELECT Nom,Front,Credits_date FROM Officier_em WHERE ID='".$data['Officier_em']."'");
					if($resultem)
					{
						while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC))
						{
							$data['Off_em']=$dataem['Nom'];
							$data['Front_e']=$dataem['Front'];
							$data['date_e']=$dataem['Credits_date'];
						}
						mysqli_free_result($resultem);
					}
				}
				else
				{
					$data['Off_em']=false;
					$data['Front_e']=98;
					$data['date_e']=false;
				}
                if($data['Officier_bonus'] >0)
                {
                    $resultem=mysqli_query($con,"SELECT Pays,Nom,Front,Credits_date FROM Officier_em WHERE ID='".$data['Officier_bonus']."'");
                    if($resultem)
                    {
                        while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC))
                        {
                            $data['Off_bonus']=$dataem['Nom'];
                            $data['Pays_b']=$dataem['Pays'];
                            $data['Front_b']=$dataem['Front'];
                            $data['date_b']=$dataem['Credits_date'];
                        }
                        mysqli_free_result($resultem);
                    }
                    $pays_b_icon="<img src='".$data['Pays_b']."20.gif'> ";

                }
                else
                {
                    $data['Off_bonus']=false;
                    $data['Pays_b']=false;
                    $data['Front_b']=98;
                    $data['date_b']=false;
                    $pays_b_icon=false;
                }
				if($Admin){
                    $admin_infos = '('.$data['ID'].')';
                }
				echo "<tr><td><img src='".$data['Pays']."20.gif'>".$admin_infos."</td>
				<td align='left'>".$data['Off_em']."</td><td>".GetFronts($data['Front_e'],$data['Pays'],$country,$Admin)."</td><td>".GetCrDate($data['date_e'],$data['Pays'],$country,$Date_Actuelle,$Admin)."</td>
				<td align='left'>".$data['Nom']."</td><td>".GetFronts($data['Front'],$data['Pays'],$country,$Admin)."</td><td>".GetCrDate($data['Credits_date'],$data['Pays'],$country,$Date_Actuelle,$Admin)."</td>
				<td align='left'>".$pays_b_icon.$data['Off_bonus']."</td><td>".GetFronts($data['Front_b'],$data['Pays_b'],$country,$Admin)."</td><td>".GetCrDate($data['date_b'],$data['Pays_b'],$country,$Date_Actuelle,$Admin)."</td>
				</tr>";
				//<td align='left'>".$data['Off']."</td><td>".GetFronts($data['Front_o'],$data['Pays'],$country,$Admin)."</td><td>".GetCrDate($data['date_o'],$data['Pays'],$country,$Date_Actuelle,$Admin)."</td>
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		echo '</table>';
		if($Admin)
			echo $persos_af;
	}
}
else
	echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";