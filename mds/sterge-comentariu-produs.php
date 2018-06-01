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
            $sqlVerificare = "select * from comentarii_produse where c_id = ? and c_u_id = ? ;";
            if($stmtVerificare = $conn->prepare($sqlVerificare)) {
                $stmtVerificare->bind_param("ii", $cId, $_SESSION['uid']);
                $stmtVerificare->execute();
                $resV = $stmtVerificare->get_result();
                $stmtVerificare->close();
                if($resV->num_rows == 1) {
                    $sqlStergePareri = "delete from pareri_comentarii_produse where c_id = ? ;";
                    if($stmtPareri = $conn->prepare($sqlStergePareri)) {
                        $stmtPareri->bind_param("i", $cId);
                        $stmtPareri->execute();
                        $stmtPareri->close();
                        $sqlSterge = "delete from comentarii_produse where c_id = ? and c_u_id = ? ;";
                        if($stmt = $conn->prepare($sqlSterge)) {
                            $stmt->bind_param("ii", $cId, $_SESSION['uid']);
                            $stmt->execute();
                            if($stmt->affected_rows == 1) {
                                $output = json_encode(array('type' => 'success'));
                                exit($output);
                            } else {
                                $output = json_encode(array('type' => 'error'));
                                exit($output);
                            }
                        } else {
                            $output = json_encode(array('type' => 'error'));
                            error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit($output);
                        }
                    } else {
                        $output = json_encode(array('type' => 'error'));
                        error_log("Error: " . $stmtPareri->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        exit($output);
                    }
                }
            }
        }
    }
