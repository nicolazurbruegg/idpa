<?php
//Ressourcen laden
include_once 'resources/builder.php';
include_once 'resources/db_connection.php';
error_reporting(E_ERROR | E_PARSE);
//Session starten
session_start();

$temp = trim($_SERVER['REQUEST_URI'],'/');
$url = explode('/', $temp);
if($_SESSION['game'] == null) {
	$random = random_int(1,2);
	$_SESSION['game'] = $random;
}


$db = db_connection();
if ($db->connect_error)
{
	die("Connection failed: " . $db->connect_error);
}
$sql = "SELECT * FROM time where session_id = ?";
$stmt = $db->prepare($sql);
$session = session_id();
$stmt->bind_param("s", $session);
$stmt->execute();
$result =  $stmt->get_result();

if ($result->num_rows == 0) {
	$sql = "INSERT INTO time (starttime, session_id, gameNumber, playedGames) VALUES (?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$timestamp = date_create()->format('Y-m-d H:i:s');
	$x = 1;
	$stmt->bind_param("ssii", $timestamp, $session, $random, $x);
	$stmt->execute();
} else if ($result->num_rows == 1) {
	
}


if(!empty($url[0])){
	//alles in Kleinbuchstaben umwandeln
	$url[0] = strtolower($url[0]);

	switch($url[0]){
		//Seiten einfügen
		case 'save-score':

			$participantName = $_POST['participantName'];
			$score= $_POST['score'];

			$sql = "INSERT INTO highscore (name, score) VALUES (?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("si", $participantName, $score);
			$stmt->execute();
			break;

		case 'save-endtime.php':
			$sql = "SELECT * FROM time where session_id = ?";
			$stmt = $db->prepare($sql);
			$session = session_id();
			$stmt->bind_param("s", $session);
			$stmt->execute();
			$result =  $stmt->get_result();
			if ($result->num_rows == 1) {
				$sql = "update time set endtime = ? where session_id = ?";
				$stmt = $db->prepare($sql);
				$timestamp = date_create()->format('Y-m-d H:i:s');
				$stmt->bind_param("ss", $timestamp, session_id());
				$stmt->execute();
			}
			break;
		case 'increase-retries':
			
			$sql = "SELECT * FROM time where session_id = ?";
			$stmt = $db->prepare($sql);
			$session = session_id();
			$stmt->bind_param("s", $session);
			$stmt->execute();
			$result =  $stmt->get_result();
			if ($result->num_rows == 1) {
				$playedGames = $result->fetch_row()[5];
				if($playedGames == null) {
					$playedGames = 1;
				} else {
					$playedGames += 1;
				}
				$sql = "update time set playedGames = ? where session_id = ?";
				$stmt = $db->prepare($sql);
				$timestamp = date_create()->format('Y-m-d H:i:s');
				$stmt->bind_param("is", $playedGames, session_id());
				$stmt->execute();
			}
			break;

	    default:
			break;
    }
} else{
	switch($_SESSION['game']) {
		case 1:
			build('./views/game-1.php');
			break;
		case 2:
			build('./views/game-2.php');
			break;
		default:
			break;
		
	}  
}

?>