<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
//$Officier=$_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
$PlayerID = $_SESSION['PlayerID'];
if ($Officier > 0 xor $OfficierEMID > 0 xor $PlayerID > 0) {
    $country = $_SESSION['country'];
    require_once __DIR__ . '/../jfv_include.inc.php';
    include_once __DIR__ . '/../view/menu_messagerie_gr.php';
    $Msg = false;
    if ($Officier > 0)
        $query = "SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Exp_em FROM Ada_Messages WHERE Reception='$Officier' AND Archive='0' AND Rec_em='2' ORDER BY ID DESC LIMIT 50";
    elseif ($OfficierEMID > 0)
        $query = "SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Exp_em FROM Ada_Messages WHERE Reception='$OfficierEMID' AND Archive='0' AND Rec_em='1' ORDER BY ID DESC LIMIT 50";
    elseif ($PlayerID > 0)
        $query = "SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Exp_em FROM Ada_Messages WHERE Reception='$PlayerID' AND Archive='0' AND Rec_em='3' ORDER BY ID DESC LIMIT 50";
    $con = dbconnecti(3);
    $ok = mysqli_query($con, $query);
    mysqli_close($con);
    if ($ok) {
        while ($data = mysqli_fetch_array($ok)) {
            if ($data['Expediteur']) {
                if ($data['Exp_em'] == 1)
                    $Expediteur = GetData("Officier_em", "ID", $data['Expediteur'], "Nom");
                elseif ($data['Exp_em'] == 3)
                    $Expediteur = GetData("Pilote", "ID", $data['Expediteur'], "Nom");
                elseif ($data['Exp_em'] == 2)
                    $Expediteur = GetData("Officier", "ID", $data['Expediteur'], "Nom");
                else
                    $Expediteur = "[No-Reply]";
            } else
                $Expediteur = "[No-Reply]";
            $Msg_Off = $data['Date'] . ' : <b>' . $Expediteur . '</b><hr>' . $data['Sujet'];
            if ($data['Lu'])
                $Lire = "<input type='submit' value='Lire (lu)' class='btn btn-success'></form></td>";
            else
                $Lire = "<input type='submit' value='Lire (non lu)' class='btn btn-default'></form></td>";
            $Msg .= "<tr><td><form action='index.php?view=mail/ground_messages' method='post'><input type='hidden' name='mes' value='" . $data['ID'] . "'>" . $Lire . "<td align='left'>" . $Msg_Off . "</td></tr>";
        }
        mysqli_free_result($ok);
        unset($ok);
    } else
        $Msg .= "<tr><td align='left'>Vide</td></tr>";
    //Send list
    $Faction = GetData("Pays", "ID", $country, "Faction");
    $Premium = GetData("Joueur", "ID", $_SESSION['AccountID'], "Premium");
    if ($Premium) {
        //$query1="SELECT DISTINCT o.ID,o.Nom,o.Pays,o.Front FROM Officier as o,Pays as p WHERE o.Pays=p.ID AND p.Faction='$Faction' AND o.Actif='0' ORDER BY o.Pays ASC,o.Nom ASC";
        $query2 = "SELECT DISTINCT o.ID,o.Nom,o.Pays,o.Front FROM Officier_em as o,Pays as p WHERE o.Pays=p.ID AND p.Faction='$Faction' AND o.Actif='0' ORDER BY o.Pays ASC,o.Nom ASC";
        $query3 = "SELECT DISTINCT o.ID,o.Nom,o.Pays,o.Front FROM Pilote as o,Pays as p WHERE o.Pays=p.ID AND p.Faction='$Faction' AND o.Actif='0' ORDER BY o.Pays ASC,o.Nom ASC";
    } else {
        //$query1="SELECT DISTINCT ID,Nom,Pays,Front FROM Officier WHERE Pays='$country' AND Actif='0' AND ID<>'$Officier' ORDER BY Nom ASC";
        $query2 = "SELECT DISTINCT ID,Nom,Pays,Front FROM Officier_em WHERE Pays='$country' AND Actif='0' AND ID<>'$OfficierEMID' ORDER BY Nom ASC";
        $query3 = "SELECT DISTINCT ID,Nom,Pays,Front FROM Pilote WHERE Pays='$country' AND Actif='0' AND ID<>'$PlayerID' ORDER BY Nom ASC";
    }
    $con = dbconnecti();
    //$result=mysqli_query($con,$query1);
    $result2 = mysqli_query($con, $query2);
    $result3 = mysqli_query($con, $query3);
    mysqli_close($con);
    if ($result2) {
        $send_list .= "<optgroup label='Officier'>";
        while ($data2 = mysqli_fetch_array($result2)) {
            $send_list .= "<option value='" . $data2['ID'] . "_'>" . $data2['Nom'] . " (" . GetPays($data2['Pays']) . " - " . GetFront($data2['Front']) . ")</option>";
        }
        mysqli_free_result($result2);
        $send_list .= "</optgroup>";
    }
    if ($result3) {
        $send_list .= "<optgroup label='Pilote'>";
        while ($data3 = mysqli_fetch_array($result3)) {
            $send_list .= "<option value='" . $data3['ID'] . "ç'>" . $data3['Nom'] . " (" . GetPays($data3['Pays']) . " - " . GetFront($data3['Front']) . ")</option>";
        }
        mysqli_free_result($result3);
        $send_list .= "</optgroup>";
    }
    /*if($result)
    {
        $send_list.="<optgroup label='Officier'>";
        while($data=mysqli_fetch_array($result))
        {
            $send_list.="<option value='".$data['ID']."'>".$data['Nom']." (".GetPays($data['Pays'])." - ".GetFront($data['Front']).")</option>";
        }
        mysqli_free_result($result);
        $send_list.="</optgroup>";
    }*/
    echo "<table class='table'>
	<thead><tr><th colspan='2'>Boite de réception</th></tr></thead></table>
	<div style='overflow:auto; height: 400px;'><table class='table table-striped'>
	<tr>" . $Msg . "</table></div>";
    if ($Officier > 0) {
        echo "<form action='index.php?view=mail/ground_envoyer' method='post'>
		<input type='hidden' name='exp' value='" . $Officier . "'>
		<input type='hidden' name='em' value='2'>
		<table class='table'><thead><tr><th>Ecrire un message</th></tr></thead></table>
		<table border='0' cellspacing='2' cellpadding='5'>
			<tr><td><label for='destinataire'>Destinataire : </label></td><td align='left'><select name='destinataire' class='form-control' style='width: 300px'>" . $send_list . "</select></td></tr>
			<tr><td><label for='Sujet'>Sujet : </label></td><td align='left'><input type='text' class='form-control' name='Sujet' size='50'></td></tr>
			<tr><td><label for='msg'>Message</label></td><td align='left'><textarea class='form-control' name='msg' rows='5' cols='50'></textarea></td></tr>
			<tr><td><input type='submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
    } elseif ($OfficierEMID > 0) {
        echo "<form action='index.php?view=mail/ground_envoyer' method='post'>
		<input type='hidden' name='exp' value='" . $OfficierEMID . "'>
		<input type='hidden' name='em' value='1'>
		<table class='table'><thead><tr><th>Ecrire un message</th></tr></thead></table>
		<table border='0' cellspacing='2' cellpadding='5'>
			<tr><td><label for='destinataire'>Destinataire : </label></td><td align='left'><select name='destinataire' class='form-control' style='width: 300px'>" . $send_list . "</select></td></tr>
			<tr><td><label for='Sujet'>Sujet : </label></td><td align='left'><input type='text' class='form-control' name='Sujet' size='50'></td></tr>
			<tr><td><label for='msg'>Message</label></td><td align='left'><textarea class='form-control' name='msg' rows='5' cols='50'></textarea></td></tr>
			<tr><td><input type='submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
    } elseif ($PlayerID > 0) {
        echo "<form action='index.php?view=mail/ground_envoyer' method='post'>
		<input type='hidden' name='exp' value='" . $PlayerID . "'>
		<input type='hidden' name='em' value='3'>
		<table class='table'><thead><tr><th>Ecrire un message</th></tr></thead></table>
		<table border='0' cellspacing='2' cellpadding='5' bgcolor='#ECDDC1'>
			<tr><td><label for='destinataire'>Destinataire : </label></td><td align='left'><select name='destinataire' class='form-control' style='width: 300px'>" . $send_list . "</select></td></tr>
			<tr><td><label for='Sujet'>Sujet : </label></td><td align='left'><input type='text' class='form-control' name='Sujet' size='50'></td></tr>
			<tr><td><label for='msg'>Message</label></td><td align='left'><textarea class='form-control' name='msg' rows='5' cols='50'></textarea></td></tr>
			<tr><td><input type='submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";