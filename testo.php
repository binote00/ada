<?
function LoadClass($classe)
{
  require_once $classe.'_class.php';
}
spl_autoload_register('LoadClass');
LoadClass(dbconnect);

//$con=new PDO('mysql:host=myd5-30.infomaniak.ch;dbname=aubedesaiglesnet;charset=latin1','player','NFLmtq8b96');
/*$con= new dbconnecti;
$con->connect;
$result=$con->query('SELECT Nom FROM Joueur WHERE ID=1');
while($data=$result->fetch(PDO::FETCH_ASSOC))
{
	echo $data['Nom'];
}*/
$id=100;

/*$result=$con->prepare("SELECT ID,Nom FROM Joueur WHERE ID <:id");
$con=null;*/
$con=new dbconnect();
//$con->connect();
$stm=$con->prepare("SELECT ID,Nom FROM Joueur WHERE ID <=:id");
$stm->bindValue(':id',$id,PDO::PARAM_INT);
$stm->execute();
$data=$stm->fetchAll(PDO::FETCH_ASSOC);
$stm->closeCursor();
unset($stm);
unset($con);
foreach($data as $row => $infos) 
{
  echo  $infos['ID'].' '.$infos['Nom'].'<br>';
}
?>