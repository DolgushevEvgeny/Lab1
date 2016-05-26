$(document).ready(function(){
	
	chat.init();
	
});

var chat = {
	
	lastID 		: 0,
	
	init : function(){
		
		var working = false;
				
		$('#mainForm').submit(function(){
			
			if (working) return false;
			working = true;

			var userData = $("#loginName").val();
			var passwordData = $("#passwordText").val();

			$.ajax({
				type: 'POST',
				url: 'php/waiter.php?action=login',
				data: {login:userData, password:passwordData},
				dataType: 'json',
				success: function(){
					working = false;

					$("#loginName").val('');
					$("#passwordText").val('');
				}
			});

			return false;
		});
		
		$('#sendForm').submit(function(){

			var text = $('#sendingMessage').val();
			
			if (text.length == 0) {
				return false;
			}
			
			if (working) { 
				return false 
			};

			working = true;

			$.ajax({
				type: 'POST',
				url: 'php/waiter.php?action=submitChat',
				data: {mess_to_send:text},
				dataType: 'json',
				success: function(){
					working = false;

					$("#sendingMessage").val('');
				}
			});

			return false;
		});

		chat.getUsers();
		
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();
	},

	addChatLine : function(params) {
		$("#messages").append("<b><font color='black'>" + params['name'] + "</b>:&nbsp;</font></b>" + params['text'] + "<br>");
        $("#messages").scrollTop(2000);
	},
	
	getChats : function(callback) {
		$.ajax({
			type: 'GET',
			url: 'php/waiter.php?action=getChats',
			data: {lastID: chat.lastID},
			dataType: 'json',
			success: function(result) {
				for (var i = 0; i < result.chats.length; i++) {
					chat.addChatLine(result.chats[i]);
				}

				if (result.chats.length) {
					chat.lastID = result.chats[i - 1].id;
				}

				setTimeout(callback, 5000);
			},
			error: function() {
				console.log("error: can't get chat");
			}
		});
	},
	
	// Запрос списка всех пользователей.
	getUsers : function(){
		$.ajax({
			type: 'GET',
			url: 'php/waiter.php?action=getUsers',
			dataType: 'json',
			success: function(result){
				var users = [];

				for (var i=0; i < result.users.length; i++) {
					if (result.users[i]) {
						$("#user_name").append("<b><font color='black'>" + result.users[i].name + "</font></b><br>");
					}
				}
			}
		});
	}
};