<?php
require_once '../jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once '../jfv_include.inc.php';
    include_once '../jfv_txt.inc.php';
    include_once '../jfv_inc_em.php';
    if ($Admin) {
        $id = Insec($_POST['id']);
        $mode = Insec($_POST['mode']);
        if ($id > 0 and $mode > 0) {
            dbconnect();
            if ($mode == 1 or $mode == 2) {
                if ($mode == 2)
                    $vis = 1;
                else
                    $vis = 0;
                $result = $dbh->prepare("UPDATE Regiment_IA SET Visible=:vis WHERE ID=:id");
                $result->bindValue('id', $id, 1);
                $result->bindValue('vis', $vis, 1);
            } elseif ($mode == 3) {
                $result = $dbh->prepare("UPDATE Regiment_IA SET Moral=100,Atk=0,Move=0,Atk_time='0000-00-00 00:00:00',Move_time='0000-00-00 00:00:00' WHERE ID=:id");
                $result->bindValue('id', $id, 1);
            }
            $result->execute();
        }
    }
    header('Location: ../index.php?view=ground_em_ia_list');
}