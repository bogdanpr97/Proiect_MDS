<?php
    require_once '../../../dbC.php';
    session_start();
    if(!isset($_POST['submit']) || !isset($_SESSION['uid'])) {
        header("Location: page-not-found.php");
        exit();
    } else {
        $emailNou = $conn->real_escape_string(filter_var(trim($_POST["email-nou"]), FILTER_SANITIZE_STRING));
        $parolaCurenta = $conn->real_escape_string(filter_var(trim($_POST["parola-curenta"]), FILTER_SANITIZE_STRING));
        $parolaCurentaC = $conn->real_escape_string(filter_var(trim($_POST["parola-curenta-c"]), FILTER_SANITIZE_STRING));
        if(empty($emailNou) || empty($parolaCurenta) || empty($parolaCurentaC) || !filter_var($emailNou, FILTER_VALIDATE_EMAIL)) {
            header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=error");
            exit();
        } else {
            if($parolaCurenta != $parolaCurentaC) {
                header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=error");
                exit();
            } else {
                if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $parolaCurenta)) {
                    header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=error");
                    exit();
                } else {
                    $sql = "select u_password from utilizatori where u_id = " . $_SESSION['uid'] . ';';
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $passwordCheck = password_verify($parolaCurenta, $row["u_password"]);
                    if($passwordCheck == false) {
                        header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=error");
                        exit();
                    } else if($passwordCheck == true) {
                        $sqlSchimbaEmail = "update utilizatori set u_email = '" . $emailNou . "' where u_id = " . $_SESSION['uid'] . ';';
                        if(!($conn->query($sqlSchimbaEmail))) {
                            header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=error");
                            error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit();
                        } else {
                            header("Location: profil.php?username=" . $_SESSION['uname'] . "&se=success");
                            exit();
                        }
                    }
                }
            }
        }
    }
