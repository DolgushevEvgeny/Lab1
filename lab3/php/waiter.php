<?php
	error_reporting(E_ALL ^ E_NOTICE);

	require "DataBase.class.php";
	require "Chat.class.php";

	session_name('chat_lab3');
	session_start();

	try{
		$db = DataBase::getDB();

		$response = array();
		
		switch($_GET['action']){
			
			case 'login':
				$response = Chat::login($_POST['login'],$_POST['password']);
			break;
			
			case 'submitChat':
				$response = Chat::submitChat($_POST['mess_to_send']);
			break;
			
			case 'getChats':
				$response = Chat::getChats($_GET['lastID']);
			break;
			
			case 'getUsers':
				$response = Chat::getUsers();
			break;
			
			default:
				throw new Exception('Wrong action');
		}
		echo json_encode($response);
	}

	catch(Exception $e){
		die(json_encode(array('error' => $e->getMessage())));
	}

?>