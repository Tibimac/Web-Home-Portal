<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Web Home Portal - Login</title>
		<link rel="stylesheet" href="css/style.css" media="screen" type="text/css"/>
		<!-- CSS Style base on Marco Biedermann's work here : http://codepen.io/marcobiedermann/pen/Fybpf and modified by me (Thibault Le Cornec) for my need.-->
	</head>
<?php
$isAuthentificatedOK = 0; // Auth non OK

if(isset($_POST['login']) && !empty($_POST['login']))
{		
	// Connexion à la base de données
	require_once('db_vars.php');
    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);   
    
	// Récupération contenu des champs saisi par l'utilisateur
	$form_id = $_POST['form_id'];
	$form_password = $_POST['form_password'];
 	
	// Récupération du mot de passe et du mot de salage correspondant au user demandant à se connecter
    $request = mysqli_query($link, "SELECT * FROM USERS WHERE login = '".$form_id."'");

	if ($request)
	{
		$line = mysqli_fetch_assoc($request);
		
		$salt = $line['salt']; //Récupération du mot de salage
		$salted_password = $salt.$form_password.$salt;
		$encrypted_password = md5($salted_password); // Cryptage
		
		$stringToCheck = $form_id."-".$encrypted_password;
	
		$db_password = $line['password']; // Récupération du mot de passe du user de la base
		$IDS = $form_id."-".$db_password;
		
		if ($stringToCheck === $IDS)
		{
			$isAuthentificatedOK = 1;
		}	
	}
	else
	{
		echo("La requête a échouée !");
	}
 }
 
if ($isAuthentificatedOK == 0)
{
	echo('
	<body>
		<div class="container">
			<div id="login">
				<form action="index.php" method="POST">
					<p>
						<span class="fontawesome-user"></span>
						<input type="text" value="Username" name="form_id" onBlur="if(this.value == \'\') this.value = \'Username\'" onFocus="if(this.value == \'Username\') this.value = \'\'" required>
					</p>
					<p>
						<span class="fontawesome-lock"></span>
						<input type="password"  value="Password" name="form_password" onBlur="if(this.value == \'\') this.value = \'Password\'" onFocus="if(this.value == \'Password\') this.value = \'\'" required>
					</p>
					<p>
						<input type="submit" value="Sign In" name="login">
					</p>
				</form>
			</div>
		</div>');
}
else if ($isAuthentificatedOK == 1)
{
	#	<!- Temporairement inclusion des liens perso via fichier HTML -->
	require_once('links.html');
	#	<!- Futur : Inclusion des liens via sélection dans la base de données -->

	mysqli_close($link);
	echo('
	</body>
</html>');
}
?>