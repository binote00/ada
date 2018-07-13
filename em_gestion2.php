<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$no=false;
		$country=$_SESSION['country'];
		include_once('./jfv_include.inc.php');
		include_once('./jfv_inc_em.php');
		include_once('./jfv_txt.inc.php');
		$Unitea=Insec($_POST['unitea']);
		$Staffela=Insec($_POST['sqna']);
		$Uniteb=Insec($_POST['uniteb']);
		$Staffelb=Insec($_POST['sqnb']);
		$Unitec=Insec($_POST['unitec']);
		$Staffelc=Insec($_POST['sqnc']);
		$Avionc=Insec($_POST['avionc']);
		$CT_Replace=Insec($_POST['CT']);
		//$Avion=Insec($_POST['modele']);
		$Nbr=Insec($_POST['nbr']);
		$Int=Insec($_POST['intervertir']);
		$lieu=Insec($_POST['lieu']);
		$auto_repa=Insec($_POST['auto_repa']);
		$Credits_Ori=$Credits;//inc_em
		if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM or $GHQ)
		{
			if($lieu)
			{
                $recce=Insec($_POST['recce']);
                $usine=Insec($_POST['usine']);
                $radar=Insec($_POST['radar']);
                $gare=Insec($_POST['gare']);
                $port=Insec($_POST['port']);
                $tour=Insec($_POST['tour']);
                $piste=Insec($_POST['piste']);
                $dca=Insec($_POST['dca']);
                include_once('./menu_em.php');
				if($auto_repa)
				{
					if($auto_repa ==2)$auto_repa=0;
					SetData("Lieu","Auto_repare",$auto_repa,"ID",$lieu);
					echo "La réparation automatique des infrastructures a été modifiée!<br>";
				}
				if($recce)
				{
					$CT_Discount=Get_CT_Discount($Avancement);
					$con=dbconnecti();
					$Observation=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$lieu' AND Task=1 AND Avion>0 AND Pays<>'$country' AND Actif=1"),0);
					mysqli_close($con);
					$Cr_cam=7+$Observation-$CT_Discount;
					if($Cr_cam <1)$Cr_cam=1;
					if($Credits_Ori >=$Cr_cam)
					{
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$lieu'");
						$reset2=mysqli_query($con,"UPDATE Unit SET Recce=0 WHERE Base='$lieu'");
						mysqli_close($con);
						UpdateCarac($OfficierEMID,"Avancement",10,"Officier_em");
						UpdateCarac($OfficierEMID,"Note",2,"Officier_em");
						AddEvent("Avion",107,10,$OfficierEMID,446,$lieu); //Unité NL non jouable pour simuler rapport EM
						$Credits_Ori-=$Cr_cam;
						$Cr_total+=$Cr_cam;
						echo "Le site a été camouflé avec succès!<br>";
					}
					else
						echo "Le camouflage est impossible, des avions d'observation ennemis ont été repérés au-dessus du site!<br>";
				}
				if($usine)
				{
					$down=$usine*20;
					if($Credits_Ori >=2 and $Pool_ouvriers >=$down)
					{
						$up=$usine;
						UpdateData("Lieu","Industrie",$up,"ID",$lieu,100);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=2;
						$Cr_total+=2;
						$Pool_ouvriers-=$down;
						echo $down." ouvriers ont été envoyés pour réparer l'usine.<br>";
					}
				}
				if($radar)
				{
					$down=$radar*20;
					if($Credits_Ori >=2 and $Pool_ouvriers >=$down)
					{
						$up=$radar;
						UpdateData("Lieu","Radar",$up,"ID",$lieu,100);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=2;
						$Cr_total+=2;
						$Pool_ouvriers-=$down;
						echo $down." ouvriers ont été envoyés pour réparer le radar.<br>";
					}
				}
				if($tour)
				{
					$down=$tour*10;
					if($Credits_Ori >=2 and $Pool_ouvriers >=$down)
					{
						$up=$tour;
						UpdateData("Lieu","Tour",$up,"ID",$lieu,100);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=2;
						$Cr_total+=2;
						$Pool_ouvriers-=$down;
						echo $down." ouvriers ont été envoyés pour réparer la tour.<br>";
					}
				}
				if($gare)
				{
					$down=$gare*20;
					if($Credits_Ori >=2 and $Pool_ouvriers >=$down)
					{
						$up=$gare;
						UpdateData("Lieu","NoeudF",$up,"ID",$lieu,100);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=2;
						$Cr_total+=2;
						$Pool_ouvriers-=$down;
						echo $down." ouvriers ont été envoyés pour réparer le noeud ferroviaire.<br>";
					}
				}
				if($port)
				{
					$down=$port*20;
					if($Credits_Ori >=2 and $Pool_ouvriers >=$down)
					{
						$up=$port;
						UpdateData("Lieu","Port",$up,"ID",$lieu,100);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=2;
						$Cr_total+=2;
						$Pool_ouvriers-=$down;
						echo $down." ouvriers ont été envoyés pour réparer le port.<br>";
					}
				}
				if($dca)
				{
					$DefenseAA_temp=GetData("Lieu","ID",$lieu,"DefenseAA_temp");
					if(!$DefenseAA_temp)$DefenseAA_temp=1;
					$down=$dca*10*$DefenseAA_temp;
					if($Pool_ouvriers >= $down)
					{
						UpdateData("Lieu","DefenseAA_temp",$dca,"ID",$lieu,10);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-'$down' WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);
						$Pool_ouvriers-=$down;
						echo $dca." batteries de DCA ont été envoyées en renfort.<br>";
					}
				}
				if($piste)
				{
					if($Credits_Ori >=30 and $Pool_ouvriers >=100)
					{
						UpdateData("Lieu","LongPiste",50,"ID",$lieu,2000);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers-100 WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);	
						$Credits_Ori-=30;
						$Cr_total+=30;
						$Pool_ouvriers-=100;
						echo "100 ouvriers ont été envoyés pour agrandir la piste.<br>";
					}
				}
				if($Cr_total !=0)
				{
					UpdateData("Officier_em","Credits",-$Cr_total,"ID",$OfficierEMID);
					UpdateCarac($OfficierEMID,"Avancement",$Cr_total,"Officier_em");
				}
			}
			elseif($Unitea and $Uniteb and $Staffela and $Staffelb and $Credits_Ori >=($Nbr*2) and ($Nbr or $Int))
			{
				$Squada='Avion'.$Staffela;
                $Squadb='Avion'.$Staffelb;
                $Avion_Nbra='Avion'.$Staffela.'_Nbr';
                $Avion_Nbrb='Avion'.$Staffelb.'_Nbr';
				$Nbra_chk=GetData("Unit","ID",$Unitea,$Avion_Nbra);
				$Nbrb_chk=GetData("Unit","ID",$Uniteb,$Avion_Nbrb);
				if($Nbra_chk >=$Nbr)
				{
					$con=dbconnecti();
					$resultunit=mysqli_query($con,"SELECT Type,Reputation,Base FROM Unit WHERE ID='$Uniteb'");
					mysqli_close($con);
					if($resultunit)
					{
						while($dataac=mysqli_fetch_array($resultunit,MYSQLI_ASSOC))
						{
							$Typeb=$dataac['Type'];
							$Reputb=$dataac['Reputation'];
							$Baseb=$dataac['Base'];
						}
						mysqli_free_result($resultunit);
					}
					$MaxFlightb=GetMaxFlight($Typeb,$Reputb,0);
					$Aviona=GetData("Unit","ID",$Unitea,$Squada);
					$Avionb=GetData("Unit","ID",$Uniteb,$Squadb);
					if($Int)
					{
						$Basea=GetData("Unit","ID",$Unitea,"Base");
						$Dist_Bases=GetDistance($Basea,$Baseb);
						if($Dist_Bases[0]>1000)
						{
							$comm_txt="<br>La distance entre les deux unités est trop importante! 1000km est un maximum";
							$no=true;
						}
						else
						{
							SetData("Unit",$Avion_Nbrb,$Nbra_chk,"ID",$Uniteb);
							SetData("Unit",$Squada,$Avionb,"ID",$Unitea);
							SetData("Unit",$Avion_Nbra,$Nbrb_chk,"ID",$Unitea);
							SetData("Unit",$Squadb,$Aviona,"ID",$Uniteb);
							UpdateData("Officier_em","Credits",-$CT_MAX,"ID",$OfficierEMID);
							UpdateCarac($OfficierEMID,"Reputation",50,"Officier_em");
							UpdateCarac($OfficierEMID,"Avancement",50,"Officier_em");
						}
					}
					elseif($Aviona ==$Avionb and $Nbrb_chk+$Nbr<$MaxFlightb+1)
					{
						UpdateData("Unit",$Avion_Nbrb,$Nbr,"ID",$Uniteb,$MaxFlightb);
						UpdateData("Unit",$Avion_Nbra,-$Nbr,"ID",$Unitea);
						$Credits=$Nbr*2;
						UpdateCarac($OfficierEMID,"Reputation",$Credits,"Officier_em");
						UpdateCarac($OfficierEMID,"Avancement",$Credits,"Officier_em");
						if(!$Admin)UpdateData("Officier_em","Credits",-$Credits,"ID",$OfficierEMID);
					}
					else
						$no=true;
					SetData("Unit","Avion".$Staffela."_Bombe_Nbr",0,"ID",$Unitea);
					SetData("Unit","Avion".$Staffelb."_Bombe_Nbr",0,"ID",$Uniteb);
					SetData("Unit","Avion".$Staffela."_Bombe",0,"ID",$Unitea);
					SetData("Unit","Avion".$Staffelb."_Bombe",0,"ID",$Uniteb);
				}
				else
				{
					echo "L'unité d'origine ne dispose pas de suffisamment d'avions!";
					UpdateCarac($OfficierEMID,"Reputation",-1,"Officier_em");
					UpdateCarac($OfficierEMID,"Avancement",-1,"Officier_em");
					$no=true;
				}
			}
			elseif($Unitec and $Staffelc and $Avionc)
			{
				if($CT_Replace >0)
					$Credits=$CT_Replace;
				else
					$Credits=12;
				$Squadc='Avion'.$Staffelc;
				SetData("Unit",$Squadc,$Avionc,"ID",$Unitec);
				SetData("Unit","Avion".$Staffelc."_Nbr",1,"ID",$Unitec);
				SetData("Unit","Avion".$Staffelc."_Bombe_Nbr",0,"ID",$Unitec);
				SetData("Unit","Avion".$Staffelc."_Bombe",0,"ID",$Unitec);
				UpdateData("Officier_em","Credits",-$Credits,"ID",$OfficierEMID);
				UpdateCarac($OfficierEMID,"Reputation",$Credits,"Officier_em");
				UpdateCarac($OfficierEMID,"Avancement",$Credits,"Officier_em");
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Unit SET Mission_Lieu=0,Mission_Type=0 WHERE ID='$Unitec'");
				$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Alt=0,Cible=0,Avion=0,Task=0 WHERE Unit='$Unitec'");
				mysqli_close($con);
                $_SESSION['msg_esc'] = GetAvionIcon($Avionc,$country,0,$Unitec,$Front).'<br>L\'unité a été équipée du matériel de réserve.<br>Vous annulez la mission d\'unité en cours et rappelez les pilotes à la base!';
                $_SESSION['esc'] = $Unitec;
                header( 'Location : ./index.php?view=em_ia');
			}
			else
				$no=true;
			if($no)
			{
				echo Afficher_Image('images/transfer_no'.$country.'.jpg',"images/image.png","Refus",50);
				echo '<div class="alert alert-danger">Cet ordre ne peut être exécuté!'.$comm_txt.'</div>';
			}
			else
			{
				echo Afficher_Image('images/transfer_yes'.$country.'.jpg',"images/image.png","Accord",50);
				echo '<div class="alert alert-success">Vos ordres ont été exécutés!</div>';
			}
			if($GHQ) echo "<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unitec."'><input type='Submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
			<br><a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour</a>";
			/*
			elseif($Unite and $Staffel and $Avion and $Nbr)
			{
				$Sqn=GetSqn($country);
				$Unite_Nom=GetData("Unit","ID",$Unite,"Nom");
				$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
				$CT_Avion=round(5+((500-GetData("Avion","ID",$Avion,"Production"))/100));
				if($CT_Avion <5)$CT_Avion=5;
				$Credits=$Nbr*$CT_Avion;
				if($Credits_Ori >=$Credits and $Mission_Jour <1)
				{
					$credits_txt=MoveCredits($PlayerID,3,-$Credits);
					UpdateCarac($PlayerID,"Commandement",$Credits);
					UpdateCarac($PlayerID,"Gestion",$Credits);
					UpdateCarac($PlayerID,"Missions_Jour",1);
					SetData("Unit","Avion".$Staffel,$Avion,"ID",$Unite);
					SetData("Unit","Avion".$Staffel."_Nbr",$Nbr,"ID",$Unite);
					echo Afficher_Image("images/avions/garage".$Avion.".jpg","images/image.png",$Avion_Nom);
					echo "Le ".$Staffel." ".$Sqn." du ".$Unite_Nom." recevra sous peu ".$Nbr." nouveaux ".$Avion_Nom;
				}
				else
				{
					echo Afficher_Image("images/transfer_no".$country.".jpg","images/image.png","Refus");
					echo "Vous ne disposez pas de suffisamment de temps pour donner cet ordre!";
				}
			}*/
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');