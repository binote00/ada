<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 xor $OfficierID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Land=$_SESSION['country'];
	if($PlayerID >0)
	{
		$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
		$query="SELECT Front,Avancement FROM Pilote WHERE ID='$PlayerID'";
	}
	/*elseif($OfficierID >0)
		$query="SELECT Front,Avancement FROM Officier WHERE ID='$OfficierID'";*/
	elseif($OfficierEMID >0)
		$query="SELECT Front,Avancement,Postuler,Mutation,Armee FROM Officier_em WHERE ID='$OfficierEMID'";
	if(!$MIA and $_SESSION['Distance'] ==0)
	{		
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
        $pilote_id=mysqli_result(mysqli_query($con,"SELECT Pilote_id FROM Joueur WHERE ID=".$_SESSION['AccountID']),0);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front_Off=$data['Front'];
				$Avancement=$data['Avancement'];
				$Postuler=$data['Postuler'];
                $Mutation=$data['Mutation'];
				$Armee=$data['Armee'];
			}
			mysqli_free_result($result);
			unset($result);
		}
		$result=mysqli_query($con,"SELECT Front,Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Adjoint_Terre,Officier_Mer,Officier_Log,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk FROM Pays WHERE Pays_ID='$Land'");
		$resultghq=mysqli_query($con,"SELECT o.ID,o.Nom,o.Avancement,o.Photo,o.Photo_Premium FROM Officier_em as o,GHQ as g WHERE g.Pays='$Land' AND o.ID=g.Planificateur");
		$resultarmies=mysqli_query($con,"SELECT o.ID,o.Nom,o.Avancement,o.Photo,o.Photo_Premium,a.Front,a.Nom as Armee_Nom,DATE_FORMAT(Credits_date,'%d-%m-%Y') AS Activite FROM Officier_em as o,Armee as a WHERE a.Cdt=o.ID AND a.Pays='$Land'");
        $resultoff=mysqli_query($con,"SELECT o.ID,o.Nom,o.Avancement,o.Photo,o.Photo_Premium,o.Front,DATE_FORMAT(Credits_date,'%d-%m-%Y') AS Activite 
        FROM Officier_em as o 
        LEFT JOIN Pays as p ON p.ID=o.Pays
        WHERE o.Pays='$Land' AND o.Armee=0 AND p.Front=o.Front AND ((p.Commandant IS NOT NULL AND p.Commandant != o.ID) OR p.Commandant IS NULL) 
        AND ((p.Adjoint_EM IS NOT NULL AND p.Adjoint_EM != o.ID) OR p.Adjoint_EM IS NULL) 
        AND ((p.Adjoint_EM IS NOT NULL AND p.Officier_Mer != o.ID) OR p.Officier_Mer IS NULL) 
        AND ((p.Adjoint_EM IS NOT NULL AND p.Officier_Log != o.ID) OR p.Officier_Log IS NULL) 
        AND ((p.Adjoint_EM IS NOT NULL AND p.Officier_EM != o.ID) OR p.Officier_EM IS NULL) 
        AND o.Credits_date > CURDATE() - INTERVAL 15 DAY");
        $result_pil=mysqli_query($con,"SELECT ID,Pays,Nom,Avancement,Photo,Photo_Premium,Front,DATE_FORMAT(Credits_date,'%d-%m-%Y') AS Activite FROM Pilote WHERE Pays='$Land' AND Credits_date > CURDATE() - INTERVAL 7 DAY");
		if($resultghq)
		{
			while($dataghq=mysqli_fetch_array($resultghq,MYSQLI_ASSOC))
			{
				$Gr_pl=GetAvancement($dataghq['Avancement'],$Land,0,1);
				if($dataghq['Photo_Premium'] ==1)
					$img_perso="uploads/Officier/".$dataghq['ID']."_photo.jpg";
				else
					$img_perso="images/persos/general".$Land.$dataghq['Photo'].".jpg";
				$Planificateur="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
					<div class='row text-center'><img title='".$Gr_pl[0]."' src='images/grades/ranks".$Land.$Gr_pl[1].".png'> <h4><span class='label label-primary'>".$dataghq['Nom']."</span></h4></div>";
			}
			mysqli_free_result($resultghq);
		}
        if($result_pil)
        {
            while($data_pil=mysqli_fetch_array($result_pil,MYSQLI_ASSOC))
            {
                $Pilotes='Pilotes'.$data_pil['Front'];
                $Ar_gr=GetAvancement($data_pil['Avancement'],$Land);
                if($data_pil['Photo_Premium'] ==1)
                    $img_perso="uploads/Pilote/".$data_pil['ID']."_photo.jpg";
                else
                    $img_perso="images/persos/pilote".$Land.$data_pil['Photo'].".jpg";
                $$Pilotes.="<li class='list-inline-item' style='padding-left:15px;'><div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
						<div class='row text-center'><img title='".$Ar_gr[0]."' src='images/grades/grades".$Land.$Ar_gr[1].".png'> <h4><span class='label label-primary'>".$data_pil['Nom']."</span></h4><h5><span class='label label-danger'>".$data_pil['Activite']."</span></h5></div></li>";
            }
            mysqli_free_result($result_pil);
        }
        if($resultoff)
        {
            while($dataoff=mysqli_fetch_array($resultoff,MYSQLI_ASSOC))
            {
                $Reserve='Reserve'.$dataoff['Front'];
                $Arr_gr=GetAvancement($dataoff['Avancement'],$Land,0,1);
                if($dataoff['Photo_Premium'] ==1)
                    $img_perso="uploads/Officier/".$dataoff['ID']."_photo.jpg";
                else
                    $img_perso="images/persos/general".$Land.$dataoff['Photo'].".jpg";
                $$Reserve.="<li class='list-inline-item' style='padding-left:15px;'><div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
						<div class='row text-center'><img title='".$Arr_gr[0]."' src='images/grades/ranks".$Land.$Arr_gr[1].".png'> <h4><span class='label label-primary'>".$dataoff['Nom']."</span></h4><h5><span class='label label-danger'>".$dataoff['Activite']."</span></h5></div></li>";
            }
            mysqli_free_result($resultoff);
        }
		if($resultarmies)
		{
			while($dataarmies=mysqli_fetch_array($resultarmies,MYSQLI_ASSOC))
			{
				$Army='Army'.$dataarmies['Front'];
				$Ar_gr=GetAvancement($dataarmies['Avancement'],$Land,0,1);
				if($dataarmies['Photo_Premium'] ==1)
					$img_perso="uploads/Officier/".$dataarmies['ID']."_photo.jpg";
				else
					$img_perso="images/persos/general".$Land.$dataarmies['Photo'].".jpg";
				$$Army.="<li class='list-inline-item' style='padding-left:15px;'><div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
						<div class='row text-center'><img title='".$Ar_gr[0]."' src='images/grades/ranks".$Land.$Ar_gr[1].".png'> <h4><span class='label label-primary'>".$dataarmies['Nom']."</span></h4><h5><span class='label label-default'>".$dataarmies['Armee_Nom']."</span></h5><h5><span class='label label-danger'>".$dataarmies['Activite']."</span></h5></div></li>";
			}
			mysqli_free_result($resultarmies);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Commandant=$data['Commandant'];
				$Adjoint_EM=$data['Adjoint_EM'];
				$Officier_EM=$data['Officier_EM'];
				$Officier_Rens=$data['Officier_Rens'];
				$Officier_Terre=$data['Officier_Terre'];
				$Adjoint_Terre=$data['Adjoint_Terre'];
				$Officier_Mer=$data['Officier_Mer'];
				$Officier_Log=$data['Officier_Log'];
				$Cdt_Chasse=$data['Cdt_Chasse'];
				$Cdt_Bomb=$data['Cdt_Bomb'];
				$Cdt_Reco=$data['Cdt_Reco'];
				$Cdt_Atk=$data['Cdt_Atk'];
				$Cdt='Cdt'.$Front;
				$Adj='Adj'.$Front;
				$Off='Off'.$Front;
				$Rens='Rens'.$Front;
				$Terre='Terre'.$Front;
				$TAdj='TAdj'.$Front;
				$Mer='Mer'.$Front;
				$Log='Log'.$Front;
				$Chasse='Chasse'.$Front;
				$Bomb='Bomb'.$Front;
				$Reco='Reco'.$Front;
				$Atk='Atk'.$Front;
				if($Commandant)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Commandant'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av1=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Cdt="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av1[0]."' src='images/grades/ranks".$Land.$Av1[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><span class='label label-danger'>".$datao['Activite']."</span></div>";
							//$$Cdt=$Av1[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off and $Avancement >25000)
				{
					$$Cdt="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_off_em' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='21'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$Cdt="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i>
				<span><ul style='list-style-type: none;'><li>Il peut consulter les cartes, l'état des dépôts, les rapports et les alertes</li><li>Il peut contrôler les unités EM terrestres, aériennes et navales</li><li>Il gère les mutations</li><li>Il défini des missions de coordination</li></ul></span></a></div>";
				if($Adjoint_EM)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Adjoint_EM'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av2=GetAvancement($datao['Avancement'],$Land);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Adj="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av2[0]."' src='images/grades/grades".$Land.$Av2[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><span class='label label-danger'>".$datao['Activite']."</span></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off and $Avancement >25000)
				{
					$$Adj="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='2'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$Adj="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes, l'état des dépôts, les rapports et les alertes<br>Il contrôle les unités aériennes stratégiques et les unités tactiques de réserve, non assignées à une armée.</span></a></div>";
				if($Officier_EM)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_EM'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av3=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Off="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av3[0]."' src='images/grades/ranks".$Land.$Av3[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><span class='label label-danger'>".$datao['Activite']."</span></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				/*elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off)
				{
					$$Off="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='3'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}*/
				else
					$$Off="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes et l'état des infrastructures<br>Il gère les infrastructures des lieux contrôlés par sa nation</span></a></div>";
				/*if($Cdt_Chasse)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Cdt_Chasse'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av9=GetAvancement($datao['Avancement'],$Land);
							$$Chasse=$Av9[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and $Front !=12)
				{
					$$Chasse="<form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='9'>
						<input type='Submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				else
					$$Chasse="Poste vacant";
				if($Cdt_Bomb)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Cdt_Bomb'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av10=GetAvancement($datao['Avancement'],$Land);
							$$Bomb=$Av10[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and $Front !=12)
				{
					$$Bomb="<form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='10'>
						<input type='Submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				else
					$$Bomb="Poste vacant";
				if($Cdt_Reco)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Cdt_Reco'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av11=GetAvancement($datao['Avancement'],$Land);
							$$Reco=$Av11[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and $Front !=12)
				{
					$$Reco="<form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='11'>
						<input type='Submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				else
					$$Reco="Poste vacant";
				if($Cdt_Atk)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Cdt_Atk'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av12=GetAvancement($datao['Avancement'],$Land);
							$$Atk=$Av12[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and $Front !=12)
				{
					$$Atk="<form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='12'>
						<input type='Submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				else
					$$Atk="Poste vacant";
				if($Officier_Terre)
				{
					//$con=dbconnecti();
					$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Officier_Terre'");
					//mysqli_close($con);
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av5=GetAvancement($datao['Avancement'],$Land,0,1);
							$$Terre=$Av5[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and $Front !=12 and $Avancement >25000)
				{
					$$Terre="<form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='5'>
						<input type='Submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				else
					$$Terre="Poste vacant";
				if($Adjoint_Terre)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Adjoint_Terre'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av7=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$TAdj="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av7[0]."' src='images/grades/ranks".$Land.$Av7[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><i>".$datao['Activite']."</i></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off and $Avancement >25000)
				{
					$$TAdj="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='6'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$TAdj="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes, l'état des dépôts, les rapports et les alertes<br>Il contrôle les unités EM terrestres</span></a></div>";*/
				if($Officier_Mer)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Mer'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av8=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Mer="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av8[0]."' src='images/grades/ranks".$Land.$Av8[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><span class='label label-danger'>".$datao['Activite']."</span></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off and $Avancement >25000)
				{
					$$Mer="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='7'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$Mer="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes, l'état des dépôts, les rapports et les alertes<br>Il contrôle les unités navales de réserve, non assignées à une flotte</span></a></div>";
				/*if($Officier_Rens)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Rens'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av4=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Rens="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av4[0]."' src='images/grades/ranks".$Land.$Av4[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><i>".$datao['Activite']."</i></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off and !$Pilote_id)
				{
					$$Rens="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='4'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$Rens="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes, l'état des dépôts, la production, les infrastructures, les opérations, les rapports et les alertes<br>Il peut effectuer des actions de renseignement</span></a></div>";*/
				if($Officier_Log)
				{
					$resulto=mysqli_query($con,"SELECT ID,Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Log'");
					if($resulto)
					{
						while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
						{
							$Av6=GetAvancement($datao['Avancement'],$Land,0,1);
							if($datao['Photo_Premium'] ==1)
								$img_perso="uploads/Officier/".$datao['ID']."_photo.jpg";
							else
								$img_perso="images/persos/general".$Land.$datao['Photo'].".jpg";
							$$Log="<div class='row text-center'><img class='img-photo' src='".$img_perso."'></div>
							<div class='row text-center'><img title='".$Av6[0]."' src='images/grades/ranks".$Land.$Av6[1].".png'> <h4><span class='label label-primary'>".$datao['Nom']."</span></h4></div>
							<div class='row text-center'><span class='label label-danger'>".$datao['Activite']."</span></div>";
						}
						mysqli_free_result($resulto);
					}
				}
				elseif($OfficierEMID >0 and !$Postuler and !$Armee and !$Mutation and $Front !=12 and $Front ==$Front_Off)
				{
					$$Log="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><form action='index.php?view=postuler_em1' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'><input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='8'>
						<input type='submit' value='Postuler' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></div>";
				}
				else
					$$Log="<div class='row text-center'><img src='images/persos/general0.png'></div><div class='row'><a href='#' class='popup'><i class='label label-default'>Poste vacant</i><span>Il peut consulter les cartes, l'état des dépôts et la production<br>Il contrôle les unités logistiques telles que les cargos et les trains</span></a></div>";
			}
			mysqli_free_result($result);
			unset($result);
		}
		mysqli_close($con);
		include_once('./menu_em.php');
		if($OfficierEMID)
		{
			if($Front_Off ==12)
				echo "<div class='alert alert-warning'>Vous ne pouvez pas postuler pour un poste à l'état-major tant que vous n'avez pas <a href='index.php?view=ground_profile' class='lien'>demandé votre mutation</a> pour un front.</div>";
			elseif($Front_Off ==99)
				echo "<div class='alert alert-warning'>Vous ne pouvez pas postuler pour un poste à l'état-major tant que vous êtes planificateur stratégique.</div>";
			else
			{
				if((!$Postuler and !$Mutation and $OfficierEMID !=$Officier_EM and $OfficierEMID !=$Officier_Log and $OfficierEMID !=$Commandant and $OfficierEMID !=$Adjoint_EM and $OfficierEMID !=$Officier_Mer and $OfficierEMID !=$Officier_Rens) or $Admin)
					echo "<h2>Commandement d'armée</h2><div class='alert alert-warning'>Vous pouvez postuler à une fonction de <a href='#' class='popup'><b>Commandant d'armée</b><span>Le commandant d'armée donne les ordres quotidiens aux unités que lui attribue son commandant de front, tels que les déplacements et les actions offensives. Veiller à l'approvisionnement de ses troupes et à la communication avec l'état-major est recommandé.</span></a> si vous voulez prendre le contrôle d'une armée (troupes terrestres) ou d'une flotte (troupes navales).<br>La nomination sera validée ou non par le Commandant en Chef du front ou le Planificateur Stratégique.
					<br>En cas de changement de front ou si votre officier fait partie de la réserve, veillez à demander votre changement de front via le profil de votre officier <b>avant</b> de postuler.</div>
					<form action='index.php?view=postuler_armee' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$Land."'>
					<input type='hidden' name='Front' value='".$Front_Off."'><input type='hidden' name='poste' value='20'><input type='Submit' value='Postuler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
				elseif($Armee)
					echo "<div class='alert alert-warning'>Vous ne pouvez pas postuler pour un poste à l'état-major tant que vous commandez une armée, et inversément.<br>Pour changer de poste, vous demandez demander votre changement de front via <a href='index.php?view=ground_profile' class='lien'>le profil de l'officier</a></div>";
				echo "<div class='alert alert-danger'>Pour qu'une nation puisse fonctionner correctement, il est nécessaire que les postes de planificateur et de commandant de front soient occupés en priorité.<br>Il est inutile d'occuper des postes de pilotes si ces postes ne sont pas pourvus.
                <p>Toute absence de plus d'une semaine entraine une démission automatique du poste.<br>Pour changer de poste ou démissionner, il suffit de de demander son changement de front via <a href='index.php?view=ground_profile' class='lien'>le profil de l'officier</a></p></div>";
			}
		}
		if(!$Planificateur)
			$Planificateur="<div class='text-center'><img src='images/persos/general0.png'><p>Poste Vacant</p></div>";
		echo "<h2>Organigramme <span><a href='index.php?view=regles#tab_em' title='Aide'><img src='images/help.png' title='Aide'></a></span></h2>
		<div class='flex-center'><div class='panel panel-war' style='width:250px;'><div class='panel-heading text-center'>Planificateur stratégique</div><div class='panel-body'>".$Planificateur."</div></div></div>";

		function Board($Land,$Front,$Front_txt,$Cdt='',$TAdj='',$Mer='',$Adj='',$Rens='',$Log='',$Off='',$Army='',$Reserve='',$Pilotes='')
		{
		    if($Front ==0 or ($Land ==8 and ($Front ==1 or $Front ==4)) or (($Land ==9 or $Land ==7) and $Front ==3) or $Land ==6)$in_prop=' in';
			echo "<a data-toggle='collapse' href='#em-".$Front."'><h2>Front ".$Front_txt."<span class='caret'></span></h2></a>
			<div class='panel panel-war collapse".$in_prop."' id='em-".$Front."'>
				<div class='row'>
					<div class='col-lg-12 col-md-12 text-center'>
						<h3>".GetGenStaff($Land,1)."</h3>
						".$Cdt."
					</div>
				</div>
				<div class='row'>
					<div class='col-md-6 text-center'>
						<h3>".GetGenStaff($Land,8)."</h3>".$Mer."
					</div>
					<div class='col-md-6 text-center'>
						<h3>".GetGenStaff($Land,2)."</h3>".$Adj."
					</div>
				</div><hr>
				<div class='row'>
					<div class='col-md-6 text-center'>
						<h3>".GetGenStaff($Land,6)."</h3>".$Log."
					</div>
					<div class='col-md-6 text-center'>
						<h3>".GetGenStaff($Land,3)."</h3>".$Off."
					</div>
				</div>";
			if($Army)
				echo "<h3>Commandants d'armées ou de flottes</h3><div class='row'><div class='col-md-12'><ul class='list-inline'>".$Army."</ul></div></div>";
            if($Reserve)
                echo "<h3>Officiers sans affectation</h3><div class='row'><div class='col-md-12'><ul class='list-inline'>".$Reserve."</ul></div></div>";
            if($Pilotes)
                echo "<h3>Pilotes</h3><div class='row'><div class='col-md-12'><ul class='list-inline'>".$Pilotes."</ul></div></div>";
			echo '</div>';
		}
				/*<th width='8%'><a href='#' class='popup'>".GetGenStaff($Land,9)."<span>Il contrôle les unités EM aériennes de chasse et de chasse lourde</span></a></th>
				<th width='8%'><a href='#' class='popup'>".GetGenStaff($Land,10)."<span>Il contrôle les unités EM aériennes de bombardement</span></a></th>
				<th width='8%'><a href='#' class='popup'>".GetGenStaff($Land,11)."<span>Il contrôle les unités EM aériennes de reconnaissance et de patrouille maritime</span></a></th>
				<th width='8%'><a href='#' class='popup'>".GetGenStaff($Land,12)."<span>Il contrôle les unités EM aériennes d'attaque et les éventuelles unités embarquées</span></a></th>*/
		if($Land ==1 or $Land ==2 or $Land ==3 or $Land ==4 or $Land ==5 or $Land ==6 or $Land ==7)
		{
			Board($country,0,"Ouest",$Cdt0,$TAdj0,$Mer0,$Adj0,$Rens0,$Log0,$Off0,$Army0,$Reserve0,$Pilotes0);
		}
		if($Land ==1 or $Land ==2 or $Land ==7 or $Land ==8 or $Land ==20 or $Land ==35)
		{
			Board($country,5,"Arctique",$Cdt5,$TAdj5,$Mer5,$Adj5,$Rens5,$Log5,$Off5,$Army5,$Reserve5,$Pilotes5);
		}
		if($Land ==1 or $Land ==8 or $Land ==20)
		{
			Board($country,4,"Nord-Est",$Cdt4,$TAdj4,$Mer4,$Adj4,$Rens4,$Log4,$Off4,$Army4,$Reserve4,$Pilotes4);
		}
		if($Land ==1 or $Land ==8 or $Land ==15 or $Land ==18 or $Land ==19)
		{
			Board($country,1,"Sud-Est",$Cdt1,$TAdj1,$Mer1,$Adj1,$Rens1,$Log1,$Off1,$Army1,$Reserve1,$Pilotes1);
		}
		if($Land ==1 or $Land ==2 or $Land ==4 or $Land ==6 or $Land ==7 or $Land ==10 or $Land ==17 or $Land ==24)
		{
			Board($country,2,"Méditerranéen",$Cdt2,$TAdj2,$Mer2,$Adj2,$Rens2,$Log2,$Off2,$Army2,$Reserve2,$Pilotes2);
		}
		if($Land ==2 or $Land ==5 or $Land ==7 or $Land ==9)
		{
			Board($country,3,"Pacifique",$Cdt3,$TAdj3,$Mer3,$Adj3,$Rens3,$Log3,$Off3,$Army3,$Reserve3,$Pilotes3);
		}
        if($Reserve12)
            echo "<h2>Officiers de réserve</h2><div class='row'><div class='col-md-12'><ul class='list-inline'>".$Reserve12."</ul></div></div>";

    }
	else
		echo "<h1>MIA</h1><img src='images/unites".$Land.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
{
	include_once('./menu_actus.php');
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
