<?php
    require_once '../../../dbC.php';
    session_start();
    if(!isset($_COOKIE["rememberme"])) {
        if(isset($_SESSION['uid'])) {
            unset($_SESSION['uprivilegiu']);
            unset($_SESSION['uid']);
            unset($_SESSION['uname']);
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        if(isset($_SESSION['uid'])) {
            $sql = "update utilizatori set u_rememberme = null where u_id = " . $_SESSION['uid'] . ';';
            $conn->query($sql);
            setcookie("rememberme", "", time()-3600);
            unset($_SESSION['uprivilegiu']);
            unset($_SESSION['uid']);
            unset($_SESSION['uname']);
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
