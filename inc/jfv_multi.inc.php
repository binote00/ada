<?php

function GetOnlinePlayers()
{
    $PlayerID=$_SESSION['PlayerID'];	
	if($PlayerID > 0)
	{
		$time=date('Y-m-d G:i');
		$ip=$_SERVER['REMOTE_ADDR'];		
		$con=dbconnecti(2);
		$result=mysqli_query($con,"SELECT ID FROM Connectes WHERE PlayerID='$PlayerID'");
		// si l'utilisateur n'est pas deja dans la table
		if(mysqli_num_rows($result) == 0)
		{
			$sql_query2="INSERT INTO Connectes (PlayerID,IP,Time) VALUES ('$PlayerID', '$ip', '$time')";
			$result2=mysqli_query($con, $sql_query2);
			if(!$result2)
			{
				$mes.="Erreur d'Insert de Connectés : GetOnlinePlayers ".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: Error',$mes);
			}
		}
		else
		{
			$sql_update="UPDATE Connectes SET Time='$time',IP='$ip' WHERE PlayerID='$PlayerID'";        
			$result=mysqli_query($con, $sql_update);
			if(!$result)
			{
				$mes.="Erreur d'Update de Connectés : GetOnlinePlayers ".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: Error',$mes);
			}
		}
		mysqli_close($con);
	}
}

function PrintOnlinePlayers($PlayerID)
{
	if($PlayerID >0)
	{		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(ID) as Nbr,Pays FROM Joueur WHERE Actif=0 GROUP BY Pays");
		if($result)
		{
			while($data=mysqli_fetch_array($result))
			{
				echo "<img src='".$data['Pays']."20.gif'>".$data['Nbr']."<br>";
			}
			mysqli_free_result($result);
		}
		/*$result101=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=1 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);	
		$result102=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=2 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result104=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=4 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result106=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=6 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result107=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=7 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result108=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=8 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result109=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=9 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		//$result1020=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=20 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);
		$result11=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=1 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);	
		$result12=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=2 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result14=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=4 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result16=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=6 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result17=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=7 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		$result18=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=8 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);
		$result19=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Officier WHERE Pays=9 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);			
		mysqli_close($con);		
		echo '<table cellspacing=\'2\' cellpadding=\'2\'>';	
		echo '<tr><td>'.$result101.'<img src=\'120.gif\' alt=\'Allemagne\'>'.$result11.'</td><td>'.$result102.'<img src=\'220.gif\' alt=\'Angleterre\'>'.$result12.'</td><tr>';
		echo '<tr><td>'.$result106.'<img src=\'620.gif\' alt=\'Italie\'>'.$result16.'</td><td>'.$result108.'<img src=\'820.gif\' alt=\'URSS\'>'.$result18.'</td></tr>';
		echo '<tr><td>'.$result109.'<img src=\'920.gif\' alt=\'Japon\'>'.$result19.'</td><td>'.$result107.'<img src=\'720.gif\' alt=\'USA\'>'.$result17.'</td></tr>';
		echo '<tr><td colspan=\'2\'>'.$result104.'<img src=\'420.gif\' alt=\'France\'>'.$result14.'</td></tr>';
		echo '</table>';*/
	}
	else
	{
		/*$con=dbconnecti();							
		$result1=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=1 AND Actif=0"),0);	
		$result2=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=2 AND Actif=0"),0);			
		$result4=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=4 AND Actif=0"),0);			
		$result6=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=6 AND Actif=0"),0);			
		$result7=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=7 AND Actif=0"),0);			
		$result8=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=8 AND Actif=0"),0);			
		$result9=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=9 AND Actif=0"),0);			
		$result20=mysqli_result(mysqli_query($con,"SELECT count(*) FROM Pilote WHERE Pays=20 AND Actif=0"),0);
		mysqli_close($con);*/
	}
}