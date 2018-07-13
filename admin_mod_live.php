<?
require_once('./jfv_inc_sessions.php');
if($_SESSION['PlayerID'] ==1)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Pays = Insec($_POST['country']);
	$Unit = Insec($_POST['unite']);
	$Lieu = Insec($_POST['lieu']);
	if($Lieu >0 and $Unit >0 and $Pays >0)
	{
		//Camouflage 0 et Piste >=50 si unité étrangère occupe le terrain
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Zone,Occupant,QualitePiste,Flag FROM Lieu WHERE ID='$Lieu'");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Zone = $data['Zone'];
				$Occupant = $data['Occupant'];
				$QualitePiste = $data['QualitePiste'];
				$Flag = $data['Flag'];
			}
			mysqli_free_result($result);
		}
		if((IsAllie($Flag) and  IsAxe($Pays)) OR (IsAxe($Flag) and IsAllie($Pays)))
		{
			if($QualitePiste <50)
			{
				SetData("Lieu","QualitePiste",50,"ID",$Lieu);
			}
			SetData("Lieu","Camouflage",0,"ID",$Lieu);
		}
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Flak SET Lieu='$Lieu' WHERE Unit='$Unit'");
		$reset_tr1=mysqli_query($con,"UPDATE Flak,Armes SET Flak.Unit=0 WHERE Flak.DCA_ID=Armes.ID AND Flak.Unit='$Unit' AND Armes.Transport=0");
		$reset_tr2=mysqli_query($con,"DELETE FROM Flak WHERE Unit=0");
		//$reset_tr2=mysqli_query($con,"DELETE FROM Flak USING Armes WHERE Flak.Unit='$Unit' AND Flak.DCA_ID=Armes.ID AND Armes.Transport=0");
		mysqli_close($con);		
		$con=dbconnecti();
		$reset2=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0,Flag='$Pays' WHERE ID='$Lieu'");
		$reset1=mysqli_query($con,"UPDATE Unit SET Base='$Lieu' WHERE ID='$Unit'");
		mysqli_close($con);
		mail('binote@hotmail.com','Aube des Aigles: Admin Mouvement Unité : '.$Unit.' fait Mouvement vers la base de '.$Lieu);
	}
	else
	{
		$Faction=GetData("Pays","ID",$Pays,"Faction");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT l.ID,l.Nom FROM Lieu as l,Pays as p WHERE l.Flag=p.ID AND l.Zone<>6 AND l.BaseAerienne >0 AND p.Faction='$Faction' ORDER BY l.Nom ASC");
		$result2=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Unit WHERE Pays='$Pays' AND Etat=1 ORDER BY Nom ASC");
		mysqli_close($con);
		if($result2)
		{
			while($data = mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
			{
				 $Units.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
			}
			mysqli_free_result($result2);
		}
		if($result)
		{
			while($datal = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				 $Lieux.="<option value='".$datal['ID']."'>".$datal['Nom']."</option>";
			}
			mysqli_free_result($result);
		}
		echo "<form action='admin_mod_live_move.php' method='post'>
			<input type='hidden' name='country' value='".$Pays."'>
			Unité <select name='unite' class='form-control' style='width: 200px'>".$Units."</select>
			Destination <select name='lieu' class='form-control' style='width: 200px'>".$Lieux."</select>
			<input type='Submit' value='VALIDER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}?>