<?php
session_start();

/* Verifica se a sessão existe */
if(isset($_SESSION['name'])){

        /* Verifica se o usuário digitou alguma coisa */
    if ($_POST ['text'] == "") {
        #prevents user from sending a blank message
    }
	else {
        $text = $_POST['text'];
        $fp = fopen("log.html", 'a');
        fwrite($fp, "<div class='msgln'><span>(".date("g:i A").") <b><user>".$_SESSION['name']."</user></b>: ".stripslashes (htmlspecialchars($text))."<br></span></div>");
        fclose($fp);
    }
}
?>
