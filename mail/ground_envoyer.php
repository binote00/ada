<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$Officier = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
$PlayerID = $_SESSION['PlayerID'];
if ($Officier > 0 or $OfficierEMID > 0 or $PlayerID > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    $Sujet = htmlspecialchars(Insec($_POST['Sujet']));
    $Msg = htmlspecialchars(Insec($_POST['msg']));
    $Destinataire = Insec($_POST['destinataire']);
    $Expediteur = Insec($_POST['exp']);
    $Exp_em = Insec($_POST['em']);
    $Rec_em = Insec($_POST['dest_em']);
    $country = $_SESSION['country'];
    $date = date('Y-m-d G:i');
    include_once __DIR__ . '/../view/menu_messagerie_gr.php';
    if (($Expediteur and $Destinataire) or $Admin or $Anim) {
        if (!$Destinataire)
            echo "<p>Vous avez envoyé un message à l'équipe d'animation du jeu.<br>Tout message ne concernant pas le roleplay de votre personnage ou une réponse à une question posée par l'équipe d'animation ne sera pas traité.</p>";
        elseif (strpos($Destinataire, "_") !== false) {
            $Destinataire = strstr($Destinataire, '_', true);
            $Rec_em = 1;
        } elseif (strpos($Destinataire, "ç") !== false) {
            $Destinataire = strstr($Destinataire, 'ç', true);
            $Rec_em = 3;
        } elseif (!$Rec_em)
            $Rec_em = 2;
        $query = "INSERT INTO Ada_Messages (Expediteur, Reception, Date, Message, Sujet, Exp_em, Rec_em)
		VALUES ('$Expediteur','$Destinataire','$date','$Msg','$Sujet','$Exp_em','$Rec_em')";
        $con = dbconnecti(3);
        //$Sujet = mysqli_real_escape_string($con,$Sujet);
        //$Msg = mysqli_real_escape_string($con,$Msg);
        $ok = mysqli_query($con, $query);
        mysqli_close($con);
        if ($ok) {
            echo '<div class="alert alert-warning">Message envoyé avec succès!</div>';
            echo '<img src="./images/poste' . $country . '.jpg">';
        } else
            echo '<div class="alert alert-danger">Erreur d\'envoi de message!</div>';
    } else
        echo "Erreur d'envoi de message!<br>Pas d'expéditeur ou destinataire inconnu !";
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";