<?
/*require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$OfficierID=$_SESSION['Officier'];
$Officier=Insec($_POST['Officier']);
if($OfficierID >0 AND $Officier >0)
{	
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Cible=Insec($_POST['Cible']);
	$Type=Insec($_POST['Type']);
	$Reset=Insec($_POST['reset']);
	$country=$_SESSION['country'];
	if($Reset ==3)
	{
		SetData("Officier","Mission_Lieu_D",0,"ID",$Officier);
		SetData("Officier","Mission_Type_D",0,"ID",$Officier);
		$mes="<p>Vous annulez la demande de mission en cours!</p>";
	}
	elseif($Reset ==4)
	{
		SetData("Officier","Aide",$Cible,"ID",$Officier);
		$mes="<p>Vous demandez des renforts d'urgence!</p>";
	}
	elseif($Reset ==6)
	{
		$Date_Courante=GetData("Conf_Update","ID",1,"Date");
		SetData("Officier","Rapport",$Date_Courante,"ID",$Officier);
		UpdateCarac($OfficierID,"Note",1,"Officier");
		$mes="<p>Vous envoyez votre rapport logistique à votre état-major.</p>";
	}
	elseif($Reset ==20)
	{
		if($Cible >0)
		{
			SetData("Officier","Train_Lieu",$Cible,"ID",$Officier);
			SetData("Regiment","Placement",3,"Officier_ID",$Officier);
			SetData("Regiment","Position",11,"Officier_ID",$Officier);
			UpdateCarac($OfficierID,"Credits",-1,"Officier");
			$mes="<p>Votre demande de transport a été envoyée.</p>";
		}
		else
			$mes="<p>Votre demande de transport a été refusée, vous n'avez pas sélectionné de destination.</p>";
	}
	elseif($Reset ==30)
	{
		$Destination=Getdata("Officier","ID",$Cible,"Train_Lieu");
		SetData("Officier","Train_Lieu",0,"ID",$Cible);
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment SET Lieu_ID='$Destination',Camouflage=1,Position=4,Visible=1,Fret=0,Fret_Qty=0,Experience=Experience+10,Moral=Moral+10 WHERE Officier_ID='$Officier'");
		$reset2=mysqli_query($con,"UPDATE Regiment SET Lieu_ID='$Destination',Camouflage=1,Placement=3,Position=4,Visible=0 WHERE Officier_ID='$Cible'");
		mysqli_close($con);		
		UpdateCarac($OfficierID,"Credits",-24,"Officier");
		$mes="<p>Vous arrivez à destination.</p>";
		$img=Afficher_Image('images/train_transport.jpg','images/train_transport.jpg','Arrivée');
	}
	elseif($Reset ==40)
	{
		if($Cible >0)
		{
			SetData("Officier","Barges_Lieu",$Cible,"ID",$Officier);
			SetData("Regiment","Placement",4,"Officier_ID",$Officier);
			SetData("Regiment","Position",11,"Officier_ID",$Officier);
			UpdateCarac($OfficierID,"Credits",-1,"Officier");
			$mes="<p>Votre demande de transport a été envoyée.</p>";
		}
		else
			$mes="<p>Votre demande de transport a été refusée, vous n'avez pas sélectionné de destination.</p>";
	}
	elseif($Reset ==50)
	{
		$con=dbconnecti();
		//$reset=mysqli_query($con,"UPDATE Regiment SET Camouflage=1,Placement=4,Position=4 WHERE Officier_ID='$Officier'");
		$reset1=mysqli_query($con,"UPDATE Regiment SET Camouflage=1,Placement=4,Position=4,Fret=1,Fret_Qty=0 WHERE Officier_ID='$Officier' AND Vehicule_ID=5000");
		$reset2=mysqli_query($con,"UPDATE Regiment SET Camouflage=1,Placement=9,Position=11,Visible=0 WHERE Officier_ID='$Cible'");
		$reset3=mysqli_query($con,"UPDATE Officier SET Credits=Credits-4,Transit='$Cible' WHERE ID='$Officier'");
		mysqli_close($con);
		$mes="<p>Vous embarquez les troupes dans vos barges.</p>";
		$img=Afficher_Image('images/embarquement.jpg','images/embarquement.jpg','Arrivée');
	}
	elseif($Reset ==60)
	{
		//$Zone=Insec($_POST['zone']);
		if($Cible >0)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Camouflage=1,Placement=1,Position=11 WHERE Officier_ID='$Officier'");
			$reset3=mysqli_query($con,"UPDATE Officier SET Credits=Credits-1,Para_Lieu='$Cible',Mission_Lieu_D='$Cible',Mission_Type_D=24 WHERE ID='$Officier'");
			mysqli_close($con);
			$mes="<p>Votre demande de parachutage a été envoyée.</p>";
		}
		else
			$mes="<p>Votre demande de parachutage a été refusée, vous n'avez pas sélectionné de destination.</p>";
	}
	elseif($Reset ==10)
	{
		$Ciea=Insec($_POST['Ciea']);
		$Cieb=Insec($_POST['Cieb']);
		$Ciec=Insec($_POST['Ciec']);
		$Cied=Insec($_POST['Cied']);
		$Chargea=Insec($_POST['Chargea']);
		$Chargeb=Insec($_POST['Chargeb']);
		$Chargec=Insec($_POST['Chargec']);
		$Charged=Insec($_POST['Charged']);
		$Muna=Insec($_POST['Muna']);
		$Munb=Insec($_POST['Munb']);
		$Munc=Insec($_POST['Munc']);
		$Mund=Insec($_POST['Mund']);
		$Qtya=Insec($_POST['Qtya']);
		$Qtyb=Insec($_POST['Qtyb']);
		$Qtyc=Insec($_POST['Qtyc']);
		$Qtyd=Insec($_POST['Qtyd']);
		$Ravit_Off=Insec($_POST['Ravit_Off']);
		if($Cible >0 and $Ravit_Off >0 and ($Ciea >0 or $Cieb >0 or $Ciec >0 or $Cied >0))
		{
			$mes_r=false;
			$Lieu=GetData("Lieu","ID",$Cible,"Nom");
			for($i=1;$i<5;$i++)
			{
				if($i ==1)
					$Prefix="a";
				elseif($i ==2)
					$Prefix="b";
				elseif($i ==3)
					$Prefix="c";
				elseif($i ==4)
					$Prefix="d";
				$Mun="Mun".$Prefix;
				$Charge="Charge".$Prefix;
				$Qty="Qty".$Prefix;
				$Cie="Cie".$Prefix;
				if($$Cie >0 and $$Charge >0 and $$Qty >0)
				{
					if($$Mun ==31)
						$Mun_txt="AP";
					elseif($$Mun ==32)
						$Mun_txt="HE";
					elseif($$Mun ==34)
						$Mun_txt="APHE";
					elseif($$Mun ==36)
						$Mun_txt="APCR";
					elseif($$Mun ==37)
						$Mun_txt="APDS";
					elseif($$Mun ==38)
						$Mun_txt="HEAT";
					if($$Charge ==87)
						$Charge_txt="Essence";
					elseif($$Charge ==1)
						$Charge_txt="Diesel";
					elseif($$Charge ==300)
						$Charge_txt="Charges";
					elseif($$Charge ==400)
						$Charge_txt="Mines";
					else
						$Charge_txt=$$Charge."mm ".$Mun_txt;
					$mes_r.="<br>Demande de ".$$Qty." ".$Charge_txt." pour ma ".$$Cie."e Cie. Rendez-vous à ".$Lieu;
				}
			}
			if($mes_r)
			{
				SendMsgOff($Ravit_Off,$Officier,$mes_r,"Demande de ravitaillement",2,2);				
				$mes.="<p>Votre demande de ravitaillement a été envoyée.</p>";
				if(!$_SESSION['Trans_ravito'])UpdateData("Officier","Note",1,"ID",$OfficierID);
				$_SESSION['Trans_ravito']=true;
			}
			else
				$mes.="<p>Votre demande de ravitaillement n'a pas pu été réalisée.</p>";
		}
		else
			$mes.="<p>Votre demande de ravitaillement n'a pas pu été réalisée.</p>";
	}
	else
	{
		switch($country)
		{
			case 1:
				$Corps="Armee Korp";
			break;
			case 2: case 7:
				$Corps="Corp";
			break;
			case 3: case 4:
				$Corps="Corps d'armée";
			break;
			default:
				$Corps="QG";
			break;
		}
		SetData("Officier","Mission_Lieu_D",$Cible,"ID",$Officier);
		SetData("Officier","Mission_Type_D",$Type,"ID",$Officier);
		if(!$_SESSION['Transmissions'])UpdateData("Officier","Note",1,"ID",$OfficierID);
		$_SESSION['Transmissions']=true;
		$mes="<p>Le ".$Corps." vous informe que votre demande de mission a été validée.</p>";
	}
	if(!$img)$img=Afficher_Image('images/radio_naval.jpg','images/image.png','radio');
	$titre="Transmissions";	
	$menu="<a href='index.php?view=ground_appui' class='btn btn-default' title='Retour'>Retour au menu Transmissions</a>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";*/
?>