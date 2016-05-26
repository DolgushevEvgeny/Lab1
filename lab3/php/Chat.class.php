<?php

class Chat{
	
	public static function login($login, $pass){
		if(!$login || !$pass){
			throw new Exception('Заполните все необходимые поля.');
		}

		$row = DataBase::getUser($login);

		if ($row == null) {
			throw new Exception('Пользователя не существует.');
		}
		
		if ($row['password'] != $pass) {
			throw new Exception('Неверный пароль.');
		}
		
		$_SESSION['user']	= array(
			'login'		=> $login,
			'pass'		=> $pass
		);
		
		return array(
			'status'	=> 1,
			'login'		=> $login,
			'pass'		=> $pass
		);
	}
	
	public static function submitChat($chatText){
		if(!$_SESSION['user']){
			throw new Exception('Вы вышли из чата');
		}
		
		if(!$chatText){
			throw new Exception('Вы не ввели сообщение.');
		}

		$insertID = DataBase::saveMessage($_SESSION['user']['login'], $chatText)->insert_id;
	
		return array(
			'status'	=> 1,
			'insertID'	=> $insertID,
			'login' 	=> $_SESSION['user']['login']
		);
	}
	
	public static function getChats($lastID){
		$lastID = (int)$lastID;
		$chats = DataBase::getChats($lastID);
		return array('chats' => $chats);
	}
	
	public static function sessExists() {
		if($_SESSION['user']){
			return array('login' => $_SESSION['user']['login']);
		}
		else {
			throw new Exception('Вы вышли из чата'); 
		}
	}
	
	public static function getUsers(){
		$users = DataBase::getAllUsers();
		
		return array( 'users' => $users);
	}
}
?>