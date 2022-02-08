<?php


$sql = "INSERT INTO time (starttime, session_id) VALUES (?, ?)";
$stmt = $db->prepare($sql);
$timestamp = date_create()->format('Y-m-d H:i:s');
$stmt->bind_param("ss", $timestamp, "testetstetest");
$stmt->execute();



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


?>