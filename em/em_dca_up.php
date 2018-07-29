<?php
/**
 * User: JF
 * Date: 29-07-18
 * Time: 10:51
 */
require_once '../jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once '../jfv_include.inc.php';
    include_once '../jfv_inc_em.php';
    if ($OfficierEMID == $Commandant || $OfficierEMID == $Officier_EM || $GHQ || $Admin) {
        $lieu = Insec($_POST['lieu']);
        $dca_up = Insec($_POST['dcaup']);
        $_SESSION['lieu_infra'] = $lieu;
        if ($lieu and $dca_up) {
            $dca_max = Lieu::getDcaMaxByNation($country);
            $dca_actu = Lieu::getDcaByNation($country);
            $lieu_o = Lieu::getByField("ID", $lieu);
            $dca_lieu = $lieu_o->DefenseAA_temp;
            var_dump($dca_lieu);
            var_dump($dca_up);
            var_dump($dca_actu);
            var_dump($dca_max);
            if (($dca_lieu + $dca_up <= 10) && ($dca_actu + $dca_up <= $dca_max)) {
                $dca_final = $dca_lieu + $dca_up;
                if (Lieu::setById($lieu, 'DefenseAA_temp', $dca_final)) {
                    $_SESSION['msg'] = 'une batterie de DCA a été installée à ' . $lieu_o->Nom;
                } else {
                    $_SESSION['msg_red'] = 'Action échouée!';
                }
            } else {
                $_SESSION['msg_red'] = 'Action refusée! '.$dca_lieu.'-'.$dca_up.'-'.$dca_actu.'-'.$dca_max;
            }
            header('Location: ../index.php?view=ville');
        }
    } else
        header('Location: ../index.php');
} else
    header('Location: ../index.php');