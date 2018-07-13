<?php
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']))
{
	if($Pilote_pvp >0 and $_SESSION['Distance'] ==0)
	{
		include_once('./jfv_include.inc.php');
		$date=date('Y-m-d G:i');
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Pilote_PVP SET Con_date='$date' WHERE ID='$Pilote_pvp'");
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Cible_Atk=0,S_Mission=0,S_Unite_Intercept=0,S_Pass=0,S_Strike=0,S_Cible=0,S_Escorte=0,S_Escorteb=0,S_Escorte_nbr=0,S_Escorteb_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Intercept_nbr=0,enis=0,avion_eni=0,Sandbox=0 WHERE ID='$Pilote_pvp'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-reset');
		$result=mysqli_query($con,"SELECT Front_sandbox,Pays,Avion_Sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-pil');
		$result_chato=mysqli_query($con,"SELECT Nom FROM Officier_PVP WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW()") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-offchat');
		$result_chatp=mysqli_query($con,"SELECT Nom FROM Pilote_PVP WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW()") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-pilchat');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Battle=$data['Front_sandbox'];
				$Faction=$data['Pays'];
				$Avion=$data['Avion_Sandbox'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result_chato)
		{
			while($datacd=mysqli_fetch_array($result_chato,MYSQLI_ASSOC))
			{
				$Connectesb.="<br><img src='images/led_green.png'> ".$datacd['Nom'];
			}
			mysqli_free_result($result_chato);
			unset($datacd);
		}
		if($result_chatp)
		{
			while($datacp=mysqli_fetch_array($result_chatp,MYSQLI_ASSOC))
			{
				$Connectesb.="<br><img src='images/led_green.png'> ".$datacp['Nom'];
			}
			mysqli_free_result($result_chatp);
			unset($datacp);
		}
		$con=dbconnecti(5);
		$result3=mysqli_query($con,"SELECT Bat_Date,Pts_Bat_Axe,Pts_Bat_Allies,Allies_inscrits,Axe_inscrits,DATE_FORMAT(Bat_Date,'%d-%m-%Y %H:%i') as Date_txt FROM gnmh_aubedesaiglesnet2.Battle_score WHERE ID='$Battle'");
		$resultc=mysqli_query($con,"SELECT ID,Battle,PlayerID,Mode,Faction,Msg,DATE_FORMAT(BDate,'%d-%m %H:%i') as Date_Chat FROM Bchat WHERE Battle IN(0,'$Battle') ORDER BY ID DESC LIMIT 10") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-chat');
		mysqli_close($con);
		if($result3)
		{
			while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Depart=$data3['Bat_Date'];
				$Date_txt=$data3['Date_txt'];
				$Allies_inscrits=$data3['Allies_inscrits'];
				$Axe_inscrits=$data3['Axe_inscrits'];
			}
			mysqli_free_result($result3);
		}
		if($resultc)
		{
			while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
			{
				$Battle_chat=$datac['Battle'];
				$Faction_chat=$datac['Faction'];
				$Chat_txt=nl2br($datac['Msg']);
				if($datac['Mode']==1)
					$DB_Chatter="Pilote_PVP";
				elseif($datac['Mode']==2)
					$DB_Chatter="Officier_PVP";
				elseif($datac['Mode']==9)
					$DB_Chatter="ADMIN";
				$Chatter=GetData($DB_Chatter,"ID",$datac['PlayerID'],"Nom");
				if(!$Battle_chat)
				{
					$Chat_open_b.="<br>".$datac['Date_Chat']." : [".$Chatter."] >".$Chat_txt;
				}
				elseif($Faction_chat ==$Faction)
				{
					$Chat_faction_b.="<br>".$datac['Date_Chat']." : [".$Chatter."] >".$Chat_txt;
				}
			}
			mysqli_free_result($resultc);
			unset($datac);
		}
		if($Avion >0)
		{
			if($Battle and $Faction)
			{
				if($date >=$Depart and $Allies_inscrits >1 and $Axe_inscrits >1)
				{
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT ID,Type FROM Avion WHERE ID='$Avion'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-avion');
					mysqli_close($con);
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Type_avion=$data['Type'];
						}
						mysqli_free_result($result2);
						unset($data);
					}
					if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==5 or $Type_avion ==12)
						$Missions_txt="<option value='1'>Appui</option><option value='3' selected>Chasse</option><option value='5'>Reconnaissance tactique</option>";
					elseif($Type_avion ==2)
						$Missions_txt="<option value='8' selected>Bombardement stratégique</option><option value='2'>Bombardement tactique</option><option value='5'>Reconnaissance tactique</option>";
					elseif($Type_avion ==3)
						$Missions_txt="<option value='15' selected>Reconnaissance stratégique</option><option value='5'>Reconnaissance tactique</option><option value='2'>Bombardement tactique</option>";
					elseif($Type_avion ==7 or $Type_avion ==10)
						$Missions_txt="<option value='1'>Appui</option><option value='2' selected>Bombardement tactique</option><option value='8'>Bombardement stratégique</option><option value='5'>Reconnaissance tactique</option>";
					elseif($Type_avion ==9)
						$Missions_txt="<option value='2' selected>Bombardement tactique</option><option value='5'>Reconnaissance tactique</option>";
					elseif($Type_avion ==11)
						$Missions_txt="<option value='8' selected>Bombardement stratégique</option><option value='16'>Bombardement de nuit</option>";
					echo "<h1>Préparation de la mission</h1><div class='row'><div class='col-md-6'><h2><b>Choix de la mission</b></h2>
					<form action='index.php?view=takeoff_pvp' method='post'><select name='Type_M' class='form-control' style='width: 300px'>".$Missions_txt."</select>
					<input type='hidden' name='Camp' value='".$Faction."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Avion' value='".$Avion."'>
					<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form></div><div class='col-md-6'><img src='images/avions.jpg'></div></div>";
					echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
					<input type='hidden' name='Battle' value='".$Battle."'>
					<input type='hidden' name='Camp' value='".$Faction."'>
					<input type='text' name='Mes' size='50' class='form-control'></div>
					<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
					<div class='col-md-2'><form action='index.php?view=mission_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
					<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_faction_b."</p></div></div></div>";
				}
				else
				{
					echo "<h1>Préparation de la mission</h1>La bataille n'a pas encore commencé. Elle débutera le <b>".$Date_txt."</b>";
					echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
					<input type='hidden' name='Battle' value='0'>
					<input type='hidden' name='Camp' value='0'>
					<input type='text' name='Mes' size='50' class='form-control'></div>
					<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
					<div class='col-md-2'><form action='index.php?view=mission_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
					<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";
				}
			}
		}
		else
		{
			echo "<h1>Préparation de la mission</h1>Vous ne pouvez pas participer à cette bataille historique!";
			echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
			<input type='hidden' name='Battle' value='0'>
			<input type='hidden' name='Camp' value='0'>
			<input type='text' name='Mes' size='50' class='form-control'></div>
			<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
			<div class='col-md-2'><form action='index.php?view=mission_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
			<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectes."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";
			/*echo "<h1>Préparation de la mission</h1><img src='images/briefing.jpg'><h2><b>Choix de la mission</b></h2>
			<form action='index.php?view=mission0_pvp' method='post'>
			<select name='Camp' class='form-control' style='width: 300px'>
				<option value='2' selected>Alliés</option>
				<option value='1'>Axe</option>
			</select>
			<select name='Battle' class='form-control' style='width: 300px'>
				<option value='1'>05/1940 - Maastricht</option>
			</select>
			<select name='Type' class='form-control' style='width: 300px'>
				<option value='8'>[Bombardier] Bombardement stratégique</option>
				<option value='2'>[Bombardier] Bombardement tactique</option>
				<option value='4'>[Chasse] Escorte</option>
				<option value='7'>[Chasse] Patrouille</option>
				<option value='5'>[Reco] Reconnaissance tactique</option>
				<option value='15'>[Reco] Reconnaissance stratégique</option>
			</select>
			<p><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form>";
	
				<option value='2'>05/1940 - Sedan</option>
				<option value='3'>07/1940 - Douvres</option>
				<option value='4'>08/1940 - Malte</option>	//Gladiator,Hurricane,Swordfish,SM79,CR42
				<option value='5'>01/1941 - Malte</option> //Hurricane,Fulmar,Maryland,Wellington,Swordfish,SM79,CR42,Ju87,Bf109E,Bf110C
				<option value='6'>06/1941 - Malte</option> //Hurricane MkIIB/C,BlenheimIV,Beaufighter,Bf109E-7
				<option value='7'>09/1941 - Leningrad</option>
				<option value='8'>12/1941 - Pearl Harbor</option>
				<option value='9'>04/1942 - Leningrad</option>
				<option value='10'>05/1942 - Malte</option> //Spitfire MkV,Beaufort,Bf109F-2,Bf110C/E,Ju88A/C,SM79
				<option value='11'>06/1942 - Midway</option>
				<option value='12'>09/1942 - Stalingrad</option>
			*/
			//<option value='23' selected>[Transport] Ravitaillement</option>
		}
	}
	else
		echo "<p>Une fois le départ en mission confirmé, vous ne pouvez accéder aux autres menus du jeu sous peine de réinitialisation de la mission.</p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";