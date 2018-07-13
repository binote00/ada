<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$country=$_SESSION['country'];
$Lieu=Insec($_GET['lieu']);
if($PlayerID >0)
{
	include_once('./jfv_map.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$Lieu=mysqli_real_escape_string($con,$Lieu);
	$result=mysqli_query($con,"SELECT * FROM Lieu WHERE ID='$Lieu'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$NoeudR_txt="";
			$NoeudF_txt="";
			$Industrie_txt="";
			$Pont_txt="";
			$Port_txt="";
			$Icone_txt="";
			$Base_txt="";
			$Info_long=105; 
			$Pays=GetNation($data['Pays']);
			if($PlayerID >0 and ($data['Recce'] or $data['Flag'] ==$country))
				$adminl=true;
			else
				$adminl=false;
			$last_attack="<br><br> Attaqué le ".$data['Last_Attack'];
			if($last_attack == "<br><br> Attaqué le 0000-00-00")
			{
				$last_attack="";
				$Info_long-=40;
			}
			if($data['BaseAerienne'] and $adminl)
			{
				if($data['BaseAerienne'] ==3)
					$Base_txt="<br> Aérodrome";
				elseif($data['BaseAerienne'] ==2)
					$Base_txt="<br> Aérodrome avec un bassin pour hydravions";
				else
					$Base_txt="<br> Aérodrome avec piste en dur";
				$Base_txt.=' ('.$data['LongPiste'].'m)';
			}
			if($data['NoeudR'])
				$NoeudR_txt="<br> Noeud routier";
			if($data['NoeudF_Ori'])
				$NoeudF_txt="<br> Noeud ferroviaire";
			if($data['Pont_Ori'])
				$Pont_txt="<br> Pont Stratégique";
			if($data['Industrie'] and $adminl)
				$Industrie_txt="<br> Zone industrielle";
			if($data['Oil'] and $adminl)
				$Industrie_txt="<br> Raffinerie";
			if($data['Port_Ori'])
				$Port_txt="<br> Infrastructures portuaires";
			if($adminl)
			{
				$Zone=$data['Zone'];
				$Icone_txt="<img src=\'images/zone".$Zone.".jpg\' title=\'".GetZone($Zone)."\'>";
			}
			if($adminl and $data['ValeurStrat'] >0)
				$ValStrat="<img src=\'images/strat".$data['ValeurStrat'].".png\'>";
			else
				$ValStrat="";
			$Info_txt=$ValStrat." <b>".$data['Nom']."</b> ".$Icone_txt."<br>(".$Pays.")<br>".$Port_txt.$Industrie_txt.$NoeudR_txt.$NoeudF_txt.$Pont_txt.$Base_txt.$last_attack;
			//Unites
			if($PlayerID ==1)
			{
				$Info_txt=$data['ID'].$Info_txt;
				$query_unit="SELECT ID,Nom,Type,Pays,Avion1,Avion2,Avion3 FROM Unit WHERE Base='$Lieu' AND Etat='1' ORDER BY Type DESC";
			}
			else
				$query_unit="SELECT ID,Nom,Type,Pays,Avion1,Avion2,Avion3 FROM Unit WHERE Base='$Lieu' AND Pays='$country' AND Etat='1' ORDER BY Type DESC";
			$con=dbconnecti();
			$result_unit=mysqli_query($con,$query_unit);
			mysqli_close($con);
			if($result_unit)
			{
				while($data_unit=mysqli_fetch_array($result_unit,MYSQLI_ASSOC))
				{
					$esc_nbr+=1;				
					$Avion1_Nom=GetData("Avion","ID",$data_unit['Avion1'],"Nom");
					$Avion2_Nom=GetData("Avion","ID",$data_unit['Avion2'],"Nom");
					$Avion3_Nom=GetData("Avion","ID",$data_unit['Avion3'],"Nom");
					if($PlayerID ==1)
						$avion_img='<p><b>'.$data_unit['Nom'].'</b> ('.$data_unit['ID'].')<br>'.GetAvionIcon($data_unit['Avion1'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion1_Nom.'<br>'.GetAvionIcon($data_unit['Avion2'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion2_Nom.'<br>'.GetAvionIcon($data_unit['Avion3'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion3_Nom.'</p>';
					else
						$avion_img='<p><b>'.$data_unit['Nom'].'</b><br>'.GetAvionIcon($data_unit['Avion1'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion1_Nom.'<br>'.GetAvionIcon($data_unit['Avion2'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion2_Nom.'<br>'.GetAvionIcon($data_unit['Avion3'],$data_unit['Pays'],0,$data_unit['ID'],2).'<br>'.$Avion3_Nom.'</p';
					$icone=$data_unit['Pays'].$data_unit['Type'];		
					$bulle_txt_global.=$avion_img;	
				}
				mysqli_free_result($result_unit);
				unset($data_unit);
			}
			echo "<div>".$Info_txt."<hr>".$bulle_txt_global."</div>";
		}
	}
}
?>