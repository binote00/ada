<?php
class dbconnect extends PDO
{
	protected $_db;
	
	public function __construct($db='')
	{
		$this->_db='aubedesaiglesnet'.$db;
		try
		{
			parent::__construct("mysql:host=myd5-30.infomaniak.ch;dbname=".$this->_db.";charset=latin1",'player','NFLmtq8b96');
		}
		catch(PDOException $ex)
		{
			echo 'ECHEC DE CONNEXION A LA BASE DE DONNEE!<br>';
			//.print_r($e);
			//die 'ERREUR: '.$e->errorInfo;
		}
	}
}
?>