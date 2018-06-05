<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST['parere']) || !isset($_POST['titlu']) || !isset($_POST['data'])) {
            echo '<p>Eroare, reincarcati pagina.</p>';
        } else {
            $titluAux = $conn->real_escape_string(filter_var(trim($_POST["titlu"]), FILTER_SANITIZE_STRING));
            $titlu = str_replace("%20", " ", $titluAux);
            $dataAux = $conn->real_escape_string(filter_var(trim($_POST["data"]), FILTER_SANITIZE_STRING));
            $data = str_replace("%20", " ", $dataAux);
            $parere = $conn->real_escape_string(filter_var(trim($_POST["parere"]), FILTER_SANITIZE_STRING));
            $sqlId = "select a_id from articole where a_titlu = ? and a_data = ? ;";
            $stmt = $conn->prepare($sqlId);
            $stmt->bind_param("ss", $titlu, $data);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $rowId = $result->fetch_assoc();
            $result->close();
            $articolId = intval($rowId['a_id']);
            $sqlVerificare = "select * from pareri_articole where u_id = " . $_SESSION['uid'] . " and a_id = " . $articolId . ";";
            $resultVerificare = $conn->query($sqlVerificare);
            if($resultVerificare->num_rows == 1) {
                $rowVerificare = $resultVerificare->fetch_assoc();
                $resultVerificare->close();
                if(($rowVerificare['parere'] == 'l' && $parere == 'l') || ($rowVerificare['parere'] == 'd' && $parere == 'd')) {
                    $sqlDelete = "delete from pareri_articole where u_id = " . $_SESSION['uid'] . " and a_id = " . $articolId . ";";
                    $conn->query($sqlDelete);
                } else if (($rowVerificare['parere'] == 'l' && $parere == 'd') || ($rowVerificare['parere'] == 'd' && $parere == 'l')) {
                    $sqlUpdate = "update pareri_articole set parere = '" . $parere . "' where u_id = " . $_SESSION['uid'] . " and a_id = " . $articolId . ";";
                    $conn->query($sqlUpdate);
                }
            } else {
                $resultVerificare->close();
                $sqlInsert = "insert into pareri_articole (a_id, u_id, parere) values ( ? , ? , ? );";
                $stmt2 = $conn->prepare($sqlInsert);
                $stmt2->bind_param("iis", $articolId, $_SESSION['uid'], $parere);
                $stmt2->execute();
                $stmt2->close();
            }
            $sqlLike = "select count(*) as total from pareri_articole where a_id = " . $articolId . " and parere = 'l';";
            $sqlDislike = "select count(*) as total from pareri_articole where a_id = " . $articolId . " and parere = 'd';";
            $rLike = $conn->query($sqlLike);
            $rDislike = $conn->query($sqlDislike);
            $rowLike = $rLike->fetch_assoc();
            $rowDislike = $rDislike->fetch_assoc();
            $rLike->close();
            $rDislike->close();
            $resultVerificare = $conn->query($sqlVerificare);
            if($resultVerificare->num_rows == 1) {
                $rowVerificare = $resultVerificare->fetch_assoc();
                $resultVerificare->close();
                if ($rowVerificare['parere'] == 'l') {
                    echo '<img id="pressed" src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'];
                } else {
                    echo '<img src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img id="pressed" src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'];
                }
            } else {
                echo '<img src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'];
            }
        }
    }
