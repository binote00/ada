<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
//$Officier=$_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
$PlayerID = $_SESSION['PlayerID'];
if ($Officier > 0 or $OfficierEMID > 0 or $PlayerID > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    $ID = Insec($_POST['mes']);
    $con = dbconnecti(3);
    $ok = mysqli_query($con, "SELECT DISTINCT DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Expediteur,Sujet,Message,Exp_em FROM Ada_Messages WHERE ID='$ID' LIMIT 1");
    if ($ok) {
        while ($data = mysqli_fetch_array($ok)) {
            $Exped = $data['Expediteur'];
            $Exp_em_ori = $data['Exp_em'];
            $Message = nl2br($data['Message']);
            $Sujet = $data['Sujet'];
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
            $Msg_Off .= $data['Date'] . '<p><b>' . $Expediteur . '</b></p><p>' . $data['Sujet'] . '</p><hr>' . $Message;
            //Update Lu
            $ok_up = mysqli_query($con, "UPDATE Ada_Messages SET Lu=1 WHERE ID='$ID'");
        }
    }
    mysqli_close($con);
    include_once __DIR__ . '/../view/menu_messagerie_gr.php';
    echo "<table class='table'><thead><tr><th>Message</th></tr></thead><tr><td align='left'>" . $Msg_Off . "</td></tr></table>";
    if ($Exped > 0) {
        if ($Officier > 0) {
            $Expe = $Officier;
            $Exp_em = 2;
        } elseif ($PlayerID > 0) {
            $Expe = $PlayerID;
            $Exp_em = 3;
        } elseif ($OfficierEMID > 0) {
            $Expe = $OfficierEMID;
            $Exp_em = 1;
        }
        ?>
        <form action='index.php?view=mail/archiver_msg_gr' method='post'>
            <input type='hidden' name='msg' value="<?= $ID; ?>">
            <input type="Submit" value="Effacer" class='btn btn-danger'
                   onclick='this.disabled=true;this.form.submit();'></form>
        <form action='index.php?view=mail/ground_envoyer' method='post'>
            <input type='hidden' name='destinataire' value="<?= $Exped; ?>">
            <input type='hidden' name='exp' value="<?= $Expe; ?>">
            <input type='hidden' name='dest_em' value="<?= $Exp_em_ori; ?>">
            <input type='hidden' name='em' value="<?= $Exp_em; ?>">
            <table class='table'>
                <thead>
                <tr>
                    <th colspan="2">Répondre</th>
                </tr>
                </thead>
            </table>
            <table border='0' cellspacing='2' cellpadding='5'>
                <tr>
                    <th>Destinataire</th>
                    <td align="left"><?= $Expediteur; ?></td>
                </tr>
                <tr>
                    <th>Sujet</th>
                    <td align="left"><input type="text" name="Sujet" value="RE: <?= $Sujet; ?>" size="50"
                                            class='form-control'></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td align="left"><textarea class='form-control' name="msg" rows="5" cols="50"></textarea></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Envoyer" class='btn btn-default'
                               onclick='this.disabled=true;this.form.submit();'></td>
                </tr>
            </table>
        </form>
        <?
    }
} else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';