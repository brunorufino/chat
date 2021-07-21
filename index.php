<?php
session_start ();
function loginForm() {
    echo '
   <div id="loginform">
   <style>img {display:none;}html,body{height:100%; overflow: hidden;}</style>
   <form action="index.php" method="post">
       <p>Insira seu nick:</p>
	   <br><br>
       <label for="name">Nome:</label>
       <input style="border: 1px solid #474747;" type="text" autofocus="" name="name" id="name" />
       <input type="submit" name="enter" id="enter" value="Entrar" />
   </form>
   </div>
   ';
}

if (isset ($_POST ['enter'])) {
	if (strlen($_POST ['name']) > 15) {
		echo "<span class='error'>O nome não pode ser maior que 15 caracteres </span>";
	}
	elseif (strlen($_POST ['name']) < 1) {
		echo "<span class='error'>Insira um nome</span>";
	}
	elseif (ctype_space($_POST ['name'])) {
		echo "<span class='error' id='error'>Insira um nome</span>";
	}
    elseif ($_POST ['name'] == "GH057") {
        $_SESSION ["name"] = stripslashes (htmlspecialchars($_POST ["name"]));
        $fp = fopen ("log.html", "a");
        fclose ($fp);
    }
    else {
        $_SESSION ["name"] = stripslashes (htmlspecialchars($_POST ["name"]));
        $fp = fopen ( "log.html", "a" );
        // Entrou no chat;
        fclose ($fp);
    }
}

if (isset ($_GET ["logout"])) {
    if ($_SESSION ['name'] == "GH057") {
        session_destroy ();
        header ("Location: index.php");
    }
    else {
        $fp = fopen ("log.html", "a");
        // Saiu do chat ;
        fclose ($fp);
        session_destroy ();
        header ("Location: index.php"); //refresh the page and destroy the session
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<link id="css" rel="stylesheet" type="text/css" href="dark.css">
<title>Chat</title>
<link rel="shortcut icon" type="image/png" href="favicon.png"/>
</head>
<body>
    <?php
    if (! isset ($_SESSION ['name'])) {
        loginForm ();
    } else {
        ?>
<div id="wrapper">
        <div id="menu">
            <p class="welcome">Olá, <b><?php echo $_SESSION['name']; ?></b></p>
            <p class="logout"><a id="exit" href="#">Sair</a></p>
            <div style="clear: both"></div>
        </div>
        <div id="chatbox"><?php
        if (file_exists ("log.html") && filesize ("log.html") > 0) {
            $handle = fopen ("log.html", "r");
            $contents = fread ($handle, filesize ("log.html"));
            fclose ($handle);
           
            echo $contents;
        }
        ?></div>
        <?php
        if ($_POST ["name"] != "GH057") { /* Se o login digitado estiver assim não podera enviar mensagem */
            echo '
            <form name="message" action="">
                <input name="usermsg" autofocus="" spellcheck="true" type="text" id="usermsg" size="63"/> <input name="submitmsg" type="submit" id="submitmsg" value="Send"/>
            </form>';
        }
        else {
            echo ">>> NÃO PODE ENVIAR MENSAGENS ENQUANTO ESTÁ NO MODO ESPECTADOR <<<";
        }
        ?>
    </div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script>
    window.onbeforeunload = function(evt) {
    return true;
}
window.onbeforeunload = function(evt) {
    var message = "Are you sure you want to log out?";
    if (typeof evt == "undefined") {
        evt = window.event.srcElement;
    }
    if (evt) {
        evt.returnValue = message;
    }
}
</script>

<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
    var scrollHeight = $("#chatbox").attr("scrollHeight") - 50;
    var scroll = true;
    if (scroll == true) {
        $("#chatbox").animate({ scrollTop: scrollHeight }, "normal");
        load = false;
    }
});
 
//jQuery Document
$(document).ready(function(){
   // Usuário clica par sair 
    $("#exit").click(function(){ // quando o usuário clica em sair
        var exit = true;
        if(exit==true){window.location = 'index.php?logout=true';}
    });
});

//Se o usuário enviar o formulário
$("#submitmsg").click(function(){
        var clientmsg = $("#usermsg").val();
        $.post("post.php", {text: clientmsg});
        $("#usermsg").attr("value", "");
        loadLog;
    return false;
});

function loadLog(){ //convert from jQuery to JavaScript
    var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 50; //Scroll height before the request
    $.ajax({
        url: "log.html",
        cache: false,
        success: function(html){
            $("#chatbox").html(html); //Insert chat log into the #chatbox div
           
            //Auto-scroll
            var newscrollHeight = $("#chatbox").attr("scrollHeight") - 50; //Scroll height after the request
            if(newscrollHeight > oldscrollHeight){
                $("#chatbox").animate({ scrollTop: newscrollHeight }, "normal"); //Autoscroll to bottom of div
            }
        },
    });
}
 
setInterval (loadLog, 2000);
</script>
<?php
    }
    ?>
    <script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <script type="text/javascript">
</script>
</body>
</html>
