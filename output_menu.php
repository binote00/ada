<?
include_once('./menu_classement.php');
include_once('./jfv_include.inc.php');
$messagesParPage=50;
$con=dbconnecti();
$query=mysqli_query($con,'SELECT COUNT(*) AS total FROM Chasse WHERE ID>0');
$data=mysqli_fetch_assoc($query);
$total=$data['total'];
$nombreDePages=ceil($total/$messagesParPage);			
if(isset($_GET['page']))
{
	 $pageActuelle=intval($_GET['page']);	 
	 if($pageActuelle>$nombreDePages) 
		  $pageActuelle=$nombreDePages;
}
else 
	 $pageActuelle=1;  
$premiereEntree=($pageActuelle-1)*$messagesParPage;
$query2=mysqli_query($con,'SELECT * FROM Chasse ORDER BY ID DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'');
mysqli_close($con);
while($data2=mysqli_fetch_assoc($query2))
{
	 ?>
			<table><tr>
				<td><? echo $data2['Date'];?></td>
				<td><? echo $data2['Pilote_win'];?></td>
				<td><? echo $data2['Avion_win'];?></td>
				<td><? echo $data2['Unite_win'];?></td>
				<td><img src='<? echo $data2['Pays_win'];?>20.gif'></td>
				<td><? echo $data2['Avion_loss'];?></td>
				<td><? echo $data2['Pilote_loss'];?></td>
				<td><? echo $data2['Unite_loss'];?></td>
				<td><img src='<? echo $data2['Pays_loss'];?>20.gif'></td>
			</tr>
			</table><br><br> 
	<?
}
echo '<p align="center">Page : ';
for($i=1;$i<=$nombreDePages;$i++)
{
	 if($i==$pageActuelle)
		 echo ' [ '.$i.' ] '; 
	 else
		  echo ' <a href="output_menu.php?page='.$i.'">'.$i.'</a> ';
}
echo '</p>';
?>