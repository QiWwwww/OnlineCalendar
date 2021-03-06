<?php
ini_set("session.cookie_httponly", 1);
session_start();
if($_SESSION['token'] != $_POST['token']){
    echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected"
            ));
    exit;
}else{
    $username = (string) htmlentities($_SESSION['username']);
    $title = (string) htmlentities($_POST['title']);
    $date = (string) htmlentities($_POST['date']);
    $time = (string) htmlentities($_POST['time']);
    $tag = (string) htmlentities($_POST['tag']);
    if ($title == "" || $date == "" || $time == "" || $tag == ""){
        echo json_encode(array(
                "success" => false,
                "message" => "Please enter complete information"
                ));
        exit;
    }else{
        require_once('connectDB.php');
        $stmt = $mysqli->prepare("insert into events(user_name, title, date, tag, time) values (?, ?, ?, ?, ?)");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Insert failed."
                ));
            exit;
        }else{
            $stmt->bind_param('sssss', $username, $title, $date, $tag, $time);
            $stmt->execute();
            $stmt->close();
            echo json_encode(array(
                "success" => true
                ));
            exit;
        }   
    }
}
?>