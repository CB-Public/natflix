<?php

$host = "127.0.0.1";
$db = "movieapi";
$user = "root";
$password = "";

$port = "3306";
$charset = "utf8mb4";

$options = [
    \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE   => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES     => false,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$pdo = new \PDO($dsn, $user, $password, $options);






if (isset($_POST)) {
    $data = file_get_contents("php://input");
    $newdata = json_decode($data, true);
}


if (in_array("fav_insert", $newdata) && $newdata !== NULL) {
    $userid = $newdata["user_id"] ?? "";
    $keyword = $newdata["keyword"] ?? "";


    // alle Fav Filme von user_favmovies
    $sql_AllUserFavMov = "SELECT * FROM user_favmovies";
    $stmt = $pdo->prepare($sql_AllUserFavMov);
    $stmt->execute();
    $result_AllUserFavMov = $stmt->fetchAll();


    if (isset($keyword) && isset($userid)) {
        $sql = "SELECT mov_id FROM user_favmovies WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userid]);
        $result_MovIds = $stmt->fetchAll();

        $listOfMovIds = [];
        foreach ($result_MovIds as $elem) {
            array_push($listOfMovIds, $elem["mov_id"]);
        }

        $listofFavMovs = [];
        foreach ($listOfMovIds as $elem) {
            $sqlfavmov = "SELECT * FROM favmovies WHERE mov_id = ?";
            $stmt_favmov = $pdo->prepare($sqlfavmov);
            $stmt_favmov->execute([$elem]);
            $listofFavMovs[] = $stmt_favmov->fetchAll();
        }

        $dataFromFav = [];
        foreach ($listofFavMovs as $elem) {
            array_push($dataFromFav, $elem[0]);
        }


        header("Content-Type: application/json");
        echo json_encode($dataFromFav);
        exit();
    }
}

// Login
if (in_array("userlogin", $newdata) && $newdata !== NULL) {
    $data = [];

    $sql_UserSelect = 'SELECT * FROM user WHERE username = :username AND pw = :pw';
    $stmt = $pdo->prepare($sql_UserSelect);
    $stmt->execute(["username" => $newdata["username"], "pw" => hash("sha256", $newdata["password"])]);
    $result = $stmt->fetch();
    unset($result['pw']);
    $data = $result;
    $userid = $data["user_id"];

    $sql = "SELECT * FROM favmovies as fm WHERE fm.mov_id in (SELECT mov_id FROM user_favmovies WHERE user_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userid]);
    $result = $stmt->fetchAll();
    $data["user_favmov"] = $result;

    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}


// Registrieren
if (in_array("registry", $newdata) && $newdata !== NULL) {

    $sql_UserSelect = 'SELECT * FROM user WHERE username = :username';
    $stmn = $pdo->prepare($sql_UserSelect);
    $stmn->execute(["username" => $newdata["username"]]);
    $result = $stmn->fetch();
    $checkIfUserExists = $result["username"] ?? "";

    // Insert der Selects zu User
    if ($newdata !== null && $checkIfUserExists !== $newdata["username"]) {
        $sql_insert = "INSERT INTO user (username, pw) VALUES (?,?)";
        $pdo->prepare($sql_insert)->execute([$newdata["username"], hash("sha256", $newdata["password"])]);
        header("Content-Type: application/json");
        echo json_encode((object)["message" => "Registierung Erfolgreich!"]);
        exit();
    }

    if ($checkIfUserExists == $newdata["username"]) {
        header("Content-Type: application/json");
        $checkIfUserExists = "";
        echo json_encode((object)["message" => "Benutzername bereits vergeben!"]);
        exit();
    }

    // // Verbindung geschlossen
    $pdo = null;
    unset($result['password']);
    unset($newdata['password']);
}



// Get UserID + Show User Favmovies of user_favmovies
if (in_array("currentUserIDFromSQL", $newdata) && $newdata !== NULL) {
    $sql = "SELECT * FROM favmovies as fm WHERE fm.mov_id in (SELECT mov_id FROM user_favmovies WHERE user_id = ?)";
    $result = $pdo->query($sql)->fetch();

    $data = array();
    while ($row = $result) {
        $data[] = $row;
    }

    // Verbindung geschlossen
    $pdo = null;

    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}



// Löschen der Favs
foreach ($newdata as $elem) {
    if (is_array($elem) && key_exists("genre", $elem) && $newdata !== NULL) {

        if ($newdata[0]["mov_id"] !== NULL) {
            $movid_del = $newdata[0]["mov_id"];
            $sql_delete = "DELETE FROM user_favmovies WHERE mov_id= :movid_del";
            $stmt = $pdo->prepare($sql_delete);
            $stmt->execute([$movid_del]);
        }

        $imdbid_del = $elem["imdbid"];
        $sql_delete = "DELETE FROM favmovies WHERE imdbid= :imdbid_del";
        $stmt = $pdo->prepare($sql_delete);
        $stmt->bindParam(":imdbid_del", $imdbid_del);
        $stmt->execute();
        echo "Der Film `" . $elem["title"] . "` wurde soeben gelöscht.";
        return;
    }
}



// Insert zu Favs
if (key_exists("currentUserId", $newdata) && $newdata !== NULL) {

    var_dump($newdata);

    $sql_favmov = "SELECT * FROM favmovies WHERE imdbid = ?";
    $stmt = $pdo->prepare($sql_favmov);
    $stmt->bindParam(1, $newdata["imdbID_key"]);
    $stmt->execute();
    $result_movid = $stmt->fetch();
    // var_dump($result_movid);

    if ($result_movid == false) {
        $sql_intoFavMovies = "INSERT INTO favmovies (title, imdbid, genre, img_url, plot, year, rating) VALUES (?,?,?,?,?,?,?)";
        $pdo->prepare($sql_intoFavMovies)->execute(
            [
                $newdata["title_key"],
                $newdata["imdbID_key"],
                $newdata["genre_key"],
                $newdata["poster_key"],
                $newdata["plot_key"],
                $newdata["year_key"],
                $newdata["rating_key"],
            ]
        );
    }

    $sql_selectMovId = "SELECT mov_id FROM favmovies WHERE imdbid = ?";
    $stmt = $pdo->prepare($sql_selectMovId);
    $stmt->bindParam(1, $newdata["imdbID_key"]);
    $stmt->execute();
    $result_selectMovId = $stmt->fetch()["mov_id"];
    // var_dump($result_selectMovId);

    $sql_ifUserFavMovExists = "SELECT * FROM user_favmovies WHERE mov_id = ?";
    $stmt = $pdo->prepare($sql_ifUserFavMovExists);
    $stmt->bindParam(1, $result_selectMovId);
    $stmt->execute();
    $result_IfUserFavMovExists = $stmt->fetch();

    if ($result_IfUserFavMovExists == false) {
        $sql_intoUserFavMovies = "INSERT INTO user_favmovies (user_id, mov_id) VALUES (?,?)";
        $pdo->prepare($sql_intoUserFavMovies)->execute([$newdata["currentUserId"], $result_selectMovId]);
        $pdo = null;
    }
}



// Fav Filme der Nutzer holen
if (key_exists("fav", $newdata) && $newdata !== NULL) {

    $data = [];
    $sql = "SELECT * FROM favmovies as fm WHERE fm.mov_id in (SELECT mov_id FROM user_favmovies WHERE user_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newdata["user_id"]]);
    $result = $stmt->fetchAll();
    $data["user_favmov"] = $result;

    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}
