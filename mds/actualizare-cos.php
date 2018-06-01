<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $output = json_encode(
                    array(
                        'text' => 'Cererea trebuie sa fie de tip Ajax!'
                    ));
            exit($output);
        } else {
            if(isset($_POST["produs_cod"]) && isset($_POST['produs_cantitate'])) {
                foreach($_POST as $key => $value) {
                    $produs[$key] = filter_var($value, FILTER_SANITIZE_STRING);
                }
                $sql = "select produs_nume, produs_pret from produse where produs_cod = ? limit 1";
                if($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('s', $produs['produs_cod']);
                    $stmt->execute();
                    $stmt->bind_result($produs_nume, $produs_pret);
                    while($stmt->fetch()) {
                        $produs["produs_nume"] = $produs_nume;
                        $produs["produs_pret"] = $produs_pret;
                        if(isset($_SESSION["produse"])) {
                            if(isset($_SESSION["produse"][$produs['produs_cod']])) {
                                if($_POST["produs_cantitate"] == 1 && !isset($_POST['s'])) {
                                    $_SESSION["produse"][$produs['produs_cod']]["produs_cantitate"] = $_SESSION["produse"][$produs['produs_cod']]["produs_cantitate"] + $_POST["produs_cantitate"];
                                } else {
                                    $_SESSION["produse"][$produs['produs_cod']]["produs_cantitate"] = $_POST["produs_cantitate"];
                                }
                            } else {
                                $_SESSION["produse"][$produs['produs_cod']] = $produs;
                            }
                        } else {
                            $_SESSION["produse"][$produs['produs_cod']] = $produs;
                        }
                    }
                    $stmt->close();
                    $total_produse = count($_SESSION["produse"]);
                    $output = json_encode(
                            array(
                                'text' => 'Produsul a fost adaugat'
                            ));
                    exit($output);
                } else {
                    $output = json_encode(
                            array(
                                'text' => 'A fost intampinata o problema, ne cerem scuze!'
                            ));
                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    exit($output);
                }
             } else {
                 $output = json_encode(
                         array(
                             'text' => 'Cererea nu este valida'
                         ));
                 exit($output);
             }
        }
    }
