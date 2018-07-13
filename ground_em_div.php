<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($Front !=12 and ($OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Commandant or $Admin or $Armee >0))
	{
		$Cie_nbr=0;
        $today=getdate();
		$Div_id=Insec($_GET['id']);
        if(!$Div_id){
            $Div_id = $_SESSION['div_id'];
            if($_SESSION['msg'])
                $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg'].'</div>';
            $_SESSION['div_id'] = false;
            $_SESSION['msg'] = false;
        }
		if($Div_id)$query_div=" AND r.Division='$Div_id'";
		if($Armee)
		{
			$query_divi.=" AND Armee='$Armee'";
			$query_div.=" AND d.Armee='$Armee'";
		}
        if($Front ==99)
        {
            if($country ==8 or $country ==17 or $country ==18 or $country ==19)
                $Front=1;
            elseif($country ==6 or $country ==10)
                $Front=2;
            elseif($country ==20)
                $Front=5;
            elseif($country ==9)
                $Front=3;
            else
                $Front=0;
        }
		$list_div="<div class='btn btn-primary'><a href='index.php?view=ground_em_div'>".Afficher_Image('images/'.$country.'div.png','',GetPays($country),0)."</a></div>";
		$con=dbconnecti();
		$resultd=mysqli_query($con,"SELECT ID,Nom FROM Division WHERE Pays='$country' AND Active=1 AND Front='$Front'".$query_divi." ORDER BY Maritime ASC,Nom ASC");
		$result2=mysqli_query($con,"SELECT d.Nom,r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,r.Move,r.Division,r.Bataillon,r.Atk,
		r.Atk_time,r.Experience,r.Skill,r.Matos,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,
		r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m
		FROM Regiment_IA as r,Division as d,Lieu as l WHERE r.Division=d.ID AND r.Lieu_ID=l.ID AND r.Pays='$country' AND r.Front='$Front'".$query_div." ORDER BY r.Division ASC,Ville ASC");
		$Service2=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID,r.Pays FROM Regiment_IA as r,Cible as c,Division as d WHERE r.Division=d.ID AND r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0".$query_div." GROUP BY c.ID ORDER BY Nbr DESC");
		mysqli_close($con);
		if($resultd)
		{
			while($data=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
			{
				$list_div.="<div class='btn btn-primary'><a href='index.php?view=ground_em_div&id=".$data['ID']."'>".Afficher_Image('images/div/div'.$data['ID'].'.png','images/'.$country.'div.png',$data['Nom'],0)."</a></div>";
			}
			mysqli_free_result($resultd);			
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Cie_nbr++;
				$Led=false;
				if($data['Move'])
					$Led="<div class='i-flex led_red'></div>";
				else
					$Led="<div class='i-flex led_green'></div>";
				if($today['mday'] >$data['Jour']+1)
					$Combat_flag=false;
                elseif($today['year'] >$data['Year_a'])
                    $Combat_flag=false;
				elseif($today['mon'] >$data['Mois'])
					$Combat_flag=false;
				elseif($today['mday']!=$data['Jour'] and $today['hours']>=$data['Heure'])
					$Combat_flag=false;
				else
					$Combat_flag=true;
				if($today['mday'] >$data['Jour_m']+1)
					$Move_flag=false;
                elseif($today['year'] >$data['Year_m'])
                    $Move_flag=false;
				elseif($today['mon'] >$data['Mois_m'])
					$Move_flag=false;
				elseif($today['mday']!=$data['Jour_m'] and $today['hours']>=$data['Heure_m'])
					$Move_flag=false;
				else
					$Move_flag=true;
				if($data['Position'] ==12)
					$Cie_ID="<span class='label label-danger'>En Vol</span>";
				elseif($data['Atk'] ==1 or $Combat_flag)
					$Cie_ID="<span class='text-danger'>En Combat<br>jusque ".$data['Heure']."</span>";
				elseif($data['mobile'] !=5 and ($data['Move'] ==1 or $Move_flag))
					$Cie_ID="<span class='text-danger'>Mouvement<br>jusque ".$data['Heure_m']."</span>";
				else
					$Cie_ID="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>".$Led."
					<input type='Submit' value='".$data['ID']."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				if($data['Experience'] >249)
					$Exp_txt="<span class='label label-success'>".$data['Experience']."XP</span>";
				elseif($data['Experience'] >49)
					$Exp_txt="<span class='label label-primary'>".$data['Experience']."XP</span>";
				elseif($data['Experience'] >1)
					$Exp_txt="<span class='label label-warning'>".$data['Experience']."XP</span>";
				else
					$Exp_txt="<span class='label label-danger'>".$data['Experience']."XP</span>";
				if($data['Skill'])
					$Skill_txt="<a href='index.php?view=reg_skills'><img src='images/skills/skillo".$data['Skill'].".png' style='width:10%;'></a>";
				else
					$Skill_txt="";
				if($data['Matos'])
					$Skill_txt.="<a href='index.php?view=reg_matos'><img src='/images/skills/skille".$data['Matos'].".png' style='width:10%;'></a>";
				/*if($data['Bataillon'])
					$Bataillon=$data['Bataillon']."e";
				else
					$Bataillon="";*/
                $Bataillon = "<form action='index.php?view=cie_quit_div' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>
                        <input type='Submit' value='Retirer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                $list_cie.="<tr><td>".Afficher_Image('images/div/div'.$data['Division'].'.png','images/'.$country.'div.png',$data['Nom'],0)."</td><td>".$Bataillon."</td><td>".$Cie_ID."</td>
				<td>".$data['Vehicule_Nbr']." ".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front).$Exp_txt.$Skill_txt."</td><td>".$data['Ville']."</td><td>".GetPosGr($data['Position']).' '.GetPlace($data['Placement'])."</td></tr>";
			}
			mysqli_free_result($result2);			
		}
		if($Service2)
		{
			while($data=mysqli_fetch_array($Service2))
			{
				$Vehicules[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service2);
			if(is_array($Vehicules))
			{
				$Total_Veh=array_sum($Vehicules);
				/*foreach($Vehicules as $Veh => $Veh_Nbr)
				{
					$Veh_tot.="<tr><td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($Veh,$country)."<td></tr>";
				}*/
				arsort($Vehicules);
				foreach($Vehicules as $Veh => $Veh_Nbr)
				{
					$Veh_tot2.="<tr><td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($Veh,$country,0,0,$Front)."<td></tr>";
				}
				unset($Vehicules);
			}
			$Total_txt="<div class='row'><div class='col-md-2'><span class='label label-primary'>Total compagnies</span><span class='badge'>".$Cie_nbr."</span></div><div class='col-md-2'><span class='label label-primary'>Total troupes</span><span class='badge'>".$Total_Veh."</span></div></div>";
			$Vehs_list="<table class='table'><thead><tr><th>En service</th><th>Modèle</th></tr></thead>".$Veh_tot2."</table>";
		}
		echo "<h2>Divisions</h2><div class='row'>".$list_div."</div>".$Alert;
		echo "<h2>Compagnies par division</h2>".$Total_txt."<div style='overflow:auto; height: 640px;'><table class='table'><thead><tr>
				<th>Division</th>
				<th></th>
				<th>Compagnie</th>
				<th>Troupes</th>
				<th>Lieu</th>
				<th>Position</th>
			</tr></thead>".$list_cie."</table></div>".$Vehs_list;
		echo "<br><a href='index.php?view=ground_em' class='btn btn-default' title='Retour'>Retour</a>";
	}
}
?>