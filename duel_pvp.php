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
		$result_chatp=mysqli_query($con,"SELECT Nom FROM Pilote_PVP WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW()") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-pilchat');
		mysqli_close($con);
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
		$resultc=mysqli_query($con,"SELECT ID,Battle,PlayerID,Mode,Faction,Msg,DATE_FORMAT(BDate,'%d-%m %H:%i') as Date_Chat FROM Bchat WHERE Battle IN(0,'$Battle') ORDER BY ID DESC LIMIT 10") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission_pvp-chat');
		mysqli_close($con);
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
		echo "<h1>Préparation de la mission</h1>
		<div class='row'><div class='col-md-6'><img src='images/briefing.jpg'></div><div class='col-md-6 alert alert-warning'><b>Le mode Duel est un mode de jeu où vous incarnez un pilote de chasse lors d'un scénario historique.</b>
		<br>Le but du jeu est simple : abattre vos adversaires et être le maître du ciel!
		<p>Vous pouvez accéder à ce mode de jeu quand vous le désirez, mais les seuls adversaires que vous rencontrerez seront d'autres joueurs connectés en même temps que vous sur le même scénario.</p>
		<p><i>Nous vous conseillons de contacter un ou plusieurs autres joueurs afin de vous donner rendez-vous pour un duel, afin de vous épargnez des minutes d'attente.</i></p></div></div>		
		<h2><b>Choix de la mission</b></h2>
		<form action='index.php?view=mission0_pvp' method='post'>
		<select name='Camp' class='form-control' style='width: 300px'>
			<option value='2' selected>Alliés</option>
			<option value='1'>Axe</option>
		</select>
		<select name='Battle' class='form-control' style='width: 300px'>
			<option value='1'>05/1940 - Maastricht</option>
			<option value='92'>05/1940 - Sedan</option>
			<option value='93'>07/1940 - Douvres</option>
			<option value='94'>08/1940 - Malte I</option>
			<option value='109'>08/1940 - Adlertag</option>
			<option value='95'>01/1941 - Malte II</option>
			<option value='96'>06/1941 - Malte III</option>
			<option value='104'>07/1941 - Jatkosota</option>			
			<option value='97'>09/1941 - Leningrad</option>
			<option value='98'>12/1941 - Pearl Harbor</option>
			<option value='99'>04/1942 - Leningrad</option>
			<option value='100'>05/1942 - Malte IV</option>
			<option value='101'>06/1942 - Midway</option>
			<option value='102'>09/1942 - Stalingrad</option>
			<option value='103'>11/1942 - Operation Uranus</option>
			<option value='105'>01/1943 - Kharkov</option>
			<option value='112'>04/1943 - Operation Vengeance</option>
			<option value='106'>07/1943 - Koursk</option>
			<option value='113'>08/1943 - Schweinfurt</option>
			<option value='111'>02/1944 - Operation Argument</option>
			<option value='110'>04/1944 - Nuremberg</option>
			<option value='107'>06/1944 - Operation Bagration</option>
			<option value='108'>01/1945 - Operation Bodenplatte</option>
		</select>
		<select name='Type' class='form-control' style='width: 300px'>
			<option value='3'>Duel</option>
		</select>
		<p><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form>";
		echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
		<input type='hidden' name='Battle' value='0'>
		<input type='hidden' name='Camp' value='0'>
		<input type='text' name='Mes' size='50' class='form-control'></div>
		<div class='col-md-2'><input type='submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
		<div class='col-md-2'><form action='index.php?view=mission_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
		<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectes."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";	
			/*<option value='2'>05/1940 - Sedan</option>
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
	else
		echo "<div class='alert alert-danger'>Une fois le départ en mission confirmé, vous ne pouvez accéder aux autres menus du jeu sous peine de réinitialisation de la mission.<br>Veuillez vous reconnecter.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";