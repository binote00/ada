<?php
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if ($PlayerID > 0) {
    if (is_array($Skills_Pil)) {
        if (in_array(1, $Skills_Pil)) {
            $Moral += 25;
            $Courage += 25;
        }
        if (in_array(5, $Skills_Pil))
            $Acrobatie = 150;
        elseif (in_array(4, $Skills_Pil))
            $Acrobatie = 125;
        elseif (in_array(3, $Skills_Pil))
            $Acrobatie = 100;
        elseif (in_array(2, $Skills_Pil))
            $Acrobatie = 75;
        else
            $Acrobatie = 50;
        if (in_array(9, $Skills_Pil))
            $Bombardement = 150;
        elseif (in_array(8, $Skills_Pil))
            $Bombardement = 125;
        elseif (in_array(7, $Skills_Pil))
            $Bombardement = 100;
        elseif (in_array(6, $Skills_Pil))
            $Bombardement = 75;
        else
            $Bombardement = 50;
        if (in_array(13, $Skills_Pil))
            $Vue = 150;
        elseif (in_array(12, $Skills_Pil))
            $Vue = 125;
        elseif (in_array(11, $Skills_Pil))
            $Vue = 100;
        elseif (in_array(10, $Skills_Pil))
            $Vue = 75;
        else
            $Vue = 50;
        if (in_array(17, $Skills_Pil))
            $Navigation = 150;
        elseif (in_array(16, $Skills_Pil))
            $Navigation = 125;
        elseif (in_array(15, $Skills_Pil))
            $Navigation = 100;
        elseif (in_array(14, $Skills_Pil))
            $Navigation = 75;
        else
            $Navigation = 50;
        if (in_array(21, $Skills_Pil))
            $Pilotage = 150;
        elseif (in_array(20, $Skills_Pil))
            $Pilotage = 125;
        elseif (in_array(19, $Skills_Pil))
            $Pilotage = 100;
        elseif (in_array(18, $Skills_Pil))
            $Pilotage = 75;
        else
            $Pilotage = 50;
        if (in_array(25, $Skills_Pil))
            $Tactique = 150;
        elseif (in_array(24, $Skills_Pil))
            $Tactique = 125;
        elseif (in_array(23, $Skills_Pil))
            $Tactique = 100;
        elseif (in_array(22, $Skills_Pil))
            $Tactique = 75;
        else
            $Tactique = 50;
        if (in_array(29, $Skills_Pil))
            $Tir = 150;
        elseif (in_array(28, $Skills_Pil))
            $Tir = 125;
        elseif (in_array(27, $Skills_Pil))
            $Tir = 100;
        elseif (in_array(26, $Skills_Pil))
            $Tir = 75;
        else
            $Tir = 50;
    }
}