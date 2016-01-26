<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Web Home Portal - Login</title>
		<link rel="stylesheet" href="css/style.css" media="screen" type="text/css"/>
		<!-- CSS Style base on Marco Biedermann's work here : http://codepen.io/marcobiedermann/pen/Fybpf and modified by me (Thibault Le Cornec) for my need.-->
	</head>
	<body>
<?php
	
$auth_OK = false;

/*##################################################
# ===== Formulaire Soumis - Vérification IDs ===== #
##################################################*/

if(isset($_POST['signin']) && !empty($_POST['signin']))
{		
// 	echo("Signin Soumis");
	// Connexion à la base de données
	require_once('db_vars.php');
    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);   
    
	// Récupération contenu des champs saisi par l'utilisateur
	$form_login = $_POST['form_login'];
	$form_password = $_POST['form_password'];
 	
	// Récupération des infos du user depuis la BDD.
    $user_credentials = mysqli_query($link, "select * from USERS where login = '$form_login'");
	if ($user_credentials)
	{
// 		echo("Credentials Récupérés");
		$user = mysqli_fetch_assoc($user_credentials);
		
		// Récupération des infos de la DB
		$id = $user['id'];
		$login = $user['login'];
		$password = $user['password'];
		$salt = $user['salt'];
		
		// Salage + Cryptage du mot de passe saisi par l'utilisateur dans le formulaire de login
		$salted_password = $salt.$form_password.$salt;
		$encrypted_password = md5($salted_password);
		$string_to_check = $form_login."-".$encrypted_password; // Concaténation login+password
// 	echo($string_to_check);
// 	echo("</br>");
		$IDS = $form_login."-".$password;
// 		echo($IDS);
		if ($string_to_check === $IDS)
		{
			$auth_OK = true;
		}	
	}
	else
	{
		echo("La requête a échouée !");
	}
 }
 
 
 
/*###########################################
# ===== Affichage Formulaire ou Liens ===== #
###########################################*/

if (!$auth_OK)
{
	?>
	<div class="container">
		<div id="login">
			<form action="index.php" method="POST">
				<p>
					<span class="fontawesome-user"></span>
					<input type="text" value="" placeholder="Username" name="form_login" required>
				</p>
				<p>
					<span class="fontawesome-lock"></span>
					<input type="password"  value="" placeholder="Password" name="form_password" required>
				</p>
				<p>
					<input type="submit" value="Sign In" name="signin">
				</p>
			</form>
		</div>
	</div>
	<?
}
else if ($auth_OK)
{
	// Récupération des sections de l'utilisateur
	$user_sections = mysqli_query($link, "select id, name from SECTIONS where SECTIONS.user_id = $id order by SECTIONS.position ASC");

	if ($user_sections)
	{
// 		echo("Request Sections OK");
		// Parcours des sections
		while($section = mysqli_fetch_assoc($user_sections))
		{

			$section_id = $section['id'];
			$section_name = $section['name'];
			
			echo("<div id=\"links\"><h3>".$section_name." :</h3>");
			
			// Récupération des liens liés à la section en cours
			$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
			$section_links = mysqli_query($link, "select name, url from LINKS where LINKS.section_id = $section_id order by LINKS.position ASC");
			if ($section_links)
			{
// 				echo("Request Liens OK");
				// Parcours des sections
				while($link = mysqli_fetch_assoc($section_links))
				{
					$link_name = $link['name'];
					$link_url = $link['url'];
					
					echo("<br/>$link_name : <a href='$link_url'>$link_url</a>");
				}
				echo("</div>");
			}
		}
	}
}

// Si une connexion à la BDD a été faite, on déconnecte
if (isset($link))
{
	mysqli_close($link);
}
?>
	</body>
</html>