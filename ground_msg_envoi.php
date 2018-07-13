<?
require_once('./jfv_inc_sessions.php');
$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
$PlayerID=$_SESSION['PlayerID'];
if($Officier >0 or $OfficierEMID >0 or $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
    include_once('./menu_messagerie_gr.php');
	$ID = Insec($_POST['mes']);
	$con=dbconnecti(3);
	$ok=mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Reception,Sujet,Message,Rec_em FROM Ada_Messages WHERE ID='$ID' LIMIT 1");
	mysqli_close($con);
	if($ok)
	{
		while($data=mysqli_fetch_array($ok)) 
		{
			$Message=nl2br($data['Message']);
			if($data['Reception'])
			{
				if($data['Rec_em'] ==1)
					$Reception_nom=GetData("Officier_em","ID",$data['Reception'],"Nom");
				elseif($data['Rec_em'] ==3)
					$Reception_nom=GetData("Pilote","ID",$data['Reception'],"Nom");
				elseif($data['Rec_em'] ==2)
					$Reception_nom=GetData("Officier","ID",$data['Reception'],"Nom");
				else
					$Reception_nom="[No-Reply]";
			}
			else
				$Reception_nom="[No-Reply]";
			$Msg_Off.='<hr>'.$Message;
            ?>
            <table class="table">
                <thead><tr><th>La Poste des Armées : Message</th></tr></thead>
                <tr><td align="left"><?=$data['Date']?></td></tr>
                <tr><td align="left"><? echo 'Adressé à <b>'.$Reception_nom.'</b>';?></td></tr>
                <tr><td align="left"><?=$data['Sujet']?></td></tr>
                <tr><td align="left"><?=$Msg_Off?></td></tr>
            </table>
            <?
		}
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>