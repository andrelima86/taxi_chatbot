<!doctype html>
<html>
<head>
<title> Test Chatbot </title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src='main.js'></script>
</head>

<body>

	<div id = 'wrapper'>
		
		<div id = 'main_chat_area'>
			<?php
				if(file_exists("chathistory.html") && filesize("chathistory.html") > 0){
				    $handle = fopen("chathistory.html", "r");
				    $contents = fread($handle, filesize("chathistory.html"));
				    fclose($handle);
				     
				    echo $contents;
				}
			?>	
		</div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
		<script type="text/javascript">
		// jQuery Document
		$(document).ready(function(){
		 
		});
		<script type="text/javascript">
		setInterval (loadLog("chathistory.html"), 2500);
		</script>
		<form name="message" action="index.php" method="POST">
        	<input name="user_msg" type="text" id="user_msg" size="63" />
        	<input name="submit_msg" type="submit"  id="submit_msg" value="Send" />
    	</form>

	</div>
	
	<footer>
	</footer>

</body>
</html>

<?php
$url = 'http://localhost:8888/chatbot/test/test.php';
function logger($text, $user)
{
	$fp = fopen("chathistory.html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$user."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}

// check if message sent 
if(!isset($_POST['user_msg']) || empty($_POST['user_msg']))
	exit();

// log user message
logger($_POST['user_msg'], 'Tester');

// chatbot response
$postdata = http_build_query(
    array(
        'user_msg' => $_POST['user_msg']
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$chatbot_response = file_get_contents($url, false, $context);
 
	
// log chatbot response
logger($chatbot_response, 'Chatbot');


?>