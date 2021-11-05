<?php

session_start();

#echo "<h1>Heroes</h1>";


$servername = "localhost";
$username = "root";
$password = "";
$dbName = "superheroes";


$heroObj = [];
$conn = null;

function connectDb()
{
    global $servername, $username, $password, $dbName, $conn;
    $conn = new mysqli($servername, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed." . $conn->connect_error);
    }
    $_SESSION["connection"] = $conn;
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
/*
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
*/
function queryDbSelect($conn, $sql)
{
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result;
    } else {
        print_r("0 results.");
    }
}

function createHero()
{
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
    if (strlen($errors) > 0) {
        print_r("ERROR 422: Missing " . $errors . ".");
        return;
    }

    $name = $_GET["name"];
    $about_me = $_GET["about_me"];
    $biography = $_GET["biography"];
    $image = isset($_GET["image"]) ? $_GET["image"] : null;
    $ability = isset($_GET["ability"]) ? $_GET["ability"] : "";

    $sql = "INSERT INTO heroes (name, about_me, biography, image_url)
    VALUES ('$name', '$about_me', '$biography', '$image')";

    queryDb(connectDb(), $sql, "Hero successfully created.");

    if (isset($_SESSION["connection"])) {
        $heroId = $_SESSION["connection"]->insert_id;
        if (strlen($ability) > 0) {
            $abilities = strlen($ability) === 1 ? str_split($ability) : explode(", ", $ability);
            foreach ($abilities as $key => $value) {
                if ($key === 0) {
                    $sql = "INSERT INTO abilities (hero_id, ability_id)
                    VALUES ('$heroId', '$value');";
                } else {
                    $sql .= "INSERT INTO abilities (hero_id, ability_id)
                    VALUES ('$heroId', '$value');";
                }
            }
            queryDb(connectDb(), $sql, "Abilities added.");
        }
    }
}
/*
function addAbility()
{
    $ability = isset($_GET["ability"]) ? $_GET["ability"] : null;
    $errors = "";

    if (!isset($_GET["id"])) {
        $errors .= "id";
    }
    if (!isset($_GET["ability"])) {
        $errors .= "ability";
    }

    $id = $_GET["id"];
    $ability = $_GET["ability"];

    $sql = "INSERT INTO abilities (hero_id, ability_id) VALUES ($id, $ability)";
    queryDb(connectDb(), $sql, "Ability successfully added to " . $id . ".");

    if (strlen($errors) > 0) {
        print_r("ERROR 422: Missing " . $errors . ".");
        return;
    }
}
*/
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

    $allHeros = queryDbSelect(connectDb(), $sql);

    while ($row = $allHeros->fetch_assoc()) {
        $heroId = $row["id"];
        $output = "\nID: " . $row["id"] . "\n" .
            "Name: " . $row["name"] . "\n" .
            "About Me: " . $row["about_me"] . "\n" .
            "Biography: " . $row["biography"] . "\n" .
            "Image: " . $row["image_url"] . "\n";

        $sql = "SELECT ability_id FROM abilities WHERE hero_id = '$heroId'";

        $abilities = queryDbSelect(connectDb(), $sql);
        while($row = $abilities->fetch_assoc()){
            $abilityId = $row["ability_id"];
            $sql = "SELECT ability FROM ability_type WHERE id = '$abilityId'";
            $ability = queryDbSelect(connectDb(), $sql);
            while($row = $ability->fetch_assoc()){
                $abilityName = $row["ability"];
                $output .= "Ability: '$abilityName' \n";
            }
        }
        print_r($output);
    }
}

function updateHero()
{
    print_r("Update Hero Fired!");
    $updates = "";
    $abilities = "";
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
        /*if (isset($_GET["ability"])) {
            $abilities .= "ability_id = '" . $_GET['ability'] . "', ";
        }*/
        $updates = rtrim($updates, ", ");
        if (!isset($index)) {
            print_r("ERROR 422: Index is required.");
            return;
        } else {
            # "UPDATE heroes SET about_me = 'The Caped Crusader', WHERE id=7"
            $sql = "UPDATE heroes SET $updates WHERE id=$index";
            var_dump($sql);
            queryDb(connectDb(), $sql, "Hero successfully updated.");

            /*$sql = "UPDATE abilities SET $abilities WHERE hero_id=$index"*/
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
