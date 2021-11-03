<?php

#session_start();

#echo "<h1>Heroes</h1>";


$servername = "localhost";
$username = "root";
$password = "";
$dbName = "superheroes";

function connectDb(){
    global $servername, $username, $password, $dbName;
    $conn = new mysqli($servername, $username, $password, $dbName);

    if($conn->connect_error){
        die("Connection failed." . $conn->connect_error);
    }
    
    return $conn;
}

function queryDb($conn, $sql, $successMessage){
    if ($conn->query($sql) === TRUE){
        print_r($successMessage);
    } else {
        print_r("ERROR: " . $sql . "\n" . $conn->error);
    }
}

function queryDbSelect($conn, $sql){
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            print_r(
                "ID: " . $row["id"] . "\n" .
                "Name: " . $row["name"] . "\n" .
                "About Me: " . $row["about_me"] . "\n" .
                "Biography: " . $row["biography"] . "\n" .
                "Image: " . $row["image_url"] . "\n\n"
            );
        }
        print_r($result);
    } else {
        print_r("0 results.");
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
        print_r("ERROR 422: Missing " . $errors . ".");
        return;
    }

    $name = $_GET["name"];
    $about_me = $_GET["about_me"]; 
    $biography = $_GET["biography"]; 
    $image = isset($_GET["image"]) ? $_GET["image"] : null;

    $sql = "INSERT INTO heroes (name, about_me, biography, image_url)
    VALUES ('$name', '$about_me', '$biography', '$image')";

    queryDb(connectDb(), $sql, "Hero successfully created.");
}

function readHero(){

    if (!isset($_GET["index"])){
        print_r("ERROR 422: Missing index.");
        return;
    }
    #echo "Read Heroes Fired!";
    $hero = $_GET['index'];
    if($hero === "all"){
        $sql = "SELECT * FROM heroes";
        #$message = "Read all heroes.";
    } else {
        $sql = "SELECT * FROM heroes WHERE id = $hero";
        #$message = "Read Hero" . $hero . ".";
    }
# TODO: error check in case hero does not match id or all.
# TODO: check if id is within bounds.

    queryDbSelect(connectDb(), $sql);
}

function updateHero(){
    print_r("Update Hero Fired!");
    $updates = "";

    $index = $_GET["index"];
    $name = $_GET["name"];
    $about_me = $_GET["about_me"]; 
    $biography = $_GET["biography"]; 
    $image = $_GET["image_url"] ? $_GET["image_url"] : null;

    if (isset($name)){
        $updates .= "name = '$name'";
    }
    if (isset($about_me)){
        $updates .= "about_me = '$about_me'";
    }
    if (isset($biography)){
        $updates .= "biography = '$biography'";
    }
    if (!isset($index)){
        print_r("ERROR 422: Index is required.");
        return;
    } else {
        $sql = "UPDATE heroes 
        SET '$updates'
        WHERE id='$index'";

        queryDb(connectDb(), $sql, "Hero successfully updated.");
    }
}

function deleteHero(){
    print_r("Delete Hero Fired!");
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
