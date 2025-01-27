<?php


$host = "127.0.0.1";
$db = "movieAPI";
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
