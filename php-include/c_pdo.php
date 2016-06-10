<?php
class mypdo {
	private static $_instance = array();
	private $dbh;
	private $check;
	private $host;
	private $user;
	private $pass;
	private $name;
	private function __construct($sHost,$sUser,$sPass,$sBase) {
		$this->host = $sHost;
		$this->user = $sUser;
		$this->pass = $sPass;
		$this->name = $sBase;
		$this->check = false;
	}
	private function connect() {
		if($this->dbh == null) {
			try {
				$idString ='mysql:host='.$this->host.';dbname='.$this->name;
				$this->dbh = new PDO($idString, $this->user, $this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				$this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
				$this->check = true;
			}
			catch(Exception $e) {
				$this->check = false;
			}
		}
		return true;
	}
	public static function GetInstance($sHost,$sUser,$sPass,$sBase) {
		$sIdConnexion = $sHost.':'.$sBase;
		if(!isset(self::$_instance[$sIdConnexion])) {
			self::$_instance[$sIdConnexion] = new mypdo($sHost,$sUser,$sPass,$sBase);
		}
		return self::$_instance[$sIdConnexion];
	}
	public function GetLine($rR,$aArg = array()) {
		$this->connect();
		$oPDOStatement = $this->dbh->prepare($rR);
		if(is_object($oPDOStatement)) {
			if(!$oPDOStatement->execute($aArg)) {
				$sArg = print_r($aArg,true);
				error_log('REQUETE SQL : '.$rR.' => '.$sArg.' => '.$_SERVER['PHP_SELF']);
				echo $rR;
			}
			$result = $oPDOStatement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
	}
	public function GetAll($rR,$aArg = array()) {
		$this->connect();
		$oPDOStatement = $this->dbh->prepare($rR);
		if(is_object($oPDOStatement)) {
			if(!$oPDOStatement->execute($aArg)) {
				echo $rR;
				print_r($aArg);
			}
			$result = $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
	}
	public function Query($rR,$aArg = array()) {
		$this->connect();
		$oPDOStatement = $this->dbh->prepare($rR);
		if(is_object($oPDOStatement)) {
			if(!$oPDOStatement->execute($aArg)) {
				echo $rR;
				print_r($aArg);
			}
		}
	}
	public function QueryIns($rR,$aArg = array()) {
		$this->connect();
		$oPDOStatement = $this->dbh->prepare($rR);
		if(is_object($oPDOStatement)){
			if(!$oPDOStatement->execute($aArg)) {
				echo $rR;
				print_r($aArg);
			}
			else
				return $this->dbh->lastInsertId();
		}
	}
	public function CheckLogin() { $this->connect(); return $this->check; }
	public function __destruct() { $this->dbh = NULL; }
}