<?php
    require_once '../../../dbC.php';
    session_start();
    if(!isset($_POST['submit']) || !isset($_SESSION['uid'])) {
        header("Location: page-not-found.php");
        exit();
    } else {
        $parolaCurenta = $conn->real_escape_string(filter_var(trim($_POST["parola-curenta"]), FILTER_SANITIZE_STRING));
        $parolaNoua = $conn->real_escape_string(filter_var(trim($_POST["parola-noua"]), FILTER_SANITIZE_STRING));
        $parolaNouaC = $conn->real_escape_string(filter_var(trim($_POST["parola-noua-c"]), FILTER_SANITIZE_STRING));
        if(empty($parolaNoua) || empty($parolaCurenta) || empty($parolaNouaC)) {
            header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=error");
            exit();
        } else {
            if($parolaNoua != $parolaNouaC) {
                header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=error");
                exit();
            } else {
                if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $parolaNoua)) {
                    header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=error");
                    exit();
                } else {
                    $sql = "select u_password from utilizatori where u_id = " . $_SESSION['uid'] . ';';
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $passwordCheck = password_verify($parolaCurenta, $row["u_password"]);
                    if($passwordCheck == false) {
                        header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=error");
                        exit();
                    } else if($passwordCheck == true) {
                        $hashedParolaNoua = password_hash($parolaNoua, PASSWORD_DEFAULT);
                        $sqlSchimbaParola = "update utilizatori set u_password = '" . $hashedParolaNoua . "' where u_id = " . $_SESSION['uid'] . ';';
                        if(!($conn->query($sqlSchimbaParola))) {
                            header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=error");
                            error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit();
                        } else {
                            header("Location: profil.php?username=" . $_SESSION['uname'] . "&sp=success");
                            exit();
                        }
                    }
                }
            }
        }
    }
