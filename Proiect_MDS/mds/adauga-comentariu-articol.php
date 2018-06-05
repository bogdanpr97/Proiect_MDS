<?php
    if(isset($_POST['submit'])) {
        include_once '../../../dbC.php';
    	session_start();
        $comentariu = $conn->real_escape_string(filter_var(trim($_POST["comentariu-articol"]), FILTER_SANITIZE_STRING));
        $articolId = intval($conn->real_escape_string(filter_var(trim($_POST["articol-id"]), FILTER_SANITIZE_STRING)));
        $articolTitlu = $conn->real_escape_string(filter_var(trim($_POST["articol-titlu"]), FILTER_SANITIZE_STRING));
        $articolData = $conn->real_escape_string(filter_var(trim($_POST["articol-data"]), FILTER_SANITIZE_STRING));
        $sql = "insert into comentarii_articole (a_id, u_id, c_text, c_data) values ( ? , ? , ? , now());";
        if($stmt = $conn->prepare($sql)) {
            $uid = $_SESSION['uid'];
            $stmt->bind_param("iis", $articolId, $uid, $comentariu);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $stmt->close();
                $file = fopen("../../logsMDS/comentariiArticoleLog.txt", "a+");
                $user = "UserID: " . $uid;
                $comentariu = "Comentariu: " . $comentariu;
                $data = "Data: " . date('d-m-Y-G-i-s', time());
                fwrite($file, $user . PHP_EOL);
                fwrite($file, $comentariu . PHP_EOL);
                fwrite($file, $data . PHP_EOL);
                fwrite($file, PHP_EOL);
                fclose($file);
                header("Location: articol.php?titlu=" . $articolTitlu . "&data=" . $articolData . "&add=success");
                exit();
            } else {
                header("Location: articol.php?titlu=" . $articolTitlu . "&data=" . $articolData . "&add=error");
                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit();
            }
        } else {
            header("Location: articol.php?titlu=" . $articolTitlu . "&data=" . $articolData . "&add=error");
            exit();
        }
    } else {
        header("Location: page-not-found.php");
        exit();
    }
