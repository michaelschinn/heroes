<?php

#session_start();

#echo "<h1>Heroes</h1>";


$servername = "localhost";
$username = "root";
$password = "";
$dbName = "superheroes";


$heroObj = [];

function connectDb()
{
    global $servername, $username, $password, $dbName;
    $conn = new mysqli($servername, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed." . $conn->connect_error);
    }

    return $conn;
}

function queryDb($conn, $sql, $successMessage)
{
    if ($conn->query($sql) === TRUE) {
        print_r($successMessage);
    } else {
        print_r("ERROR: " . $sql . "\n" . $conn->error);
    }
}

function queryDbInsert($conn, $sql, $successMessage)
{
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        print_r($successMessage);
        while ($row = $result->fetch_assoc()) {
            $heroObj["id"] = $row["id"];
            $heroObj["name"] = $row["name"];
            $heroObj["about_me"] = $row["about_me"];
            $heroObj["image_url"] = $row["image_url"];
        }
    } else {
        print_r("ERROR: " . $sql . "\n" . $conn->error);
    }
}

function queryDbSelect($conn, $sql)
{
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            print_r(
                "ID: " . $row["id"] . "\n" .
                    "Name: " . $row["name"] . "\n" .
                    "About Me: " . $row["about_me"] . "\n" .
                    "Biography: " . $row["biography"] . "\n" .
                    "Image: " . $row["image_url"] . "\n\n"
            );
        }
        #print_r($result);
    } else {
        print_r("0 results.");
    }
}

function createHero()
{
    global $heroObj;
    $errors = "";

    if (!isset($_GET["name"])) {
        $errors .= "name ";
    }
    if (!isset($_GET["about_me"])) {
        $errors .= "about_me ";
    }
    if (!isset($_GET["biography"])) {
        $errors .= "biography ";
    }
    if (!isset($_GET["ability"])) {
        $errors .= "ability ";
    }
    if (strlen($errors) > 0) {
        print_r("ERROR 422: Missing " . $errors . ".");
        return;
    }

    $name = $_GET["name"];
    $about_me = $_GET["about_me"];
    $biography = $_GET["biography"];
    $ability = $_GET["ability"];
    $image = isset($_GET["image"]) ? $_GET["image"] : null;

    $sql = "INSERT INTO heroes (name, about_me, biography, image_url)
    VALUES ('$name', '$about_me', '$biography', '$image')";

    queryDbInsert(connectDb(), $sql, "Hero successfully created.");
    
    if (isset($heroObj)) {
        $lastId = $heroObj["id"];
        $sql = "INSERT INTO abilities (hero_id, ability_id) VALUES ($lastId, $ability)";
        queryDb(connectDb(), $sql, "Ability successfully added.");
    }

}

function readHero()
{

    if (!isset($_GET["index"])) {
        print_r("ERROR 422: Missing index.");
        return;
    }
    #echo "Read Heroes Fired!";
    $hero = $_GET['index'];
    if ($hero === "all") {
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

function updateHero()
{
    print_r("Update Hero Fired!");
    $updates = "";
    try {
        // check to see if the index in the get array exists
        // if it does modify the query
        $index = $_GET["index"];
        var_dump($index);

        if (isset($_GET["name"])) {
            $updates .= "name = '" . $_GET['name'] . "', ";
        }
        if (isset($_GET["about_me"])) {
            $updates .= "about_me = '" . $_GET['about_me'] . "', ";
        }
        if (isset($_GET["biography"])) {
            $updates .= "biography = '" . $_GET['biography'] . "', ";
        }
        if (isset($_GET["image_url"])) {
            $updates .= "image_url = '" . $_GET['image_url'] . "', ";
        }
        $updates = rtrim($updates, ", ");
        if (!isset($index)) {
            print_r("ERROR 422: Index is required.");
            return;
        } else {
            # "UPDATE heroes SET about_me = 'The Caped Crusader', WHERE id=7"
            $sql = "UPDATE heroes SET $updates WHERE id=$index";
            var_dump($sql);
            queryDb(connectDb(), $sql, "Hero successfully updated.");
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function deleteHero()
{
    print_r("Delete Hero Fired!");
    if ($_GET["index"] < 1 || !isset($_GET["index"])) {
        print_r("ERROR 422: Index is required.");
    } else {
        $index = $_GET["index"];
        $sql = "DELETE FROM heroes WHERE id=$index";
        queryDb(connectDb(), $sql, "Hero successfully deleted.");
    }
}

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
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
