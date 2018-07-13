<?
/*require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_nomission.inc.php');
if(isset($_SESSION['AccountID']))
{
	$PlayerID=Insec($_SESSION['PlayerID']);
	$country=Insec($_SESSION['country']);
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Credits FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
		}		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique,Reputation,Coffre1,Coffre2,Coffre3,Coffre4,Coffre5,Coffre6,Coffre7,Coffre8,Coffre9,Coffre10 FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unit_Nom = $data['Nom'];
				$Commandant = $data['Commandant'];
				$Officier_Adjoint = $data['Officier_Adjoint'];
				$Officier_Technique = $data['Officier_Technique'];
				$Reputation = $data['Reputation'];
				$Coffre1 = $data['Coffre1'];
				$Coffre2 = $data['Coffre2'];
				$Coffre3 = $data['Coffre3'];
				$Coffre4 = $data['Coffre4'];
				$Coffre5 = $data['Coffre5'];
				$Coffre6 = $data['Coffre6'];
				$Coffre7 = $data['Coffre7'];
				$Coffre8 = $data['Coffre8'];
				$Coffre9 = $data['Coffre9'];
				$Coffre10 = $data['Coffre10'];
			}
			mysqli_free_result($result);
			unset($data);
			unset($result);
		}		
		//Coffre
		$Coffre=floor($Reputation/10000)+1;
		if($Coffre >10)$Coffre=10;			
		$Acces_Off=false;
		if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
			$Acces_Off=true;		
		/*Pilotes
		$pils='<option value="0" selected>Personne</option>';
		$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE Unit='$Unite' ORDER BY Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$pils.="<option value=".$data['ID'].">".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
			unset($result);
			unset($data);
		}
		$pils.='</select>';*/		
		/*include_once('./menu_escadrille.php');
?>
		<h2>Equipement de l'unité <span><a href='aide_matos.php' target='_blank' title='Aide à propos du matériel'><img src='images/help.png' title='Aide'></a></span></h2>
		<p class='lead'>Equiper un objet remplacera l'objet existant dans l'inventaire de votre pilote!</p>
		<?
		for($c=1;$c<11;$c++)
		{
			$Coffre="Coffre".$c;
			if($$Coffre >0)
			{
			?><br><img src="images/matos<?echo $$Coffre;?>.gif" title="<?if($$Coffre){echo GetData("Matos","ID",$$Coffre,"Nom");}?>"><?	
			if($Credits > 11 and $$Coffre)
			{
				?><form action='esc_equipement1.php' method='post'><input type='hidden' name='Action' value="1"><input type='hidden' name='Slot' value="<? echo $c;?>"><input type='hidden' name='Item' value="<? echo $$Coffre;?>"><input type='Submit' value='Equiper (12 CT)' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><?
			}
			if($Acces_Off)
			{
				if($$Coffre)
				{
					?><form action='esc_equipement1.php' method='post'><input type='hidden' name='Action' value="0"><input type='hidden' name='Slot' value="<? echo $c;?>"><input type='hidden' name='Item' value="<? echo $$Coffre;?>"><input type='Submit' value='Effacer' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form><?		
				}
			}
			}
		}
	}
	else
	{
		//MIA
		$mes="<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php')*/;
?>