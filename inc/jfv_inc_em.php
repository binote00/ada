<?php
if ($Flag_includes) {
    if ($OfficierEMID > 0) {
        $con = dbconnecti();
        $resultpj = mysqli_query($con, "SELECT Premium,Admin,Pilote_id FROM Joueur WHERE ID='" . $_SESSION['AccountID'] . "'");
        $resultoffem = mysqli_query($con, "SELECT Pays,Avancement,Front,Trait,Credits,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
        mysqli_close($con);
        if ($resultpj) {
            while ($datapj = mysqli_fetch_array($resultpj, MYSQLI_ASSOC)) {
                $Premium = $datapj['Premium'];
                $Admin = $datapj['Admin'];
                $Pilote_id = $datapj['Pilote_id'];
            }
            mysqli_free_result($resultpj);
            unset($datapj);
        }
        if ($resultoffem) {
            while ($dataoffem = mysqli_fetch_array($resultoffem, MYSQLI_ASSOC)) {
                $country = $dataoffem['Pays'];
                $Avancement = $dataoffem['Avancement'];
                $Front = $dataoffem['Front'];
                $Trait = $dataoffem['Trait'];
                $Credits = $dataoffem['Credits'];
                $Armee = $dataoffem['Armee'];
            }
            mysqli_free_result($resultoffem);
            unset($dataoffem);
        }
    }
    if ($Admin)
        $GHQ = true;
    elseif ($Front == 99) {
        $Planificateur = GetData("GHQ", "Pays", $country, "Planificateur");
        if ($Planificateur > 0 and $OfficierEMID == $Planificateur)
            $GHQ = true;
    } elseif ($country) {
        $con = dbconnecti();
        $resultem = mysqli_query($con, "SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Adjoint_Terre,Officier_Mer,Officier_Log,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk,Pool_ouvriers FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
        mysqli_close($con);
        if ($resultem) {
            while ($dataem = mysqli_fetch_array($resultem, MYSQLI_ASSOC)) {
                $Commandant = $dataem['Commandant'];
                $Officier_Adjoint = $dataem['Adjoint_EM'];
                $Officier_EM = $dataem['Officier_EM'];
                $Officier_Rens = $dataem['Officier_Rens'];
                $Adjoint_Terre = $dataem['Adjoint_Terre'];
                $Officier_Mer = $dataem['Officier_Mer'];
                $Officier_Log = $dataem['Officier_Log'];
                $Cdt_Chasse = $dataem['Cdt_Chasse'];
                $Cdt_Bomb = $dataem['Cdt_Bomb'];
                $Cdt_Reco = $dataem['Cdt_Reco'];
                $Cdt_Atk = $dataem['Cdt_Atk'];
                $Pool_ouvriers = $dataem['Pool_ouvriers'];
            }
            mysqli_free_result($resultem);
            unset($dataem);
        }
        if ($OfficierEMID > 0 and
            ($OfficierEMID == $Commandant or $OfficierEMID == $Officier_Adjoint or $OfficierEMID == $Adjoint_Terre or $OfficierEMID == $Officier_Mer or $OfficierEMID == $Officier_EM or $OfficierEMID == $Officier_Log))
            $memberEM = true;
    }
    /*else {
        $Front=12;
        $GHQ=false;
        $Commandant=1;
        $Officier_Adjoint=1;
        $Officier_EM=1;
        $Officier_Rens=1;
        $Adjoint_Terre=1;
        $Officier_Mer=1;
        $Officier_Log=1;
        $Cdt_Chasse=1;
        $Cdt_Bomb=1;
        $Cdt_Reco=1;
        $Cdt_Atk=1;
    }*/
}