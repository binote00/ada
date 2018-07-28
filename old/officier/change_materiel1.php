<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
if ($OfficierID > 0) {
    include_once './jfv_include.inc.php';
    include_once './jfv_ground.inc.php';
    include_once './jfv_txt.inc.php';
    $country = $_SESSION['country'];
    $Regiment = Insec($_POST['Rg']);
    $Vehicule = Insec($_POST['Ve']);
    $Reput = Insec($_POST['Cr']);
    if ($Regiment > 0 and $Vehicule and $Reput > 0) {
        $Credits = GetData("Officier", "ID", $OfficierID, "Credits");
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
                        $Veh_family = 22; //Pz I
                        break;
                    case 23:
                    case 124:
                    case 125:
                    case 248:
                    case 463:
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
                        $Veh_family = 29; //Pz III
                        break;
                    case 30:
                    case 166:
                    case 167:
                    case 168:
                    case 346:
                    case 395:
                    case 520:
                        $Veh_family = 30; //Pz IV
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
                        $Veh_family = 39; //Renault R35
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
                        $Veh_family = 60; //Autoblinda
                        break;
                    case 65:
                    case 85:
                    case 180:
                    case 429:
                        $Veh_family = 180; //Humber AC
                        break;
                    case 78:
                    case 79:
                    case 230:
                        $Veh_family = 78; //Carro Armato
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
                    case 121:
                    case 222:
                    case 223:
                    case 232:
                    case 384:
                    case 511:
                        $Veh_family = 121; //Sdkfz 251
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
                    case 404:
                    case 405:
                        $Veh_family = 404; //BA-64
                        break;
                    case 406:
                    case 421:
                        $Veh_family = 406; //SU-76
                        break;
                    case 473:
                    case 474:
                    case 475:
                    case 450:
                    case 457:
                    case 553:
                    case 555:
                    case 556:
                        $Veh_family = 473; //Sherman
                        break;
                    case 492:
                    case 493:
                    case 494:
                    case 547:
                    case 449:
                        $Veh_family = 492; //Stuart
                        break;
                    case 513:
                    case 514:
                        $Veh_family = 513; //Grille
                        break;
                    case 560:
                    case 561:
                        $Veh_family = 560; //Isuzu Type94
                        break;
                    case 602:
                    case 603:
                        $Veh_family = 602; //Type95
                        break;
                    case 607:
                    case 608:
                        $Veh_family = 607; //Type97
                        break;
                    default:
                        $Veh_family = $Veh;
                        break;
                }
                return $Veh_family;
            }

            $Trait_o = GetData("Officier", "ID", $OfficierID, "Trait");
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Categorie,Type,mobile,Flak,HP FROM Cible WHERE ID='$Vehicule'");
            $result2 = mysqli_query($con, "SELECT Vehicule_ID,Experience FROM Regiment WHERE ID='$Regiment'");
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Type = $data['Type'];
                    $Categorie = $data['Categorie'];
                    $mobile = $data['mobile'];
                    $Flak = $data['Flak'];
                    $HP_ori = $data['HP'];
                }
                mysqli_free_result($result);
            }
            if ($result2) {
                while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                    $Veh_ori = $data['Vehicule_ID'];
                    $Exp_ori = $data['Experience'];
                }
                mysqli_free_result($result2);
            }
            if ($mobile == 5)
                $HP = $HP_ori;
            else
                $HP = 0;
            if (!$Type or $Type == 93 or $Type == 96 or $Type == 97 or $Type == 99 or ($Type == 98 and $Categorie == 5))
                $Renfort = 50;
            elseif ($Type == 4 or $Type == 6 or $Type == 8 or $Type == 12 or $Type == 9 or $Type == 10 or $Type == 11 or $Type == 12 or $Type == 91 or $Type == 92 or $Flak)
                $Renfort = 2;
            elseif ($Type == 13 or $mobile == 5 or $Type == 37 or $Type == 95)
                $Renfort = 1;
            else
                $Renfort = 4;
            //XP
            if (Veh_family($Veh_ori) == Veh_family($Vehicule))
                $Base_xp = floor($Exp_ori / 2);
            elseif ($Trait_o == 12)
                $Base_xp = 50;
            else
                $Base_xp = 0;
            $query3 = "UPDATE Regiment SET Vehicule_ID='$Vehicule',Vehicule_Nbr='$Renfort',Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Muns=0,Experience='$Base_xp',HP='$HP' WHERE ID='$Regiment'";
            $con = dbconnecti();
            $ok3 = mysqli_query($con, $query3);
            mysqli_close($con);
            if ($ok3) {
                $img = "<img src='images/vehicules/vehicule" . $Vehicule . ".gif'>";
                $mes = "Matériel mis à jour";
            } else
                $mes = "Mise à joue échouée " . mysqli_error($con);
            UpdateCarac($OfficierID, "Credits", -$Reput, "Officier");
            $titre = "Changement de matériel";
        } else {
            $mes = "<h6>Crédits insuffisants !</h6>";
            $img = Afficher_Image('images/transfer_no' . $country . '.jpg', "images/image.png", "Refus", 50);
        }
        $menu = Output::linkBtn('index.php?view=ground_menu', 'Retour au menu Ordres');
        include_once './index.php';
    } else
        echo "Tsss";
}