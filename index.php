<?php

#session_start();

#echo "<h1>Heroes</h1>";


$servername = "localhost";
$username = "root";
$password = "";
$dbName = "superheroes";

function connectDb(){
    global $servername, $username, $password, $db;
    $conn = new mysqli($servername, $username, $password, $db);

    if($conn->connect_error){
        die("Connection failed." . $conn->connect_error);
    }
    
    return $conn;
}

function queryDb($conn, $sql, $successMessage){
    if ($conn->query($sql) === TRUE){
        echo $successMessage;
    } else {
        echo "ERROR: " . $sql . "<br>" . $conn->error;
    }
}


function createHero(){
    $errors = "";

    if (!isset($_GET["name"])){
        $errors .= "name ";
    }
    if (!isset($_GET["about_me"])){
        $errors .= "about_me ";
    }
    if (!isset($_GET["biography"])){
        $errors .= "biography ";
    }
    if (strlen($errors) > 0){
        echo "ERROR 422: Missing " . $errors . ".";
        return;
    }

    $name = $_GET["name"];
    $about_me = $_GET["about_me"]; 
    $biography = $_GET["biography"]; 
    $image = isset($_GET["image"]) ? $_GET["image"] : null;

    $sql = "INSERT INTO superheroes
    VALUES ('$name', '$about_me', '$biography', '$image')";

    queryDb(connectDb(), $sql, "Hero successfully created.");
}

function readHero(){

    if (!isset($_GET["index"])){
        echo "ERROR 422: Missing index.";
        return;
    }
    echo "Read Heroes Fired!";
    $hero = $_GET['index'];
    if($hero === "all"){
        $sql = "SELECT * FROM heroes";
        $message = "Read all heroes.";
    } else {
        $sql = "SELECT * FROM heroes WHERE id = $hero";
        $message = "Read Hero" . $hero . ".";
    }
# TODO: error check in case hero does not match id or all.
# TODO: check if id is within bounds.

    queryDb(connectDb(), $sql, $message);
}

function updateHero(){
    echo "Update Hero Fired!";
}

function deleteHero(){
    echo "Delete Hero Fired!";
}



if (isset($_GET["action"])){
    switch($_GET["action"]){
        case "createHero":
            createHero();
            break;
        case "readHero":
            readHero();
            break;
        case "updateHero";
            updateHero();
            break;
        case "deleteHero";
            deleteHero();
            break;
        default:
            return;
    }
}
