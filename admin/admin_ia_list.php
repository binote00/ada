<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$Admin=GetData("Pilote","ID",$PlayerID,"Admin");
	if($Admin >0)
	{
		for($Land=1;$Land<=9;$Land++)
		{
			if($Land == 1 or $Land == 2 or $Land == 4 or $Land == 6 or $Land == 7 or $Land == 8 or $Land == 9)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT * FROM Regiment_IA WHERE Pays='$Land' ORDER BY FRONT ASC,Vehicule_ID ASC");
				mysqli_close($con);
				if($result)
				{
					while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						echo GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$data['Front'],$data['ID']."e Cie")."<br>";
					}
					mysqli_free_result($result);
				}
			}
		}
	}
	else
		echo "Vous n'avez pas le droit d'accéder à cette page!";
}
?>