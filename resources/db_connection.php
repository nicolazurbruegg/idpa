<?php 
    function db_connection()
    {
        //Zugangsdaten DB
        $server = "localhost:3306";
        $benutzer = "root";
        $passwort = "root";
        $datenbank = "idpa";

        //Verbindung zur Datenbank
        $db = new Mysqli($server, $benutzer, $passwort, $datenbank);
        return($db);
    }
?>