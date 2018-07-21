<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$Pilote = $_SESSION['Pilote_pvp'];
if (isset($_SESSION['AccountID']) AND $Pilote > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    require_once 'jfv_inc_pvp.php';
    RetireCandidatPVP($Pilote, "profil");
    $_SESSION['Distance'] = 0;
    $_SESSION['done'] = false;
    $Premium = GetData("Joueur", "ID", $_SESSION['AccountID'], "Premium");
    $con = dbconnecti();
    $result = mysqli_query($con, "SELECT * FROM Pilote_PVP WHERE ID = $Pilote");
    mysqli_close($con);
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $ID = $data['ID'];
            $Nom = $data['Nom'];
            $Pays = $data['Pays'];
            $Country = $data['Pays'];
            $Unite = $data['Unit'];
            $Engagement = $data['Engagement'];
            $Reputation = $data['Reputation'];
            $Avancement = $data['Avancement'];
            $Courage = $data['Courage'];
            $Moral = $data['Moral'];
            $Pilot = floor($data['Pilotage']);
            $Navig = floor($data['Navigation']);
            $Tir = floor($data['Tir']);
            $Endu = $data['Endurance'];
            $Vue = floor($data['Vue']);
            $Acrobat = floor($data['Acrobatie']);
            $Bomb = floor($data['Bombardement']);
            $Tactic = floor($data['Tactique']);
            $Avion_Perso = $data['Avion_Perso'];
            $Proto = $data['Proto'];
            $Vic = $data['Victoires'];
            $Vic_prob = $data['Victoires_prob'];
            $Missions = $data['Missions'];
            $Raids = $data['Raids_Bomb'];
            $Raids_Nuit = $data['Raids_Bomb_Nuit'];
            $Recce = $data['Recce'];
            $Dive = $data['Dive'];
            $kreta = $data['kreta'];
            $Photo = $data['Photo'];
            $Actif = $data['Actif'];
            $Landings = $data['Landings'];
            $Points = $data['Points'];
            $Abattu = $data['Abattu'];
            $Equipage = $data['Equipage'];
            $Ailier = $data['Ailier'];
            $MIA = $data['MIA'];
            $medal0 = $data['medal0'];
            $medal1 = $data['medal1'];
            $medal2 = $data['medal2'];
            $medal3 = $data['medal3'];
            $medal4 = $data['medal4'];
            $medal5 = $data['medal5'];
            $medal6 = $data['medal6'];
            $medal7 = $data['medal7'];
            $medal8 = $data['medal8'];
            $medal9 = $data['medal9'];
            $medal10 = $data['medal10'];
            $medal11 = $data['medal11'];
            $medal12 = $data['medal12'];
            $medal13 = $data['medal13'];
            $medal14 = $data['medal14'];
            $medal15 = $data['medal15'];
            $medal16 = $data['medal16'];
            $medal17 = $data['medal17'];
            $medal18 = $data['medal18'];
        }
        mysqli_free_result($result);
        unset($data);
        $Photo = '<img src="./images/pilotes/pilote_pvp.jpg">';
        if ($Premium) {
            if ($Avancement > 500000)
                $Avancement_Max = 1000000;
            elseif ($Avancement > 200000)
                $Avancement_Max = 500000;
            elseif ($Avancement > 100000)
                $Avancement_Max = 200000;
            elseif ($Avancement > 50000)
                $Avancement_Max = 100000;
            elseif ($Avancement > 25000)
                $Avancement_Max = 50000;
            elseif ($Avancement > 10000)
                $Avancement_Max = 25000;
            elseif ($Avancement > 5000)
                $Avancement_Max = 10000;
            elseif ($Avancement > 3000)
                $Avancement_Max = 5000;
            elseif ($Avancement > 2000)
                $Avancement_Max = 3000;
            elseif ($Avancement > 1500)
                $Avancement_Max = 2000;
            elseif ($Avancement > 1000)
                $Avancement_Max = 1500;
            elseif ($Avancement > 500)
                $Avancement_Max = 1000;
            elseif ($Avancement > 300)
                $Avancement_Max = 500;
            elseif ($Avancement > 200)
                $Avancement_Max = 300;
            elseif ($Avancement > 100)
                $Avancement_Max = 200;
            else
                $Avancement_Max = 100;
            if ($Reputation > 100000)
                $Reputation_Max = 1000000;
            elseif ($Reputation > 10000)
                $Reputation_Max = 100000;
            elseif ($Reputation > 1000)
                $Reputation_Max = 10000;
            elseif ($Reputation > 500)
                $Reputation_Max = 1000;
            elseif ($Reputation > 50)
                $Reputation_Max = 500;
            else
                $Reputation_Max = 50;
            $Avancement_Max_Bar = $Avancement_Max / ($Avancement_Max / 100);
            $Reputation_Max_Bar = $Reputation_Max / ($Reputation_Max / 100);
            $Avancement_Bar = $Avancement / ($Avancement_Max / 100);
            $Reputation_Bar = $Reputation / ($Reputation_Max / 100);
            if ($PlayerID == 1 or $PlayerID == 2) {
                $PrintRep = $Reputation;
                $PrintAv = $Avancement;
            } else {
                $PrintRep = '';
                $PrintAv = '';
            }
        }
        ?>
        <script type="text/javascript">
            /***********************************************
             * Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
             * This notice MUST stay intact for legal use
             * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
             ***********************************************/

            var offsetxpoint = -500 //Customize x offset of tooltip
            var offsetypoint = -50 //Customize y offset of tooltip
            var ie = document.all
            var ns6 = document.getElementById && !document.all
            var enabletip = false
            if (ie || ns6)
                var tipobj = document.all ? document.all["dhtmltooltip"] : document.getElementById ? document.getElementById("dhtmltooltip") : ""

            function ietruebody() {
                return (document.compatMode && document.compatMode != "BackCompat") ? document.documentElement : document.body
            }

            function ddrivetip(thetext, thecolor, thewidth, theheight, theoffsetx, theoffsety) {
                if (ns6 || ie) {
                    if (typeof thewidth != "undefined") tipobj.style.width = thewidth + "px"
                    if (typeof theheight != "undefined") tipobj.style.height = theheight + "px"
                    if (typeof thecolor != "undefined" && thecolor != "") tipobj.style.backgroundColor = thecolor
                    tipobj.innerHTML = thetext
                    enabletip = true
                    return false
                }
            }

            function positiontip(e) {
                if (enabletip) {
                    var curX = (ns6) ? e.pageX : event.clientX + ietruebody().scrollLeft;
                    var curY = (ns6) ? e.pageY : event.clientY + ietruebody().scrollTop;
                    //Find out how close the mouse is to the corner of the window
                    var rightedge = ie && !window.opera ? ietruebody().clientWidth - event.clientX - offsetxpoint : window.innerWidth - e.clientX - offsetxpoint - 20
                    var bottomedge = ie && !window.opera ? ietruebody().clientHeight - event.clientY - offsetypoint : window.innerHeight - e.clientY - offsetypoint - 20

                    var leftedge = (offsetxpoint < 0) ? offsetxpoint * (-1) : -1000

                    //if the horizontal distance isn't enough to accomodate the width of the context menu
                    if (rightedge < tipobj.offsetWidth)
                    //move the horizontal position of the menu to the left by it's width
                        tipobj.style.left = ie ? ietruebody().scrollLeft + event.clientX - tipobj.offsetWidth + "px" : window.pageXOffset + e.clientX - tipobj.offsetWidth + "px"
                    else if (curX < leftedge)
                        tipobj.style.left = "5px"
                    else
                    //position the horizontal position of the menu where the mouse is positioned
                        tipobj.style.left = curX + offsetxpoint + "px"

                    //same concept with the vertical position
                    if (bottomedge < tipobj.offsetHeight)
                        tipobj.style.top = ie ? ietruebody().scrollTop + event.clientY - tipobj.offsetHeight - offsetypoint + "px" : window.pageYOffset + e.clientY - tipobj.offsetHeight - offsetypoint + "px"
                    else
                        tipobj.style.top = curY + offsetypoint + "px"
                    tipobj.style.visibility = "visible"
                }
            }

            function hideddrivetip() {
                if (ns6 || ie) {
                    enabletip = false
                    tipobj.style.visibility = "hidden"
                    tipobj.style.left = "-1000px"
                    tipobj.style.backgroundColor = ''
                    tipobj.style.width = ''
                }
            }

            document.onmousemove = positiontip
        </script>
        <h1>Profil</h1>
        <h2><?=$Nom?></h2>
        <div id="profil_photo"><?=$Photo?></div>
        <div id="profil_infos">
            <table>
                <tr>
                    <td><img src='./premium/images/Logo_ada_mini.png'></td>
                </tr>
                <tr>
                    <td>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <th>Engagement</th>
                </tr>
                <tr>
                    <td><? echo $Engagement; ?></td>
                </tr>
                <!--<tr><th title="Vous bénéficiez de 2 Missions de base chaque jour, le reste devant être gagné, soit en ramenant votre avion à la base, soit en demandant des missions supplémentaires en échange de CT. Dans tous les cas, vous avez droit à 6 Missions par jour au maximum.">Missions</th></tr>
			<tr><td title="Le premier chiffre doit être inférieur à 2 pour pouvoir partir en mission de vol. Le second représente le nombre de missions restantes pour la journée."><? echo $Missions_Jour . ' / ' . $Missions_Max; ?></td></tr>-->
            </table>
        </div>
        <div id="profil_skills">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th colspan='4'>Statistiques</th>
                </tr>
                </thead>
                <tr>
                    <th align="left">Score</th>
                    <th width="120px" align="right"><?=$Points?></th>
                </tr>
                <tr>
                    <th align="left">Missions</th>
                    <td width="120px" align="right"><?=$Missions?></td>
                </tr>
                <tr>
                    <th align="left">Atterrissages réussis</th>
                    <td width="120px" align="right"><?=$Landings?></td>
                </tr>
                <tr>
                    <th align="left">Raids Diurnes réussis</th>
                    <td width="120px" align="right"><?=$Raids?></td>
                </tr>
                <tr>
                    <th align="left">Raids Nocturnes réussis</th>
                    <td width="120px" align="right"><?=$Raids_Nuit?></td>
                </tr>
                <tr>
                    <th align="left">Véhicules détruits</th>
                    <td width="120px" align="right"><?=$Dive?></td>
                </tr>
                <tr>
                    <th align="left">Victoires confirmées</th>
                    <td width="120px" align="right"><?=$Vic?></td>
                </tr>
                <tr>
                    <th align="left">Victoires probables</th>
                    <td width="120px" align="right"><?=$Vic_prob?></td>
                </tr>
                <tr>
                    <th align="left">Reconnaissances réussies</th>
                    <td width="120px" align="right"><?=$Recce?></td>
                </tr>
                <tr>
                    <th align="left">Abattu</th>
                    <td width="120px" align="right"><?=$Abattu?></td>
                </tr>
            </table>
        </div>
        <div id="col_droite">
        <?/*if($Premium){
		$Bar_pc=round($Avancement_Bar,1,PHP_ROUND_HALF_DOWN); 
		$Bar_rep_pc=round($Reputation_Bar,1,PHP_ROUND_HALF_DOWN);?>
	<table class="table">
		<thead><tr><th>Premium</th></tr></thead>
		<tr><th>Avancement</th></tr>
		<tr><td><div class="progress">
		  <div class="progress-bar" role="progressbar" aria-valuenow="<?=$Avancement_Bar?>" aria-valuemin="0" aria-valuemax="<?=$Avancement_Max_Bar?>" style="width:<?=$Bar_pc?>%;"><?=$Bar_pc?>%</div>
		</div></td></tr>
		<tr><th>Réputation</th></tr>
		<tr><td><div class="progress">
		  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?=$Reputation_Bar?>" aria-valuemin="0" aria-valuemax="<?=$Reputation_Max_Bar?>" style="width:<?=$Bar_rep_pc?>%;"><?=$Bar_rep_pc?>%</div>
		</div></td></tr>
	</table>
	<?}?>
	</div>
	<div style='clear:both;'>
	<h3>Brevets et Décorations</h3>
	<table class='table'>
	<tr><td><div style='float:left;'>
		<?
		for($i=0;$i<=18;$i++)
		{
			$medal_txt=GetMedal_Name($Country,$i);
			$medal='medal'.$i;
			if($$medal >0)
			{
				$u++;
				if($i ==14 and $Country ==1)
					$mes.="<img title='".$medal_txt."' src='images/pmedal".$Country.$i."_".$medal14.".gif'>";
				elseif($i ==15 and $Country ==1)
					$mes.="<img title='".$medal_txt."' src='images/pmedal".$Country.$i."_".$medal15.".gif'>";
				else				
					$mes.="<img title='".$medal_txt."' src='images/pmedal".$Country.$i.".gif'>";					
			}
		}
		echo $mes;
		if($kreta ==1)
			echo "<img title='Armelband Kreta' src='images/pkreta.gif'>";					
		echo "</div></td></tr></table></div>";
	*/
    } else {
        echo "<h1>Un problème est survenu lors de la récupération des données de votre profil!</h1>";
        echo "<p>Si le problème persiste, contactez un administrateur via le forum</p>";
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";