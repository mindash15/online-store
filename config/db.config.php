<?php
function getDbConnection()
{
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=online_store","root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>