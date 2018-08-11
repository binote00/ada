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
		$msg='Erreur envoi de message : Exp='.$Expediteur.' ; Recepteur='.$Recepteur.' ; Sujet='.$Sujet;
		mail(EMAIL_LOG,'Aube des Aigles: SendMsg Error',$msg);
	}
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
		$msg='Erreur envoi de message : Exp='.$Expediteur.' ; Recepteur='.$Recepteur.' ; Sujet='.$Sujet.' ; Msg='.$Msg.' ; Error Msg='.mysqli_error($con);
		mail(EMAIL_LOG,'Aube des Aigles: SendMsgOff Error',$msg);
	}
	mysqli_close($con);
}