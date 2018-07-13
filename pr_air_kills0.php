<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE Actif=0 ORDER BY Nom ASC";
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
	<form action="index.php?view=pr_air_kills" method="post">
	<input type='hidden' name='Off' value='<?echo $PlayerID;?>'>
	Officier contre lequel vous désirez comparer vos exploits
	<select name='Off_eni' class='form-control' style='width: 300px'>
		<?echo $pilotes;?>
	</select>
	<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>