<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST['username']) || !isset($_POST['subiect']) || !isset($_POST['mesaj'])) {
            $output = json_encode(
                    array(
                        'type' => 'error',
                    ));
            exit($output);
        } else {
            $username = $conn->real_escape_string(filter_var(trim($_POST["username"]), FILTER_SANITIZE_STRING));
            $mesaj = $conn->real_escape_string(filter_var(trim($_POST["mesaj"]), FILTER_SANITIZE_STRING));
            $subiect = $conn->real_escape_string(filter_var(trim($_POST["subiect"]), FILTER_SANITIZE_STRING));
            $sqlVerificare = "select * from utilizatori where u_username = ? ;";
            if($stmt = $conn->prepare($sqlVerificare)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if(!$result || $result->num_rows == 0) {
                    $output = json_encode(
                            array(
                                'type' => 'error',
                            ));
                    error_log("Error1: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    exit($output);
                } else {
                    $stmt->close();
                    $row = $result->fetch_assoc();
                    $destinatarId = $row['u_id'];
                    $result->close();
                    $sqlVerificareBlocare = " select * from utilizatori_blocati where ( uid_i = " . $_SESSION['uid'] . " and uid_b = " . $destinatarId . " ) or ( uid_i = " . $destinatarId . " and uid_b = " . $_SESSION['uid'] . ");";
                    $resBlocare = $conn->query($sqlVerificareBlocare);
                    if($resBlocare->num_rows == 0 ) {
                        $resBlocare->close();
                        $sqlInsert = "insert into mesaje_utilizatori (m_expeditor_uid, m_destinatar_uid, m_text, m_titlu) values ( ? , ? , ? , ? );";
                        if($stmt2 = $conn->prepare($sqlInsert)) {
                            $stmt2->bind_param("iiss", $_SESSION['uid'], $destinatarId, $mesaj, $subiect);
                            $stmt2->execute();
                            if($stmt2->affected_rows == 1) {
                                $stmt2->close();
                                $file = fopen("../../logsMDS/mesajeUtilizatoriLog.txt", "a+");
                                $user1 = "ExpeditorID: " . $_SESSION['uid'];
                                $user2 = "DestinatarID: " . $destinatarId;
                                $subiect = "Subiect: " . $subiect;
                                $mesaj = "Mesaj: " . $mesaj;
                                $data = "Data: " . date('d-m-Y-G-i-s', time());
                                fwrite($file, $user1 . PHP_EOL);
                                fwrite($file, $user2 . PHP_EOL);
                                fwrite($file, $subiect . PHP_EOL);
                                fwrite($file, $mesaj . PHP_EOL);
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
                                error_log("Error2: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                exit($output);
                            }
                        } else {
                            $output = json_encode(
                                    array(
                                        'type' => 'error',
                                    ));
                            error_log("Error3: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit($output);
                        }
                    } else {
                        $file = fopen("../../logsMDS/actiuniNepermise.txt", "a+");
                        $user = "ID user: " . $destinatarId;
                        fwrite($file, $user . PHP_EOL);
                        fwrite($file, "Incercare trimitere mesaj catre cei blocati" . PHP_EOL);
                        fwrite($file, PHP_EOL);
                        fclose($file);
                        $output = json_encode(
                                array(
                                    'type' => 'error',
                                ));
                        exit($output);
                    }
                }
            } else {
                $output = json_encode(
                        array(
                            'type' => 'error',
                        ));
                error_log("Error4: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit($output);
            }
        }
    }
