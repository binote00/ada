<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	$ouvriers=Insec($_POST['ouvriers']);
	$recce=Insec($_POST['recce']);
	$usine=Insec($_POST['usine']);
	$fort=Insec($_POST['fort']);
	$gare=Insec($_POST['gare']);
	$pont=Insec($_POST['pont']);
	$port=Insec($_POST['port']);
	$dca=Insec($_POST['dca']);
	$dca_down=Insec($_POST['dcad']);
	$piste=Insec($_POST['piste']);
	$PisteR=Insec($_POST['rpiste']);
	$dur=Insec($_POST['dur']);
	$gardes=Insec($_POST['garnison']);
	$lieu=Insec($_POST['lieu']);
	$depot_off=Insec($_POST['depot_off']);
	$auto_repa=Insec($_POST['auto_repa']);
	include_once './jfv_inc_em.php';
	$no=false;
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_EM or $GHQ or $Admin)
	{
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
		if(!$Officier_EM)$EM_CT=1; //Coût réduit si pas d'EM infras
        $_SESSION['lieu_infra'] = $lieu;
		if($lieu and $dca_down)
		{
			$DefenseAA_temp=GetData("Lieu","ID",$lieu,"DefenseAA_temp");
			if($DefenseAA_temp >= $dca_down)
			{
				UpdateData("Lieu","DefenseAA_temp",-$dca_down,"ID",$lieu);
				//echo "<div class='alert alert-success'>".$dca_down." batterie de DCA a été démantelée.</div>";
                $_SESSION['msg'] = 'une batterie de DCA a été démantelée.';
			}
			else
                $_SESSION['msg_red'] = 'Action refusée!';
            header( 'Location : index.php?view=ville');
		}
		elseif($lieu) //and !$GHQ
		{
			if($Admin or $GHQ)
				$Pool_ouvriers=100;
			else
				$Pool_ouvriers=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Pool_ouvriers");
			if($depot_off)
			{
				if($depot_off ==2)$depot_off=0;
				SetData("Lieu","Depot_prive",$depot_off,"ID",$lieu);
                $_SESSION['msg_infra'] = 'L\'accès au dépôt a été modifié!';
            }
			if($auto_repa)
			{
				if($auto_repa ==2)$auto_repa=0;
				SetData("Lieu","Auto_repare",$auto_repa,"ID",$lieu);
                $_SESSION['msg_infra'] = 'La réparation automatique des infrastructures a été modifiée!';
			}
			if($recce >0)
			{
				$con=dbconnecti();
				$Observation=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$lieu' AND Task=1 AND Avion>0 AND Pays<>'$country' AND Actif=1"),0);
				mysqli_close($con);
				if($Observation >0)
                    $_SESSION['msg_infra'] = 'Des avions d\'observation ennemis ont été repérés au-dessus du site!';
				$CT_Discount=Get_CT_Discount($Avancement);
				$Cr_cam=7+$Observation-$CT_Discount;
				if($Credits >=$Cr_cam)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$lieu'");
					mysqli_close($con);
					UpdateData("Officier_em","Note",2,"ID",$OfficierEMID);
					AddEvent("Avion",107,10,$OfficierEMID,446,$lieu); //Unité NL non jouable pour simuler rapport EM
					$Credits-=$Cr_cam;
					$Cr_total+=$Cr_cam;
                    $_SESSION['msg_infra_ok'] .= 'Le site a été camouflé avec succès!';
				}
			}
			if($usine)
			{
				$down=$usine*GetModCT(10,$country,$EM_CT);
				if($Admin)echo "<p>Credits=".$Credits." , usine=".$usine." , Pool_ouvriers=".$Pool_ouvriers." , down=".$down."</p>";
				if($Credits >=($usine*3) and $Pool_ouvriers >=$down)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					if($reset)
					{
						UpdateData("Lieu","Industrie",$down,"ID",$lieu,100);
						$Credits-=($usine*3);
						$Cr_total+=($usine*3);
						$Pool_ouvriers-=$down;
                        $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer l\'usine.';
					}
				}
			}
			if($ouvriers and $Pool_ouvriers >=$ouvriers)
			{
				$up=$ouvriers/10;
				UpdateData("Lieu","boostProd",$up,"ID",$lieu,100);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$ouvriers' WHERE Pays_ID='$country' AND Front='$Front'");
				mysqli_close($con);
                $_SESSION['msg_infra_ok'] = $ouvriers.' ouvriers ont été envoyés pour augmenter la production de l\'usine.';
			}
			if($fort)
			{
				/*$cout=($fort*10)+10;
				if($Credits >=$cout)*/
				$Cout_Fort=50;
				$Fortification=GetData("Lieu","ID",$lieu,"Fortification");
				if(!$Fortification)
					$Fortification=1;
				else
				{
					if($Fortification >50)$Cout_Fort=100;
					$Fortification/=10;
				}
				$down=$fort*GetModCT($Cout_Fort,$country,$EM_CT)*$Fortification;
				if($Pool_ouvriers>=$down)
				{
					$up=$fort*10;
					UpdateData("Lieu","Fortification",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					$Pool_ouvriers-=$down;
					//$Credits-=$cout;
					//$Cr_total+=$cout;
                    $_SESSION['msg_infra_ok'] =$down.' ouvriers ont été envoyés pour fortifier la caserne.';
				}
				else
                    $_SESSION['msg_infra'] = $Pool_ouvriers.' ouvriers sont disponibles, cet ordre en nécessite '.$down;
			}
			if($PisteR)
			{
				$pisteup=$PisteR*20;
				$ouvriersdown=$PisteR*GetModCT(100,$country,$EM_CT);
				if(($Credits >=24 and $Pool_ouvriers >=$ouvriersdown and $ouvriersdown >=50) or $Admin)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-".$ouvriersdown." WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					if($reset)
					{
						UpdateData("Lieu","QualitePiste",$pisteup,"ID",$lieu,100);
						$Credits-=24;
						$Cr_total+=24;
						$Pool_ouvriers-=$ouvriersdown;
                        $_SESSION['msg_infra_ok'] = $ouvriersdown.' ouvriers ont été envoyés pour réparer la piste.';
					}
				}
			}
			if($piste)
			{
				$down_ouv=GetModCT(100,$country,$EM_CT);
				if($Credits >=30 and $Pool_ouvriers >=$down_ouv)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-".$down_ouv." WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					if($reset)
					{
						UpdateData("Lieu","LongPiste",50,"ID",$lieu,2000);
						$Credits-=30;
						$Cr_total+=30;
						$Pool_ouvriers-=$down_ouv;
                        $_SESSION['msg_infra_ok'] = $down_ouv.' ouvriers ont été envoyés pour agrandir la piste.';
					}
				}
			}
			if($dur)
			{				
				$down_ouv=GetModCT(199,$country,$EM_CT);
				if($Credits >=$CT_MAX and $Pool_ouvriers >=$down_ouv)
				{
					$con=dbconnecti();
					$resetpiste=mysqli_query($con,"UPDATE Lieu SET BaseAerienne=1 WHERE ID='$lieu'");
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-".$down_ouv." WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);	
					if($reset and $resetpiste)
					{
						$Credits-=$CT_MAX;
						$Cr_total+=$CT_MAX;
						$Pool_ouvriers-=$down_ouv;
                        $_SESSION['msg_infra_ok'] = $down_ouv.' ouvriers ont été envoyés pour appliquer un revêtement en dur sur la piste.';
					}
				}
			}
			if($radar)
			{
				$down=$radar*GetModCT(20,$country,$EM_CT);
				if($Credits >=2 and $Pool_ouvriers >=$down)
				{
					$up=$radar;
					UpdateData("Lieu","Radar",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);	
					$Credits-=($radar*3);
					$Cr_total+=($radar*3);
					$Pool_ouvriers-=$down;
                    $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer le radar.';
				}
			}
			if($tour)
			{
				$down=$tour*GetModCT(10,$country,$EM_CT);
				if($Credits >=2 and $Pool_ouvriers >=$down)
				{
					$up=$tour;
					UpdateData("Lieu","Tour",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);	
					$Credits-=2;
					$Cr_total+=2;
					$Pool_ouvriers-=$down;
                    $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer la tour.';
				}
			}
			if($gare)
			{
				$down=$gare*GetModCT(10,$country,$EM_CT);
				if($Credits >=($gare*3) and $Pool_ouvriers >=$down)
				{
					$up=$gare;
					UpdateData("Lieu","NoeudF",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);	
					$Credits-=($gare*3);
					$Cr_total+=($gare*3);
					$Pool_ouvriers-=$down;
                    $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer le noeud ferroviaire.';
				}
			}
			if($pont)
			{
				$down=$pont*GetModCT(10,$country,$EM_CT);
				if($Credits >=($pont*3) and $Pool_ouvriers >=$down)
				{
					$up=$pont;
					UpdateData("Lieu","Pont",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					$Credits-=($pont*3);
					$Cr_total+=($pont*3);
					$Pool_ouvriers-=$down;
                    $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer le pont.';
				}
			}
			if($port)
			{
				$down=$port*GetModCT(10,$country,$EM_CT);
				if($Credits >=($port*3) and $Pool_ouvriers >=$down)
				{
					$up=$port;
					UpdateData("Lieu","Port",$up,"ID",$lieu,100);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					$Credits-=($port*3);
					$Cr_total+=($port*3);
					$Pool_ouvriers-=$down;
                    $_SESSION['msg_infra_ok'] = $down.' ouvriers ont été envoyés pour réparer le port.';
				}
			}
			if($dca)
			{
				/*if($Credits >=($dca*2))*/
				$DefenseAA_temp=GetData("Lieu","ID",$lieu,"DefenseAA_temp");
				if(!$DefenseAA_temp)$DefenseAA_temp=1;
				$down=$dca*GetModCT(10,$country,$EM_CT)*$DefenseAA_temp;
				if($Pool_ouvriers >=$down)
				{
					UpdateData("Lieu","DefenseAA_temp",$dca,"ID",$lieu,10);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					$Pool_ouvriers-=$down;
					/*$Credits-=($dca*2);
					$Cr_total+=($dca*2);*/
                    $_SESSION['msg_infra_ok'] = $dca.' batteries de DCA ont été envoyées en renfort.';
				}
			}
			if($gardes)
			{
				$Garnison_Max=(GetData("Lieu","ID",$lieu,"ValeurStrat")*200)+100;
				$gardes_up=$gardes*GetModCT(2,$country,$EM_CT);
				if($Credits >=$gardes_up)
				{
					$gardesup=$gardes*10;
					UpdateData("Lieu","Garnison",$gardesup,"ID",$lieu,$Garnison_Max);
					$Credits-=$gardes_up;
					$Cr_total+=$gardes_up;
                    $_SESSION['msg_infra_ok'] = $gardes.' escouades ont été envoyées en renfort.';
				}
			}
			if($Cr_total !=0)
			{
				//UpdateCarac($OfficierID,"Note",floor($Cr_total/8));
				UpdateCarac($OfficierEMID,"Credits",-$Cr_total,"Officier_em");
				if($Cr_total >10)$Cr_total=10;
                UpdateData("Officier_em","Avancement",$Cr_total,"ID",$OfficierEMID);
			}
            header( 'Location : index.php?view=ground_em_infras0');
		}
		else
			$no=true;
		//Old Way
		if($no)
		{
			echo Afficher_Image('images/transfer_no'.$country.'.jpg',"images/image.png","Refus",50);
			echo "<p>Cet ordre ne peut être exécuté!</p>";
		}
		else
		{
			echo Afficher_Image('images/transfer_yes'.$country.'.jpg',"images/image.png","Accord",50);
			echo "<p>Vos ordres ont été exécutés!</p>";
		}
		if($GHQ)
		    Output::linkBtn('index.php?view=ville', 'Retour au menu');
		else
		{
			if($lieu)
				echo "<form action='index.php?view=ground_em1' method='post'><input type='hidden' name='lieu' value='".$lieu."'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>"; //lieu
            Output::linkBtn('index.php?view=ground_em_infras', 'Retour au menu');
		}
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';