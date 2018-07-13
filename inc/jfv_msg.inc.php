<?php

Function IsValidEmail($email) 
{ 
	$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
	if(preg_match($Syntaxe,$email))
		return true; 
	else
		return false;
}

function SendMsg($Recepteur,$Expediteur,$Msg,$Sujet,$Exp_em=0,$Rec_em=0)
{
	$date=date('Y-m-d G:i');
	$Lu=0;
	$query="INSERT INTO Messages (Expediteur, Reception, Date, Message, Sujet, Lu, Exp_em, Rec_em)
	VALUES ('$Expediteur','$Recepteur','$date','$Msg','$Sujet','$Lu','$Exp_em','$Rec_em')";
	$con=dbconnecti(3);
	$ok=mysqli_query($con,$query);
	mysqli_close($con);
	if(!$ok)
	{
		$msg.='Erreur envoi de message : Exp='.$Expediteur.' ; Recepteur='.$Recepteur.' ; Sujet='.$Sujet;
		mail('binote@hotmail.com','Aube des Aigles: SendMsg Error',$msg);
	}
	/*else
	{
		$courier=GetData("Joueur","Pilote_id",$Recepteur,"Courier");
		if($courier)
		{
			$adresse=GetData("Joueur","Pilote_id",$Recepteur,"adresse");
			$auteur=GetData("Pilote","ID",$Expediteur,"Nom");
			mail ($adresse, "Aube des Aigles: Alerte de courrier" , "Vous avez reçu un message privé dans le jeu l Aube des Aigles\n\r ".$auteur." vous envoie :\n".$Sujet."\n\r".$Msg);
		}
		$msg.='Message envoyé!';
	}*/
}

function SendMsgOff($Recepteur,$Expediteur,$Msg,$Sujet,$Exp_em=0,$Rec_em=0)
{
	$date=date('Y-m-d G:i');
	$Lu=0;
	$query="INSERT INTO Ada_Messages (Expediteur, Reception, Date, Message, Sujet, Lu, Exp_em, Rec_em)
	VALUES ('$Expediteur','$Recepteur','$date','$Msg','$Sujet','$Lu','$Exp_em','$Rec_em')";
	$con=dbconnecti(3);
	$ok=mysqli_query($con,$query);
	if(!$ok)
	{
		$msg.='Erreur envoi de message : Exp='.$Expediteur.' ; Recepteur='.$Recepteur.' ; Sujet='.$Sujet.' ; Msg='.$Msg.' ; Error Msg='.mysqli_error($con);
		mail('binote@hotmail.com','Aube des Aigles: SendMsgOff Error',$msg);
	}
	mysqli_close($con);
	/*else
	{
		if($Rec_em > 0)
			$Off="Officier_em";
		else
			$Off="Officier";
		if($Exp_em > 0)
			$Off_exp="Officier_em";
		else
			$Off_exp="Officier";
		$courier=GetData("Joueur",$Off,$Recepteur,"Courier");
		if($courier)
		{
			$adresse=GetData("Joueur",$Off,$Recepteur,"adresse");
			$auteur=GetData($Off_exp,"ID",$Expediteur,"Nom");
			mail ($adresse, "Aube des Aigles: Alerte de courrier" , "Vous avez reçu un message privé dans le jeu l Aube des Aigles\n\r ".$auteur." vous envoie :\n".$Sujet."\n\r".$Msg);
		}
		$msg.='Message envoyé!';
	}*/
}