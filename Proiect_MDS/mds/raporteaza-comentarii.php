<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(isset($_POST['c_id']) && isset($_POST['tip']) && $_POST['tip'] == "articol"  && isset($_POST['motiv'])) {
            $motiv = $conn->real_escape_string(filter_var(trim($_POST['motiv']), FILTER_SANITIZE_STRING));
            $cId = intval($conn->real_escape_string(filter_var(trim($_POST['c_id']), FILTER_SANITIZE_STRING)));
            $sql = "insert into raportare_comentarii_articole (c_id, u_id, motiv) values ( ? , ? , ? );";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $cId, $_SESSION['uid'], $motiv);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $output = json_encode(
                        array(
                            'type' => 'success',
                        ));
                exit($output);
            } else {
                $output = json_encode(
                        array(
                            'type' => 'error',
                        ));
                exit($output);
            }
        } else if(isset($_POST['c_id']) && isset($_POST['tip']) && $_POST['tip'] == "produs"  && isset($_POST['motiv'])) {
            $motiv = $conn->real_escape_string(filter_var(trim($_POST['motiv']), FILTER_SANITIZE_STRING));
            $cId = intval($conn->real_escape_string(filter_var(trim($_POST['c_id']), FILTER_SANITIZE_STRING)));
            $sql = "insert into raportare_comentarii_produse (c_id, u_id, motiv) values ( ? , ? , ? );";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $cId, $_SESSION['uid'], $motiv);
            $stmt->execute();
            if($stmt->affected_rows == 1) {
                $output = json_encode(
                        array(
                            'type' => 'success',
                        ));
                exit($output);
            } else {
                $output = json_encode(
                        array(
                            'type' => 'error',
                        ));
                exit($output);
            }
        }
    }
