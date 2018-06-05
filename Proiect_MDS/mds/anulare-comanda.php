<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if (!isset($_POST["c_id"])) {
            $output = json_encode(array('type' => 'error'));
            exit($output);
        } else {
            $cId = intval($conn->real_escape_string(filter_var(trim($_POST["c_id"]), FILTER_SANITIZE_STRING)));
            $sqlVerificare = "select * from comenzi where comanda_id = ? and comanda_u_id = ? ;";
            if($stmtVerificare = $conn->prepare($sqlVerificare)) {
                $stmtVerificare->bind_param("ii", $cId, $_SESSION['uid']);
                $stmtVerificare->execute();
                $resV = $stmtVerificare->get_result();
                $stmtVerificare->close();
                if($resV->num_rows == 1) {
                    $resV->close();
                    $sqlAnulare = "delete from comenzi where comanda_id = ? and comanda_u_id = ? ;";
                    if($stmtAnulare = $conn->prepare($sqlAnulare)) {
                        $stmtAnulare->bind_param("ii", $cId, $_SESSION['uid']);
                        $stmtAnulare->execute();
                        $output = json_encode(array('type' => 'success'));
                        exit($output);
                    } else {
                        $output = json_encode(array('type' => 'error'));
                        error_log("Error: " . $stmtAnulare->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        exit($output);
                    }
                } else {
                    $output = json_encode(array('type' => 'error'));
                    exit($output);
                }
            } else {
                $output = json_encode(array('type' => 'error'));
                error_log("Error: " . $stmtVerificare->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit($output);
            }
        }
    }
