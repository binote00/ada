<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$query="SELECT DISTINCT ID,Nom FROM Officier WHERE Actif=0 ORDER BY Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
		{
			 $pilotes.="<option value='".$data[0]."'>".$data[1]."</option>";
		}
		mysqli_free_result($result);
	}
?>
<h1>Kikitoutdur</h1>
	<form action="../index.php?view=pr_ground_kills" method="post">
	<input type='hidden' name='Off' value='<?echo $OfficierID;?>'>
	Officier contre lequel vous d�sirez comparer vos exploits
	<select name='Off_eni' class='form-control' style='width: 300px'>
		<?echo $pilotes;?>
	</select>
	<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>