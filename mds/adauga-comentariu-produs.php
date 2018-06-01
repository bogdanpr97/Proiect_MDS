<?php
    if(isset($_POST['submit'])) {
        include_once '../../../dbC.php';
    	session_start();
        $comentariu = $conn->real_escape_string(filter_var(trim($_POST["comentariu-produs"]), FILTER_SANITIZE_STRING));
        $produsCod = $conn->real_escape_string(filter_var(trim($_POST["produs-cod"]), FILTER_SANITIZE_STRING));
        $produsId = intval($conn->real_escape_string(filter_var(trim($_POST["produs-id"]), FILTER_SANITIZE_STRING)));
        $sql = "insert into comentarii_produse (c_p_id, c_u_id, c_text, c_data) values ( ? , ? , ? , now());";
        if($stmt = $conn->prepare($sql)) {
            $uid = $_SESSION['uid'];
            $stmt->bind_param("iis", $produsId, $uid, $comentariu);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $stmt->close();
                $file = fopen("../../logsMDS/comentariiProduseLog.txt", "a+");
                $user = "UserID: " . $uid;
                $comentariu = "Comentariu: " . $comentariu;
                $data = "Data: " . date('d-m-Y-G-i-s', time());
                fwrite($file, $user . PHP_EOL);
                fwrite($file, $comentariu . PHP_EOL);
                fwrite($file, $data . PHP_EOL);
                fwrite($file, PHP_EOL);
                fclose($file);
                header("Location: produs.php?cod_produs=" . $produsCod . "&add=success");
                exit();
            } else {
                header("Location: produs.php?cod_produs=" . $produsCod . "&add=error");
                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit();
            }
        } else {
            header("Location: produs.php?cod_produs=" . $produsCod . "&add=error");
            exit();
        }
    } else {
        header("Location: page-not-found.php");
        exit();
    }
