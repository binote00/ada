<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
	if($_SESSION['Distance'] ==0)
	{
		$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
		if($Premium)
		{
			$Action=Insec($_POST['Mode']);
			if($Admin)
			    $query_date='';
			elseif($Action ==3)
				$query_date=" AND DATE(Date) BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()";
			elseif($Action ==2)
				$query_date=" AND DATE(Date)=CURDATE() - INTERVAL 2 DAY";
			elseif($Action ==1)
				$query_date=" AND DATE(Date)=CURDATE() - INTERVAL 1 DAY";
			else
				$query_date=" AND DATE(Date)=CURDATE()";
			$con=dbconnecti(4);
			$Avions_perdus=mysqli_query($con,"SELECT Avion,SUM(Avion_Nbr) as Avion_Nbr FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231)".$query_date." AND Avion_Nbr >0 GROUP BY Avion");
            $Cbt_Air=mysqli_query($con,"SELECT COUNT(*),Avion_Nbr FROM Events_em WHERE Event_Type IN (280,281,282,283)".$query_date." GROUP BY Avion_Nbr");
			$Veh_Perdus=mysqli_query($con,"SELECT Avion,Avion_Nbr FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615)".$query_date);
			$Veh_Perdus2=mysqli_query($con,"SELECT Pilote_eni,Avion_Nbr FROM Events_Ground_Stats WHERE Event_Type IN (402,403)".$query_date);
			$Veh_Perdus3=mysqli_query($con,"SELECT Avion,Avion_Nbr FROM Events_Ground WHERE Event_Type IN (505,515)".$query_date);
			$Veh_Perdus4=mysqli_query($con,"SELECT Pilote_eni,Avion_Nbr FROM Events_Ground WHERE Event_Type IN (502,602,702,707)".$query_date);
            /*$DCA_hit=mysqli_query($con,"SELECT Avion FROM Events_Feed WHERE Event_Type IN (78,178)".$query_date."");
            $DCA_hit2=mysqli_query($con,"SELECT Avion FROM Events_Ground WHERE Event_Type IN (381)".$query_date."");*/
            mysqli_close($con);
			if($Avions_perdus)
			{
				while($data=mysqli_fetch_array($Avions_perdus,MYSQLI_ASSOC))
				{
					//$Avions_pertes.="<tr><td>".$data['Avion_Nbr']."</td><td>".GetAvionIcon($data['Avion'])."<td></tr>";
					$Avs[$data['Avion']]+=$data['Avion_Nbr'];
					$Av_Land[$data['Pays']]+=$data['Avion_Nbr'];
				}
				mysqli_free_result($Avions_perdus);
			}
            if($Cbt_Air)
            {
                while($data=mysqli_fetch_array($Cbt_Air))
                {
                    //$Avions_pertes.="<tr><td>".$data[0]."</td><td>".GetAvionIcon($data['Avion_Nbr'])."<td></tr>";
                    $Avs[$data['Avion_Nbr']]+=$data[0];
                    $Av_Land[$data['Pays']]+=$data[0];
                }
                mysqli_free_result($Cbt_Air);
            }
			if($Veh_Perdus)
			{
				while($data=mysqli_fetch_array($Veh_Perdus,MYSQLI_ASSOC))
				{
					$Vehs[$data['Avion']]+=$data['Avion_Nbr'];
				}
				mysqli_free_result($Veh_Perdus);
			}
			if($Veh_Perdus2)
			{
				while($data=mysqli_fetch_array($Veh_Perdus2,MYSQLI_ASSOC))
				{
					$Vehs[$data['Pilote_eni']]+=$data['Avion_Nbr'];
				}
				mysqli_free_result($Veh_Perdus2);
			}
			if($Veh_Perdus3)
			{
				while($data=mysqli_fetch_array($Veh_Perdus3,MYSQLI_ASSOC))
				{
					$Vehs[$data['Avion']]+=$data['Avion_Nbr'];
				}
				mysqli_free_result($Veh_Perdus3);
			}
			if($Veh_Perdus4)
			{
				while($data=mysqli_fetch_array($Veh_Perdus4,MYSQLI_ASSOC))
				{
					$Vehs[$data['Pilote_eni']]+=$data['Avion_Nbr'];
				}
				mysqli_free_result($Veh_Perdus4);
			}
			if(is_array($Vehs))
			{
				arsort($Vehs);
				foreach($Vehs as $Veh => $Avion_Nbr)
				{
					$Veh_pertes.="<tr><td>".$Avion_Nbr."</td><td>".GetVehiculeIcon($Veh)."<td></tr>";
				}
				unset($Vehs);
			}
            if(is_array($Avs))
            {
                arsort($Avs);
                foreach($Avs as $Av => $Avion_Nbr)
                {
                    $Avions_pertes.="<tr><td>".$Avion_Nbr."</td><td>".GetAvionIcon($Av)."<td></tr>";
                }
                unset($Avs);
            }
            if(is_array($Av_Land))
            {
                arsort($Av_Land);
                foreach($Av_Land as $Land => $Avion_Nbr)
                {
                    $Avions_pertes_pays.="<tr><td>".$Avion_Nbr."</td><td><img src='images/0".$Land.".gif'><td></tr>";
                }
                unset($Av_Land);
            }
			echo "<h1>Pertes du jour</h1><div class='row'><div class='col-md-6'><table class='table'><thead><tr><th>Perdus</th><th>Avion</th></tr></thead>".$Avions_pertes."</table></div>
			<div class='col-md-6'><table class='table'><thead><tr><th>Perdus</th><th>Véhicule</th></tr></thead>".$Veh_pertes."</table></div></div>";
		}
		else
			echo "<h1>Pertes du jour</h1><h2>Information Premium</h2><img src='images/premium.png' title='Information Premium'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";