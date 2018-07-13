<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_events.inc.php');
include_once('./menu_actus.php');	
	/*$Date_time=time();
	$Date_start=strtotime($Date_debut);
	$tab=diff_date($Date_time,$Date_start);
	$Date_final="20120510"+($tab["mois"]*100)+($tab["semaine"]*7)+$tab["jour"];	
	if($Date_time > $Date_start)
	{*/
	$date=date('Y-m-d');
	if($date >"2013-01-01" and $date <"2013-02-01")
		echo "<img src='http://n.brajnov.free.fr/Aube-des-Aigles/Carte-2013.gif' border='1' alt='Bonne année'>";
	else
	{
		if($Date_Campagne >"1940-06-11" and $Date_Campagne <"1940-10-01")
		{
			switch($Date_Campagne)
			{
				//Bf109=2prdsZwdV7g
				//x/05=lenhYWIryhw (RAF vs Luft)
				//14/05=GVL5Q_Yk_wM
				//15/05=Hq2p-dmqvvs
				//20/05=20YWUd2vLLk
				//Bat France (luft vs Armée air)=LWZePlD3bIY
				//Norvège=KWgbDSVuBOU et wy4_ex0tXJ4
				//Dunkerque=tAsOpSkdOEE
				//10/06=bSGT7qaxANA
				//11/06=hF2_JzyBe7s
				//Front Est Luftwaffe=I6nrgmmcoxE
				case "1940-06-12":
					$id="VdigfUlNHAE";
				break;
				case "1940-06-13":
					$id="JXiAAgNyLew";
				break;
				case "1940-06-14":
					$id="fAH5_hPPc9k";
				break;
				case "1940-06-15":
					$id="BVaalZXD088";
				break;
				case "1940-06-16":
					$id="s87CKB5E3SQ";
				break;
				case "1940-06-17":
					$id="s87CKB5E3SQ";
				break;
				case "1940-06-18":
					$id="RfU90MTkYE4";
				break;
				case "1940-06-19":
					$id="xbgLyzoll7U";
				break;
				case "1940-06-20":
					$id="21jr7dVvu44";
				break;
				case "1940-06-21":
					$id="mbLyWuTcYLA";
				break;
				case "1940-06-22":
					$id="vmuCRCLU_h0";
				break;
				case "1940-06-23":
					$id="K1Zej7An9gA";
				break;
				case "1940-06-24":
					$id="YjERRm5_z80";
				break;
				case "1940-06-25":
					$id="mxdoPdildWE";
				break;
				case "1940-06-27":
					$id="r6koxIZuXD8";
				break;
				case "1940-06-28":
					$id="53QWL-yIofQ";
				break;
				case "1940-07-01":
					$id="B6qd9HAxMdo";
				break;
				case "1940-07-02":
					$id="wE2DeI2ZxFY";
				break;
				case "1940-07-03":
					$id="yCfY8FdrBTA";
				break;
				case "1940-07-04":
					$id="hRWn1WUIRKc";
				break;
				case "1940-08-01":
					$id="zLMSWVeHidU";
				break;
				case "1940-08-02":
					$id="19tk33yWDtw";
				break;
				case "1940-08-10":
					$id="thAqsLaP-uQ";
				break;
				case "1940-08-20":
					$id="GuIVYKKy-Dk";
				break;
				case "1940-09-01":
					$id="rcR9NOVIGXI";
				break;
			}
	?>
	<div align="center">
		<table border="1" cellspacing="1" cellpadding="1" bgcolor="#ECDDC1">
			<tr><th colspan="3" class="TitreBleu_bc">Les Actualités</th></tr>
			<tr><td colspan='3'>
				<iframe width="480" height="360" sandbox src="https://www.youtube.com/embed/<? echo $id; ?>"></iframe>
				<!--<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/<? //echo $id; ?>&rel=0"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/<? //echo $id; ?>&rel=0" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object> -->
				</td>
			<tr>
			<td><?echo $msg;?></td></tr>
		</table>
	</div>
	<hr>
	<?
		}
		elseif($Date_Campagne)
		{
			$msg="<a href='http://cheratte.net/aceofaces/forum/viewforum.php?f=124' target='_blank'>Récits de guerre</a>";
	?>
	<div align="center">
		<table border="1" cellspacing="1" cellpadding="1" bgcolor="#ECDDC1">
			<tr><th colspan="6" class="TitreBleu_bc">Les Actualités</th></tr>
			<tr><td colspan='6'><img src="images/old/journal<? echo $Date_Campagne; ?>.jpg"></td>
			<tr><td><?echo $msg;?></td></tr>
		</table>
	</div>
	<hr>
	<?
		}
		else
			$msg="La campagne n'a pas encore débuté.";
	}
	?>