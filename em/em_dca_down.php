<?php
/**
 * User: JF
 * Date: 29-07-18
 * Time: 10:15
 */
require_once '../jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once '../jfv_include.inc.php';
    include_once '../jfv_inc_em.php';
    if ($OfficierEMID == $Commandant || $OfficierEMID == $Officier_EM || $GHQ || $Admin) {
        $lieu = Insec($_POST['lieu']);
        $dca_down = Insec($_POST['dcad']);
        $_SESSION['lieu_infra'] = $lieu;
        if ($lieu and $dca_down) {
            $lieu_o = Lieu::getByField("ID", $lieu);
            $dca_lieu = $lieu_o->DefenseAA_temp;
            if ($dca_lieu >= $dca_down) {
                $dca_final = $dca_lieu - $dca_down;
                if (Lieu::setById($lieu, 'DefenseAA_temp', $dca_final)) {
                    $_SESSION['msg'] = 'une batterie de DCA a été démantelée à ' . $lieu_o->Nom;
                } else {
                    $_SESSION['msg_red'] = 'Action échouée!';
                }
            } else {
                $_SESSION['msg_red'] = 'Action refusée!';
            }
            header('Location: ../index.php?view=ville');
        }
    } else
        header('Location: ../index.php');
} else
    header('Location: ../index.php');
