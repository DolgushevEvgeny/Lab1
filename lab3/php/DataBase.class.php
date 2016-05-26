<?php

class DataBase {
	private static $db = null; // Единственный экземпляр класса, чтобы не создавать множество подключений
	private $MySQLi; // Идентификатор соединения

    const DB_HOST = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "notebook";
	const DB_NAME = "Chat";

	public static function getDB() {
		if (self::$db == null) self::$db = new DataBase();
		return self::$db;
	}

	private function __construct() {
		$this->MySQLi = new mysqli( self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
		if (mysqli_connect_errno()) {
			throw new Exception('Ошибка базы данных.');
		}
		
		$this->MySQLi->set_charset("utf8");
	}
	
	private static function query($q){
		return self::$db->MySQLi->query($q);
	}
	
	public static function selectRow($q) {
		$result_set = self::$db->MySQLi->query($q);
		return $result_set->fetch_assoc();
	}
	
	public static function select($q) {
		$result_set = self::$db->MySQLi->query($q);
		if (!$result_set) return false;
		return self::resultSetToArray($result_set);
	}
	 
	private static function resultSetToArray($result_set) {
		$array = array();
		while (($row = $result_set->fetch_assoc()) != false) {
		  $array[] = $row;
		}
		return $array;
	 }
	
	private static function getMySQLiObject(){
		return self::$db->MySQLi;
	}
	
	private static function esc($str){
		return self::$db->MySQLi->real_escape_string(htmlspecialchars($str));
	}

	public function __destruct() {
		if ($this->MySQLi) $this->MySQLi->close();
	}

	public static function saveMessage($login, $message) {
		$result = self::$db->MySQLi->query("
			INSERT INTO `message` (`name`,`text`)
			VALUES (
				'".DataBase::esc($login)."',
				'".DataBase::esc($message)."'
			)
		");

		return self::getMySQLiObject();
	}

	public static function getUser($login) {
		return self::selectRow("SELECT * FROM `users` WHERE `name`='".DataBase::esc($login)."' ");
	}

	public static function getChats($lastID) {
		return self::select('SELECT * FROM message WHERE id > '.$lastID.' ORDER BY id ASC');
	}

	public static function getAllUsers() {
		return self::select('SELECT `name` FROM `users`');
	}
}
?>