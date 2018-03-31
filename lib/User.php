<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 31-10-17
 * Time: 05:39
 */

class User
{
    private $id;
    private $login;
    private $mdp;
    private $adresse;
    protected $ip;
    protected $langue;
    protected $premium;
    protected $premium_date;
    protected $con_date;
    protected $chat_date;
    private $pays;
    private $front;
    private $engagement;
    protected $actif;
    private $pilote_id;
    private $officier_em;
    private $officier_bonus;
    private $courier;
    private $cadeau;
    private $parrain;
    protected $anim;
    private $encodage;
    protected $admin;
    protected $beta;
    protected $ban;

    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $login
     */
    public function userConnect($login)
    {
        $result = DBManager::getData('joueur',['ID','Mdp','IP','Langue','Premium','Premium_date','Con_date','Chat_date','Pays','Engagement','Actif','Pilote_id','Officier_em','Officier_bonus','Cadeau','Parrain','Anim','Encodage','Admin','Beta','Ban'],'login',$login);
        $data = $result->fetchObject();
        $this->setId($data->ID);
        $this->setMdp($data->Mdp);
        $this->setIp($data->IP);
        $this->setLangue($data->Langue);
        $this->setPremium($data->Premium);
        $this->setPremiumDate($data->Premium_date);
        $this->setConDate($data->Con_date);
        $this->setChatDate($data->Chat_date);
        $this->setPays($data->Pays);
        $this->setEngagement($data->Engagement);
        $this->setActif($data->Actif);
        $this->setAdmin($data->Admin);
        $this->setPiloteId($data->Pilote_id);
        $this->setOfficierEm($data->Officier_em);
        $this->setOfficierBonus($data->Officier_bonus);
        $this->setAnim($data->Anim);
        $this->setBeta($data->Beta);
        $this->setBan($data->Ban);
    }

    public function userCheckIn()
    {
        DBManager::setData('joueur',['IP','Con_date'],[$this->ip, $this->con_date],'id',$this->id);
        if($this->getPremium() and $this->getPremiumDate() < date("Y-m-d")){
            DBManager::setData('joueur','Premium','0','id',$this->id);
        }
        elseif(!$this->getCadeau() and !$this->getPremium()){
            DBManager::setData('joueur',['Cadeau','Premium','Premium_date'],['1','1','DATE_ADD(CURDATE(),INTERVAL 7 DAY)'],'id',$this->id);
            Output::Alert('Le jeu offre à tous ses nouveaux joueurs 1 semaine de Premium!');
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * @param mixed $langue
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;
    }

    /**
     * @return mixed
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * @param mixed $premium
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;
    }

    /**
     * @return mixed
     */
    public function getPremiumDate()
    {
        return $this->premium_date;
    }

    /**
     * @param mixed $premium_date
     */
    public function setPremiumDate($premium_date)
    {
        $this->premium_date = $premium_date;
    }

    /**
     * @return mixed
     */
    public function getConDate()
    {
        return $this->con_date;
    }

    /**
     * @param mixed $con_date
     */
    public function setConDate($con_date)
    {
        $this->con_date = $con_date;
    }

    /**
     * @return mixed
     */
    public function getChatDate()
    {
        return $this->chat_date;
    }

    /**
     * @param mixed $chat_date
     */
    public function setChatDate($chat_date)
    {
        $this->chat_date = $chat_date;
    }

    /**
     * @return mixed
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return mixed
     */
    public function getFront()
    {
        return $this->front;
    }

    /**
     * @param mixed $front
     */
    public function setFront($front)
    {
        $this->front = $front;
    }

    /**
     * @return mixed
     */
    public function getEngagement()
    {
        return $this->engagement;
    }

    /**
     * @param mixed $engagement
     */
    public function setEngagement($engagement)
    {
        $this->engagement = $engagement;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }

    /**
     * @return mixed
     */
    public function getPiloteId()
    {
        return $this->pilote_id;
    }

    /**
     * @param mixed $pilote_id
     */
    public function setPiloteId($pilote_id)
    {
        $this->pilote_id = $pilote_id;
    }

    /**
     * @return mixed
     */
    public function getOfficierEm()
    {
        return $this->officier_em;
    }

    /**
     * @param mixed $officier_em
     */
    public function setOfficierEm($officier_em)
    {
        $this->officier_em = $officier_em;
    }

    /**
     * @return mixed
     */
    public function getOfficierBonus()
    {
        return $this->officier_bonus;
    }

    /**
     * @param mixed $officier_bonus
     */
    public function setOfficierBonus($officier_bonus)
    {
        $this->officier_bonus = $officier_bonus;
    }

    /**
     * @return mixed
     */
    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * @param mixed $courier
     */
    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    /**
     * @return mixed
     */
    public function getCadeau()
    {
        return $this->cadeau;
    }

    /**
     * @param mixed $cadeau
     */
    public function setCadeau($cadeau)
    {
        $this->cadeau = $cadeau;
    }

    /**
     * @return mixed
     */
    public function getParrain()
    {
        return $this->parrain;
    }

    /**
     * @param mixed $parrain
     */
    public function setParrain($parrain)
    {
        $this->parrain = $parrain;
    }

    /**
     * @return mixed
     */
    public function getAnim()
    {
        return $this->anim;
    }

    /**
     * @param mixed $anim
     */
    public function setAnim($anim)
    {
        $this->anim = $anim;
    }

    /**
     * @return mixed
     */
    public function getEncodage()
    {
        return $this->encodage;
    }

    /**
     * @param mixed $encodage
     */
    public function setEncodage($encodage)
    {
        $this->encodage = $encodage;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getBeta()
    {
        return $this->beta;
    }

    /**
     * @param mixed $beta
     */
    public function setBeta($beta)
    {
        $this->beta = $beta;
    }

    /**
     * @return mixed
     */
    public function getBan()
    {
        return $this->ban;
    }

    /**
     * @param mixed $ban
     */
    public function setBan($ban)
    {
        $this->ban = $ban;
    }
}