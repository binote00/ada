<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	$Division=Insec($_POST['Division']);
	$Option=Insec($_POST['Mode']);
	if($Option ==1)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Front,Pays,Base,Cdt FROM Division WHERE ID='$Division'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Div_Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Front=$data['Front'];
				$Base=$data['Base'];
				$Div_Cdt=$data['Cdt'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($OfficierID ==$Div_Cdt)
		{
			$Vehicule=GetDoubleData("Cible","Pays",$Pays,"Categorie",4,"ID");
			$query2 ="INSERT INTO Regiment_IA (Pays,Front,Division,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,HP,Camouflage,Experience,Moral,Distance,Move)";
			$query2.="VALUES ('$Pays','$Front','$Division','$Vehicule','$Base',1,0,0,1,255,100,5000,1)";
			$con=dbconnecti();
			$ok2=mysqli_query($con,$query2);
			mysqli_close($con);
			if($ok2)
				$mes.="La Compagnie d'état-major de la division <b>".$Div_Nom."</b> a été activée avec succès !<br>".GetVehiculeIcon($Vehicule, $Pays, 0, 0, $Front);
			else
				$mes.="Erreur lors de l'activation de la Compagnie d'état-major de la division <b>".$Div_Nom."</b> !";
			$mes.="<br><a href='index.php?view=ground_div' class='btn btn-default' title='Retour'>Retour</a>";
			$img=Afficher_Image('images/rally.jpg', 'images/image.png', 'Véhicule de commandement');;
		}
	}
	else
	{
		$Off=Insec($_POST['Officier']);
		$repli=Insec($_POST['prepli']);
		$rally=Insec($_POST['prally']);
		$ravit=Insec($_POST['pravit']);
		$atk=Insec($_POST['patk']);
		$hatk=Insec($_POST['hatk']);
		$def=Insec($_POST['pdef']);	
		if($repli !=999999)
			SetData("Division","repli",$repli,"ID",$Division);
		if($rally !=999999)
			SetData("Division","rally",$rally,"ID",$Division);
		if($atk !=999999)
			SetData("Division","atk",$atk,"ID",$Division);
		if($def !=999999)
			SetData("Division","def",$def,"ID",$Division);
		if($ravit !=999999)
			SetData("Division","ravit",$ravit,"ID",$Division);
		if($hatk)
			SetData("Division","hatk",$hatk,"ID",$Division);
		if(GetData("Officier","ID",$Off,"Orders") ==0)
		{
			UpdateData("Officier","Avancement",10,"ID",$Off);
			UpdateData("Officier","Note",2,"ID",$Off);
			SetData("Officier","Orders",1,"ID",$Off);
		}
		else
			UpdateData("Officier","Avancement",1,"ID",$Off);		
		/*$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Division SET repli='$repli',rally='$rally',atk='$atk',def='$def' WHERE ID='$Division'");
		mysqli_close($con);*/	
		$mes ="Vos ordres ont été transmis.";
	}
	$titre ="Ordres à la Division";
	include_once('./default.php');
}
?>