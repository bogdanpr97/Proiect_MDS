<?php
    require_once '../../../dbC.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    if(!isset($_POST['submit-nl']) || !isset($_POST['nume-nl']) || !isset($_POST['email-nl'])) {
        header("Location: page-not-found.php");
        exit();
    } else {
        $nume = filter_var(trim($_POST["nume-nl"]), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST["email-nl"]), FILTER_SANITIZE_EMAIL);
        if(preg_match("/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])*))*(\s)*$/", $nume) == 0) {
            header("Location: index.php?abonare-nl=error-nume");
            exit();
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email validation
                header("Location: index.php?abonare-nl=error-email");
                exit();
            } else {
                $sqlTest = "select * from abonati_newsletter where a_email = ? ;";
                if($stmt = $conn->prepare($sqlTest)) {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $rezultat = $stmt->get_result();
                    $stmt->close();
                    if($rezultat->num_rows == 1) {
                        header("Location: index.php?abonare-nl=error-exista");
                        exit();
                    } else {
                        $rezultat->close();
                        $sqlAdaugare = "insert into abonati_newsletter (a_nume, a_cod_verificare, a_email) values (? , ? , ?);";
                        if($stmt2 = $conn->prepare($sqlAdaugare)) {
                            $key = "cod_verificare_newsletter";
                            $time = time();
                            $cod_verificare = hash_hmac('sha256', $time, $key);
                            $stmt2->bind_param("sss", $nume, $cod_verificare, $email);
                            $stmt2->execute();
                            $stmt2->close();
                            $ip = $_SERVER['REMOTE_ADDR'];
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
                                $mesaj = "Va multumim ca v-ati abonat la newsletter-ul Pro Gains\r\n
                                          Daca nu dumneavoastra ati solicitat aceasta cerere, dati click pe linkul urmator pentru a va dezabona: "
                                          . "localhost/mds/dezabonare-newsletter.php?cod_verificare=" . $cod_verificare . "&email=" . $email;
                                $mail->isHTML(true);
                                $mail->Subject = "Abonare newsletter Pro Gains";
                                $mail->Body = $mesaj;
                                $mail->AltBody = $mesaj;
                                $mail->send();
                            } catch (Exception $e) {
                                header("Location: index.php?abonare-nl=error-q");
                                exit();
                            }
                                header("Location: index.php?abonare-nl=succes");
                                exit();
                        } else {
                            header("Location: index.php?abonare-nl=error-q");
                            exit();
                        }
                    }
                } else {
                    header("Location: index.php?abonare-nl=error-q");
                    exit();
                }
            }
        }
    }
