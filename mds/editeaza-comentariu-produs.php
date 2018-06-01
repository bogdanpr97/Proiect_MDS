<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST['c_id']) || !isset($_POST['mesaj'])) {
            $output = json_encode(
                    array(
                        'type' => 'error',
                    ));
            exit($output);
        } else {
            $cId = intval($conn->real_escape_string(filter_var(trim($_POST["c_id"]), FILTER_SANITIZE_STRING)));
            $mesaj = $conn->real_escape_string(filter_var(trim($_POST["mesaj"]), FILTER_SANITIZE_STRING));
            $sqlVerificare = "select * from comentarii_produse where c_id = ? and c_u_id = ? ;";
            if($stmt = $conn->prepare($sqlVerificare)) {
                $stmt->bind_param("ii", $cId, $_SESSION['uid']);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                if($result->num_rows == 0) {
                    $output = json_encode(
                            array(
                                'type' => 'error',
                            ));
                    exit($output);
                } else {
                    $sqlUpdate = "update comentarii_produse set c_text = ? , c_data = concat('Editat la: ', now()) where c_id = ? and c_u_id = ? ;";
                    if($stmt2 = $conn->prepare($sqlUpdate)) {
                        $stmt2->bind_param("sii", $mesaj, $cId, $_SESSION['uid']);
                        $stmt2->execute();
                        if($stmt2->affected_rows == 1) {
                            $stmt2->close();
                            $file = fopen("comentariiProduseLog.txt", "a+");
                            $user = "UserID: " . $_SESSION['uid'];
                            $comentariu = "Comentariu: " . $mesaj;
                            $data = "Editat la data: " . date('d-m-Y-G-i-s', time());
                            fwrite($file, $user . PHP_EOL);
                            fwrite($file, $comentariu . PHP_EOL);
                            fwrite($file, $data . PHP_EOL);
                            fwrite($file, PHP_EOL);
                            fclose($file);
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
                            error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit($output);
                        }
                    } else {
                        $output = json_encode(
                                array(
                                    'type' => 'error',
                                ));
                        error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        exit($output);
                    }
                }
            } else {
                $output = json_encode(
                        array(
                            'type' => 'error',
                        ));
                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit($output);
            }
        }
    }
