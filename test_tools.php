<?
function LoadClass($classe)
{
  require_once $classe.'_class.php';
}
spl_autoload_register('LoadClass');
LoadClass(dbconnect);
LoadClass(account);
LoadClass(account_manager);

/*$classes=array('dbconnect','account','account_manager');
require_once('./l_load_classes.php');*/
$id=1;

$con=new Dbconnect();
$compte_manager=new Account_Manager($con);
$compte=$compte_manager->get($id);
$id=$compte->id;
$admin=$compte->admin;
$login=$compte->login;
echo "id=".$id." ".$login." ( Admin ? ".$admin.")";

/*class C_avion
{
	protected $Nom='Bf109E-1';
	protected $_ManH=140;
	protected $_ManB=150;
	protected $_HPmax=1500;
	protected $_HP=;
	protected $_Degats=200;
	protected $_compteur;
	
	const FLAPS_AUCUN=0;
	const FLAPS_BASIC=1;
	const FLAPS_DOUBLE=2;
	const FLAPS_DIVE=3;

	public function __construct($HP)
	{
		//dbbconnect
		self::$_compteur++;
		$this->SetHP($HP);
	}
	
	static public function GetInstances()
	{
		self::$_compteur++;
	}
  
	public function Nom() //getter
	{
		return $this->Nom;
	}
	
	public function SetHP($HP) //setter
	{
		if(!is_int($HP))
		{
			trigger_error('La force d\'un personnage doit être un nombre entier', E_USER_WARNING);
			return;
		}		
		if($HP >5000)
		{
			trigger_error('La force d\'un personnage ne peut dépasser 100', E_USER_WARNING);
			return;
		}		
		$this->_HP=$HP;
	}
	
	public function SetFlaps($Flaps)
	{
		if(in_array($Flaps,[self::FLAPS_AUCUN,self::FLAPS_BASIC,self::FLAPS_DOUBLE,self::FLAPS_AUTO,self::FLAPS_DIVE]))
	}
  
	public function Maniab($moda=1,$malus_incident=1,$flaps=0)
	{
		if($this->_HP <1)
			$Mani=1;
		else
		{
			if($moda ==1 and $this->_HP <9999)
			{
				if($this->_HPmax >$this->_HP)
					$moda=$this->_HPmax/$this->_HP;
			}
			elseif(!$moda)
				$moda=1;
			$Mani=($Maniabilite-($flaps*10))/$moda*$malus_incident;
		}
		return $Mani;
	}
	
	public function Duel(C_avion $Avioneni)
	{
		$Avioneni->_HP -= $this->_Degats;
	}
}
$oAvion=new C_avion();
$oAvionEni=new C_avion();
$oAvion->Nom();


/*require_once('Membre.class.php');

class Chasseur extends Avion
{

}*/
?>