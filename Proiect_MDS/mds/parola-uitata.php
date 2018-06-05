<?php
    require_once '../../../dbC.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    session_start();
    if(!isset($_POST['email-parola-uitata'])) {
        header("Location: page-not-found.php");
        exit();
    } else {
        $email = filter_var(trim($_POST["email-parola-uitata"]), FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: login.php?error=invalid");
            exit();
        } else {
            $sql = "select * from utilizatori where u_email = ? ;";
            if(!($stmt = $conn->prepare($sql))) {
                header("Location: login.php");
                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                exit();
            } else {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                if($stmt->affected_rows == 0) {
                    header("Location: login.php?error=invalid");
                    exit();
                } else {
                    $result = $stmt->get_result();
                    $stmt->close();
                    $row = $result->fetch_assoc();
                    $result->close();
                    $sqlAdaugare = "update utilizatori set u_cod_resetare_parola = ? where u_id = ? ;";
                    if($stmt2 = $conn->prepare($sqlAdaugare)) {
                        $key = "cod_verificare_resetare_parola";
                        $time = time();
                        $cod_verificare = hash_hmac('sha256', $time, $key);
                        $stmt2->bind_param("ss", $cod_verificare, $row['u_id']);
                        $stmt2->execute();
                        if($stmt2->affected_rows == 1) {
                            $stmt2->close();
                            $mail = new PHPMailer(true);
                            try {
                                $mail->isSMTP();
                                $mail->Host = 'smtp.mail.yahoo.com';
                                $mail->SMTPAuth = true;
                                $mail->Username = 'robertgrmds@yahoo.com';
                                $mail->Password = 'zxc567bnM0';
                                $mail->Port = 465;
                                $mail->SMTPSecure = "ssl";
                                $mail->setFrom('robertgrmds@yahoo.com');
                                $mail->addAddress($email);
                                $mesaj = "Mail de confirmare pentru resetarea parolei<br><br>
                                          Daca nu dumneavoastra ati solicitat aceasta cerere, ignorati acest mesaj.<br><br> Link pentru resetarea parolei:"
                                          . "localhost/mds/resetare-parola.php?cod_verificare=" . $cod_verificare . "&email=" . $email;
                                $mail->isHTML(true);
                                $mail->Subject = "Resetare parola cont Pro Gains";
                                $mail->Body = $mesaj;
                                $mail->AltBody = $mesaj;
                                $mail->send();
                            } catch (Exception $e) {
                                header("Location: login.php");
                                exit();
                            }
                                header("Location: login.php?et=1");
                                exit();
                        } else {
                            header("Location: login.php?error=invalid");
                            error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            exit();
                        }
                    } else {
                        header("Location: login.php");
                        error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        exit();
                    }
                }
            }
        }
    }
