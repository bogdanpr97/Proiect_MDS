<?php
    require_once '../../../dbC.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $output = json_encode(
                    array(
                        'type' => 'error',
                        'text' => 'Cererea trebuie sa fie de tip Ajax!'
                    ));
            exit($output);
        } else {
            if (!isset($_POST["nume"]) || !isset($_POST["email"]) || !isset($_POST["subiect"]) || !isset($_POST["mesaj"])) {
                $output = json_encode(array('type' => 'error', 'text' => 'Completati toate campurile!'));
                exit($output);
            } else {
                $nume = filter_var(trim($_POST["nume"]), FILTER_SANITIZE_STRING);
                $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
                $subiect = filter_var(trim($_POST["subiect"]), FILTER_SANITIZE_STRING);
                $mesaj = filter_var(trim($_POST["mesaj"]), FILTER_SANITIZE_STRING);
                if(preg_match("/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])*))*(\s)*$/", $nume) == 0) {
                    $output = json_encode(array('type' => 'error', 'text' => 'Numele nu este valid!'));
                    exit($output);
                } else {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email validation
                        $output = json_encode(array('type' => 'error', 'text' => 'Introduceti o adresa de email valida!'));
                        exit($output);
                    } else {
                        if (strlen($mesaj) < 5) { //check emtpy message
                            $output = json_encode(array('type' => 'error', 'text' => 'Mesajul este prea scurt!'));
                            exit($output);
                        } else {
                            $sqlTest = "select * from mesaje_contact_utilizatori where nume = ? and email = ? and subiect = ? and mesaj = ?;";
                            if($stmtTest = $conn->prepare($sqlTest)) {
                                $nume = $conn->real_escape_string($nume);
                                $email = $conn->real_escape_string($email);
                                $subiect = $conn->real_escape_string($subiect);
                                $mesaj = $conn->real_escape_string($mesaj);
                                $stmtTest->bind_param("ssss", $nume, $email, $subiect, $mesaj);
                                $stmtTest->execute();
                                $res = $stmtTest->get_result();
                                if($res->num_rows == 0) {
                                    $stmtTest->close();
                                    $sql = "insert into mesaje_contact_utilizatori (nume, email, subiect, mesaj) values (? , ? , ? , ?);";
                                    if($stmt = $conn->prepare($sql)) {
                                        $stmt->bind_param("ssss", $nume, $email, $subiect, $mesaj);
                                        $stmt->execute();
                                        $stmt->close();
                                        $mail = new PHPMailer(true);
                                        try {
                                            //$mail->SMTPDebug = 2;
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.mail.yahoo.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'robertgrmds@yahoo.com';
                                            $mail->Password = 'zxc567bnM0';
                                            $mail->Port = 465;
                                            $mail->SMTPSecure = "ssl";
                                            $mail->setFrom('robertgrmds@yahoo.com');
                                            $mail->addAddress('robertgrmds@yahoo.com');
                                            $mesaj = $mesaj . '<br/><br/> De la: ' . $email;
                                            $mail->isHTML(true);
                                            $mail->Subject = $subiect;
                                            $mail->Body = $mesaj;
                                            $mail->AltBody = $mesaj;
                                            $mail->send();
                                        } catch (Exception $e) {
                                            $output = json_encode(array('type' => 'message', 'text' => $mail->ErrorInfo));
                                            exit($output);
                                        }
                                            $output = json_encode(array('type' => 'message', 'text' => "Mesajul a fost trimis cu succes"));
                                            exit($output);
                                     } else {
                                        $output = json_encode(array('type' => 'error', 'text' => 'Mesajul nu a putut fi trimis, va rugam contactati administratorul!'));
                                        error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                        exit($output);
                                    }
                                } else {
                                    $output = json_encode(array('type' => 'error', 'text' => 'Acest mesaj a fost trimis deja.'));
                                    exit($output);
                                }
                            } else {
                                $output = json_encode(array('type' => 'error', 'text' => 'Emailul nu a putut fi trimis, va rugam contactati administratorul!'));
                                error_log("Error: " . $stmtTest->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                exit($output);
                            }
                        }
                    }
                }
            }
        }
    }
