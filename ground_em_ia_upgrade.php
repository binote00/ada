<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once './jfv_include.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_ground.inc.php';
    $Vehicule = Insec($_POST['Ve']);
    $Reput = Insec($_POST['Cr']);
    $Reg = Insec($_POST['Reg']);
    if ($Vehicule > 0 and $Reput > 0 and $Reg > 0) {
        $_SESSION['reg'] = $Reg;
        $con = dbconnecti();
        $result = mysqli_query($con, "SELECT Type,mobile,Flak,Portee,HP FROM Cible WHERE ID='$Vehicule'");
        $resultr = mysqli_query($con, "SELECT Vehicule_ID,Experience,CT FROM Regiment_IA WHERE ID='$Reg'");
        mysqli_close($con);
        if ($resultr) {
            while ($datar = mysqli_fetch_array($resultr, MYSQLI_ASSOC)) {
                $Credits = $datar['CT'];
                $Veh_Ori = $datar['Vehicule_ID'];
                $Exp_Ori = $datar['Experience'];
            }
            mysqli_free_result($resultr);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Type = $data['Type'];
                $mobile = $data['mobile'];
                $Flak = $data['Flak'];
                $Portee = $data['Portee'];
                if ($mobile == MOBILE_WATER or $mobile == MOBILE_RAIL) {
                    $HP = $data['HP'];
                    $Placement = PLACE_LARGE;
                    $Experience = 250;
                    $Veh_Nbr = 1;
                } else {
                    $HP = 0;
                    $Placement = PLACE_CASERNE;
                    $Experience = 50;
                    $Veh_Nbr = 1; //ceil(GetMaxVeh($Type,$mobile,$Flak,500000)/2);
                }
            }
            mysqli_free_result($result);
        }
        if ($Credits >= $Reput) {
            function Veh_family($Veh)
            {
                switch ($Veh) {
                    case 19:
                    case 21:
                    case 81:
                        $Veh_family = 21; //Sdkfz 7
                        break;
                    case 22:
                    case 412:
                    case 461:
                    case 462:
                    case 784:
                        $Veh_family = 22; //Pz I
                        break;
                    case 23:
                    case 124:
                    case 125:
                    case 248:
                    case 463:
                    case 725:
                    case 785:
                    case 786:
                        $Veh_family = 23; //Pz II
                        break;
                    case 24:
                    case 263:
                        $Veh_family = 24; //Cruiser I
                        break;
                    case 26:
                    case 701:
                        $Veh_family = 26; //T-13
                        break;
                    case 27:
                    case 174:
                    case 175:
                        $Veh_family = 27; //Renault AMR33
                        break;
                    case 29:
                    case 163:
                    case 164:
                    case 165:
                    case 224:
                    case 264:
                    case 265:
                    case 266:
                    case 549:
                    case 787:
                    case 788:
                    case 789:
                    case 790:
                        $Veh_family = 29; //Pz III
                        break;
                    case 30:
                    case 166:
                    case 167:
                    case 168:
                    case 346:
                    case 395:
                    case 520:
                    case 791:
                    case 801:
                        $Veh_family = 30; //Pz IV
                        break;
                    case 32:
                    case 782:
                    case 783:
                        $Veh_family = 32; //Pz38t
                        break;
                    case 33:
                    case 262:
                        $Veh_family = 33; //Cruiser II
                        break;
                    case 36:
                    case 260:
                    case 497:
                        $Veh_family = 33; //Matilda II
                        break;
                    case 38:
                    case 169:
                        $Veh_family = 38; //Hotchkiss H35
                        break;
                    case 39:
                    case 160:
                    case 796:
                        $Veh_family = 39; //Renault R35
                        break;
                    case 41:
                    case 818:
                        $Veh_family = 41; //Somua S35
                        break;
                    case 42:
                    case 161:
                        $Veh_family = 42; //Renault D1
                        break;
                    case 44:
                    case 45:
                    case 177:
                    case 541:
                        $Veh_family = 44; //Fiat Ansaldo M
                        break;
                    case 58:
                    case 120:
                    case 214:
                    case 215:
                        $Veh_family = 215; //Sdkfz 221
                        break;
                    case 60:
                    case 151:
                    case 748:
                        $Veh_family = 60; //Autoblinda
                        break;
                    case 65:
                    case 85:
                    case 180:
                    case 429:
                    case 739:
                        $Veh_family = 180; //Humber AC
                        break;
                    case 78:
                    case 79:
                    case 230:
                        $Veh_family = 78; //Carro Armato
                        break;
                    case 80:
                    case 436:
                        $Veh_family = 80; //Sdkfz 10
                        break;
                    case 91:
                    case 162:
                    case 253:
                        $Veh_family = 91; //Bren Carrier
                        break;
                    case 103:
                    case 107:
                    case 153:
                    case 268:
                    case 359:
                    case 407:
                        $Veh_family = 103; //Soldaten
                        break;
                    case 104:
                    case 109:
                    case 154:
                    case 361:
                    case 408:
                        $Veh_family = 104; //Infantrymen
                        break;
                    case 105:
                    case 110:
                    case 362:
                    case 409:
                        $Veh_family = 105; //Fantassin
                        break;
                    case 106:
                    case 111:
                    case 269:
                    case 360:
                    case 411:
                        $Veh_family = 106; //Soldati
                        break;
                    case 114:
                    case 209:
                        $Veh_family = 114; //Bedford QL
                        break;
                    case 117:
                    case 208:
                        $Veh_family = 117; //Matador
                        break;
                    case 118:
                    case 206:
                    case 237:
                        $Veh_family = 118; //Opel Blitz
                        break;
                    case 119:
                    case 781:
                        $Veh_family = 119; //Kfz
                        break;
                    case 121:
                    case 222:
                    case 223:
                    case 232:
                    case 384:
                    case 511:
                        $Veh_family = 121; //Sdkfz 251
                        break;
                    case 123:
                    case 465:
                    case 737:
                    case 754:
                    case 755:
                    case 756:
                    case 757:
                        $Veh_family = 123; //Sdkfz 23x
                        break;
                    case 133:
                    case 225:
                        $Veh_family = 133; //PaK36
                        break;
                    case 155:
                    case 156:
                        $Veh_family = 155; //RollsRoyce
                        break;
                    case 171:
                    case 172:
                    case 815:
                        $Veh_family = 171; //B1
                        break;
                    case 178:
                    case 179:
                    case 453:
                    case 517:
                    case 518:
                        $Veh_family = 178; //Crusader
                        break;
                    case 189:
                    case 190:
                    case 380:
                    case 418:
                    case 419:
                    case 521:
                        $Veh_family = 189; //StuG
                        break;
                    case 193:
                    case 194:
                    case 350:
                    case 389:
                    case 390:
                    case 499:
                    case 539:
                        $Veh_family = 193; //Valentine
                        break;
                    case 195:
                    case 196:
                        $Veh_family = 195; //Humber LRC
                        break;
                    case 203:
                    case 204:
                    case 205:
                        $Veh_family = 203; //Schneider 105
                        break;
                    case 211:
                    case 378:
                        $Veh_family = 211; //Fiat 626
                        break;
                    case 212:
                    case 213:
                        $Veh_family = 213; //Fiat 666
                        break;
                    case 217:
                    case 218:
                    case 219:
                    case 220:
                    case 221:
                        $Veh_family = 217; //Sdkfz 250
                        break;
                    case 235:
                    case 236:
                    case 340:
                        $Veh_family = 235; //2cm Flak30
                        break;
                    case 256:
                    case 257:
                    case 258:
                        $Veh_family = 256; //Marder III
                        break;
                    case 261:
                    case 397:
                    case 454:
                    case 523:
                    case 524:
                    case 525:
                    case 684:
                    case 804:
                        $Veh_family = 261; //Churchill
                        break;
                    case 270:
                    case 331:
                        $Veh_family = 270; //Inf
                        break;
                    case 286:
                    case 287:
                    case 288:
                        $Veh_family = 286; //BT-7
                        break;
                    case 289:
                    case 290:
                        $Veh_family = 289; //BA-10
                        break;
                    case 293:
                    case 294:
                    case 295:
                    case 296:
                    case 297:
                    case 301:
                    case 333:
                    case 422:
                    case 437:
                        $Veh_family = 293; //T-26
                        break;
                    case 298:
                    case 299:
                    case 402:
                        $Veh_family = 298; //leFH18
                        break;
                    case 304:
                    case 305:
                    case 306:
                        $Veh_family = 304; //T-28
                        break;
                    case 307:
                    case 308:
                    case 309:
                    case 310:
                    case 438:
                        $Veh_family = 307; //T-34
                        break;
                    case 314:
                    case 401:
                        $Veh_family = 314; //T-40
                        break;
                    case 317:
                    case 318:
                    case 319:
                    case 320:
                    case 321:
                    case 322:
                    case 325:
                        $Veh_family = 317; //KV-1
                        break;
                    case 326:
                    case 400:
                        $Veh_family = 326; //ZiS5
                        break;
                    case 327:
                    case 328:
                        $Veh_family = 327; //ZiS6
                        break;
                    case 334:
                    case 335:
                    case 338:
                        $Veh_family = 334; //Inf
                        break;
                    case 356:
                    case 357:
                    case 358:
                    case 540:
                        $Veh_family = 356; //Semovente
                        break;
                    case 366:
                    case 370:
                        $Veh_family = 366; //Gaz AAA
                        break;
                    case 372:
                    case 373:
                        $Veh_family = 372; //Katyusha
                        break;
                    case 379:
                    case 382:
                        $Veh_family = 379; //Sdkfz 251/Genie
                        break;
                    case 391:
                    case 392:
                        $Veh_family = 391; //MAN F4
                        break;
                    case 393:
                    case 394:
                        $Veh_family = 393; //NAG
                        break;
                    case 396:
                    case 802:
                    case 803:
                        $Veh_family = 396; //AEC
                        break;
                    case 404:
                    case 405:
                        $Veh_family = 404; //BA-64
                        break;
                    case 406:
                    case 421:
                        $Veh_family = 406; //SU-76
                        break;
                    case 455:
                    case 685:
                        $Veh_family = 455; //Sexton/Priest
                        break;
                    case 464:
                    case 681:
                    case 792:
                        $Veh_family = 464; //Panther
                        break;
                    case 473:
                    case 474:
                    case 475:
                    case 450:
                    case 457:
                    case 534:
                    case 546:
                    case 553:
                    case 555:
                    case 556:
                    case 735:
                    case 741:
                    case 765:
                        $Veh_family = 473; //Sherman
                        break;
                    case 492:
                    case 493:
                    case 494:
                    case 533:
                    case 545:
                    case 547:
                    case 449:
                        $Veh_family = 492; //Stuart
                        break;
                    case 507:
                    case 508:
                        $Veh_family = 507; //Marmon
                        break;
                    case 513:
                    case 514:
                        $Veh_family = 513; //Grille
                        break;
                    case 560:
                    case 561:
                        $Veh_family = 560; //Isuzu Type94
                        break;
                    case 590:
                    case 591:
                    case 593:
                    case 594:
                    case 807:
                        $Veh_family = 590; //Cavalier/Centaur
                        break;
                    case 602:
                    case 603:
                        $Veh_family = 602; //Type95
                        break;
                    case 607:
                    case 608:
                        $Veh_family = 607; //Type97
                        break;
                    case 610:
                    case 611:
                        $Veh_family = 610; //Ho-Ni
                        break;
                    case 695:
                    case 696:
                        $Veh_family = 695; //Canon de 75 GP
                        break;
                    case 736:
                    case 805:
                    case 806:
                        $Veh_family = 736; //Cromwell
                        break;
                    case 750:
                    case 751:
                    case 752:
                        $Veh_family = 750; //FlakPanzer
                        break;
                    default:
                        $Veh_family = $Veh;
                        break;
                }
                return $Veh_family;
            }

            if ($Exp_ori > 100 and Veh_family($Veh_ori) == Veh_family($Vehicule))
                $Base_xp = floor($Exp_ori / 2);
            else
                $Base_xp = 50;
            if ($Type == TYPE_SUB)
                $Skills_1 = array(25, 32, 35, 37, 43);
            elseif ($Type == TYPE_CV)
                $Skills_1 = array(25, 30, 36);
            elseif ($Type == 20 or $Type == 19 or $Type == 18) //Cuirassé & Croiseur
                $Skills_1 = array(15, 22, 25, 30, 31, 33, 34, 35, 36, 38, 41);
            elseif ($Type == 15 or $Type == 16 or $Type == 17) //Escorteurs
                $Skills_1 = array(25, 30, 35, 36, 37, 39, 40, 42);
            elseif ($Type == 14) //Pt navires
                $Skills_1 = array(25, 35, 36);
            elseif ($Categorie == 6) //MG
                $Skills_1 = array(3, 4, 6, 7, 9, 11, 13, 14, 23, 25, 29);
            elseif ($Type == 4) //Canon AT
                $Skills_1 = array(3, 6, 9, 11, 12, 14, 25);
            elseif ($Type == 6)
                $Skills_1 = array(6, 8, 9, 12, 15, 22, 25, 28);
            elseif ($Type == 8)
                $Skills_1 = array(6, 8, 9, 15, 20, 22, 25, 28);
            elseif ($Type == 9)
                $Skills_1 = array(1, 2, 3, 5, 6, 9, 10, 16, 18, 19, 21, 24, 25);
            elseif ($Type == 12)
                $Skills_1 = array(6, 9, 12, 14, 25, 30);
            elseif ($Type == 7 or $Type == 10 or $Type == 91)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 24, 25);
            elseif ($Type == 11)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 25, 30);
            elseif ($Type == 2 or $Type == 3 or $Type == 5 or $Type == 93)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 25);
            else //Inf
                $Skills_1 = array(3, 4, 6, 7, 9, 11, 13, 14, 17, 23, 25, 26, 29);
            $Skill_p = $Skills_1[mt_rand(0, count($Skills_1) - 1)];
            $Credits -= $Reput;
            $query = "UPDATE Regiment_IA SET Vehicule_ID=$Vehicule,Vehicule_Nbr=$Veh_Nbr,HP=$HP,Moral=100,Move=1,Position=0,Distance=$Portee,Experience=$Base_xp,Skill=$Skill_p,CT=$Credits WHERE ID=$Reg";
            $con = dbconnecti();
            $ok2 = mysqli_query($con, $query);
            mysqli_close($con);
            if ($ok2) {
                $_SESSION['msg'] = 'La Compagnie d\'état-major a été améliorée avec succès !<br>' . $Veh_Nbr . ' ' . GetVehiculeIcon($Vehicule, $country, 0, 0, $Front);
            } else
                $_SESSION['msg_red'] = 'Erreur lors de l\'amélioration de la Compagnie d\'état-major !';
        } else
            $_SESSION['msg_red'] = 'L\'unité ne bénéficie pas des crédits suffisants !';
        var_dump($ok2);
        header('Location: index.php?view=ground_em_ia');
        header('Location: ../index.php?view=ground_em_ia_list');
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";