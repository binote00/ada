<?
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'])
{
	include_once('./jfv_include.inc.php');
	$con=dbconnecti();
	$medals=mysqli_query($con,"SELECT m.PlayerID,m.Medal,p.Nom,p.Avancement,p.Pays FROM Pil_medals as m,Pilote as p WHERE m.PlayerID=p.ID ORDER BY p.Avancement DESC");
	mysqli_close($con);
	if($medals)
	{
		while($datam=mysqli_fetch_array($medals,MYSQLI_ASSOC))
		{
			if($Nom !=$datam['Nom'])
			{
				$Grade=GetAvancement($datam['Avancement'],$datam['Pays']);
				$pil_txt="<td>".$datam['Nom']."</td><td><img title='".$Grade[0]."' src='images/grades/grades".$datam['Pays'].$Grade[1].".png'></td><td><img src='images/".$datam['Pays']."20.gif'></td><td>";
			}
			$Nom=$datam['Nom'];
			$medal_txt[$datam['PlayerID']].="<img src='images/mmedal".$datam['Pays'].$datam['Medal'].".gif'>";
			$final_txt[$datam['PlayerID']]="<tr>".$pil_txt.$medal_txt[$datam['PlayerID']]."</td></tr>";
		}
		mysqli_free_result($medals);
	}
	foreach($final_txt as $value)
	{
		$txt.=$value;
	}
	echo "<h1>Pilotes décorés</h1>";
	echo "<table class='table table-striped'>	
		<thead><tr>
			<th>Pilote</th>
			<th>Grade</th>
			<th>Pays</th>
			<th colspan='20'>Décorations</th>
		</tr></thead>".$txt."</table>";
}
/*echo "<h1>Pilotes décorés</h1>
<div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>N°</th>
		<th>Pilote</th>
		<th>Unité</th>
		<th>Grade</th>
		<th>Pays</th>
		<th colspan='20'>Décorations</th>
	</tr></thead>";*/
/*include_once('./jfv_txt.inc.php');
$query="SELECT * FROM Pilote WHERE Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() AND medal1=1 AND Actif=0 ORDER BY (medal9+ medal8+ medal7+ medal6+ medal5+ medal4+ medal3+ medal2+ medal1+ medal0) DESC, Reputation DESC LIMIT 100";
//ID,Nom,Pays,Unit,Avancement,medal0,medal1,medal2,medal3,medal4,medal5,medal6,medal7,medal8,medal9,medal10,medal11,medal12,medall3,medal14,medal15,medal16,medal17,medal18 
$con=dbconnecti();
$result=mysqli_query($con,$query);
mysqli_close($con);
if($result)
{
	$num=mysqli_num_rows($result);
	if($num ==0)
		echo "<h6>Désolé, aucune médaille n'a encore été attribuée dans cette campagne</h6>";
	else
	{
		$i=0;
		while($i <$num) 
		{

			$ID=mysqli_result($result,$i,"ID");
			$Pilote=mysqli_result($result,$i,"Nom");
			$Pays=mysqli_result($result,$i,"Pays");
			$Unit=mysqli_result($result,$i,"Unit");
			$Avancement=mysqli_result($result,$i,"Avancement");
			$Medal0=mysqli_result($result,$i,"medal0");
			$Medal1=mysqli_result($result,$i,"medal1");
			$Medal2=mysqli_result($result,$i,"medal2");
			$Medal3=mysqli_result($result,$i,"medal3");
			$Medal4=mysqli_result($result,$i,"medal4");
			$Medal5=mysqli_result($result,$i,"medal5");
			$Medal6=mysqli_result($result,$i,"medal6");
			$Medal7=mysqli_result($result,$i,"medal7");
			$Medal8=mysqli_result($result,$i,"medal8");
			$Medal9=mysqli_result($result,$i,"medal9");
			$Medal10=mysqli_result($result,$i,"medal10");
			$Medal11=mysqli_result($result,$i,"medal11");
			$Medal12=mysqli_result($result,$i,"medal12");
			$Medal13=mysqli_result($result,$i,"medal13");
			$Medal14=mysqli_result($result,$i,"medal14");
			$Medal15=mysqli_result($result,$i,"medal15");
			$Medal16=mysqli_result($result,$i,"medal16");
			$Medal17=mysqli_result($result,$i,"medal17");
			$Medal18=mysqli_result($result,$i,"medal18");
			$Unite=GetData("Unit","ID",$Unit,"Nom");
			$Grade=GetAvancement($Avancement,$Pays);
			$Avion_unit_img=Afficher_Icone($Unit,$Pays,$Unite);
?>
				<td><? echo $i+1;?></td>
				<td><a href="user_public.php?Pilote=<?echo $ID;?>" target="_blank" class='lien'><? echo $Pilote;?></a></td>
				<td><? echo $Avion_unit_img;?></td>
				<td><img title="<?echo $Grade[0];?>" src="images/grades/grades<? echo $Pays.$Grade[1]; ?>.png"></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<?if($Medal0){?>
				<td><img src='images/mmedal<? echo $Pays."0.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal1){?>
				<td><img src='images/mmedal<? echo $Pays."1.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal2){?>
				<td><img src='images/mmedal<? echo $Pays."2.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal3){?>
				<td><img src='images/mmedal<? echo $Pays."3.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal4){?>
				<td><img src='images/mmedal<? echo $Pays."4.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal5){?>
				<td><img src='images/mmedal<? echo $Pays."5.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal6){?>
				<td><img src='images/mmedal<? echo $Pays."6.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal7){?>
				<td><img src='images/mmedal<? echo $Pays."7.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal8){?>
				<td><img src='images/mmedal<? echo $Pays."8.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal9){?>
				<td><img src='images/mmedal<? echo $Pays."9.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal10){?>
				<td><img src='images/mmedal<? echo $Pays."10.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal11){?>
				<td><img src='images/mmedal<? echo $Pays."11.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal12){?>
				<td><img src='images/mmedal<? echo $Pays."12.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal13){?>
				<td><img src='images/mmedal<? echo $Pays."13.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal14){?>
				<td><img src='images/mmedal<? echo $Pays."14.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal15){?>
				<td><img src='images/mmedal<? echo $Pays."15.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal16){?>
				<td><img src='images/mmedal<? echo $Pays."16.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal17){?>
				<td><img src='images/mmedal<? echo $Pays."17.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}if($Medal18){?>
				<td><img src='images/mmedal<? echo $Pays."18.gif";?>'></td>
				<?}else{?>
				<td></td>
				<?}?>
			</tr>
					<?
			$i++;
		}
	}
}
else
	echo "<h6>Désolé, aucune médaille n'a encore été attribuée dans cette campagne</h6>";
echo "</table></div>";
*/?>
