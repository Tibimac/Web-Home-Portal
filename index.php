<?
$isAuthentificatedOK = 0; // Auth non OK

if(isset($_POST['connect']) && !empty($_POST['connect']))
{		
	// Connexion à la base de données
	require_once('db_vars.php');
    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);   
    
	// Récupération contenu des champs saisi par l'utilisateur
	$form_id = $_POST['form_id'];
	$form_password = $_POST['form_password'];
 	
	// Récupération du mot de passe et du mot de salage correspondant au user demandant à se connecter
    $request = mysqli_query($link, "SELECT salt, password FROM USERS WHERE login = '".$form_id."'");

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
/*	else
	{
		echo("Requête PAS OK");
	} */
	
	mysqli_close($link);
 }
 ?>

<?
if ($isAuthentificatedOK == 0)
{
?>
	<form action="index.php" method="POST">
		Identifiant :<input type="text" id="form_id" name="form_id"/></br/>
		Mot de passe :<input type="password" id="form_password" name="form_password"/><br/>
		<input type="submit" id="connect-button" value="Connexion" name="connect"/>
	</form>
<?
}
else if ($isAuthentificatedOK == 1)
{
	#	<!- Temporairement inclusion des liens perso via fichier HTML -->
	require_once('links.html');
	#	<!- Futur : Inclusion des liens via sélection dans la base de données -->
}
 ?>