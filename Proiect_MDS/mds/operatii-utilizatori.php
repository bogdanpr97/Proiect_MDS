<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(isset($_POST['username']) && isset($_POST['tip']) && $_POST['tip'] == "blocare"  && !isset($_POST['motiv'])) {
            $username = $conn->real_escape_string(filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING));
            $sqlId = "select u_id from utilizatori where u_username = ? ;";
            $stmtId = $conn->prepare($sqlId);
            $stmtId->bind_param("s", $username);
            $stmtId->execute();
            $resultId = $stmtId->get_result();
            $stmtId->close();
            $rowId = $resultId->fetch_assoc();
            $resultId->close();
            $sql = "insert into utilizatori_blocati (uid_i, uid_b) values ( ? , ? );";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $_SESSION['uid'], $rowId['u_id']);
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
        } else if(isset($_POST['username']) && isset($_POST['tip']) && $_POST['tip'] == "deblocare" && !isset($_POST['motiv'])) {
            $username = $conn->real_escape_string(filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING));
            $sqlId = "select u_id from utilizatori where u_username = ? ;";
            $stmtId = $conn->prepare($sqlId);
            $stmtId->bind_param("s", $username);
            $stmtId->execute();
            $resultId = $stmtId->get_result();
            $stmtId->close();
            $rowId = $resultId->fetch_assoc();
            $resultId->close();
            $sql = "delete from utilizatori_blocati where uid_i = ? and uid_b = ? ;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $_SESSION['uid'], $rowId['u_id']);
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
        } else if(isset($_POST['username']) && !isset($_POST['tip']) && isset($_POST['motiv'])) {
            $username = $conn->real_escape_string(filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING));
            $motiv = $conn->real_escape_string(filter_var(trim($_POST['motiv']), FILTER_SANITIZE_STRING));
            $sqlId = "select u_id from utilizatori where u_username = ? ;";
            $stmtId = $conn->prepare($sqlId);
            $stmtId->bind_param("s", $username);
            $stmtId->execute();
            $resultId = $stmtId->get_result();
            $stmtId->close();
            $rowId = $resultId->fetch_assoc();
            $resultId->close();
            $sql = "insert into raportare_utilizatori (u_id, u_raportat_id, motiv) values ( ? , ? , ? );";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iis', $_SESSION['uid'], $rowId['u_id'], $motiv);
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
