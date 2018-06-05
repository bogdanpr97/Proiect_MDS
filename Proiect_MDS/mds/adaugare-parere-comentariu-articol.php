<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST['parere']) || !isset($_POST['id'])) {
            echo '<p>Eroare, reincarcati pagina.</p>';
        } else {
            $cId = intval($conn->real_escape_string(filter_var(trim($_POST["id"]), FILTER_SANITIZE_STRING)));
            $parere = $conn->real_escape_string(filter_var(trim($_POST["parere"]), FILTER_SANITIZE_STRING));
            $sqlVerificare = "select * from pareri_comentarii_articole where u_id = " . $_SESSION['uid'] . " and c_id = " . $cId . ";";
            $resultVerificare = $conn->query($sqlVerificare);
            if($resultVerificare->num_rows == 1) {
                $rowVerificare = $resultVerificare->fetch_assoc();
                if(($rowVerificare['parere'] == 'l' && $parere == 'l') || ($rowVerificare['parere'] == 'd' && $parere == 'd')) {
                    $sqlDelete = "delete from pareri_comentarii_articole where u_id = " . $_SESSION['uid'] . " and c_id = " . $cId . ";";
                    $conn->query($sqlDelete);
                } else if (($rowVerificare['parere'] == 'l' && $parere == 'd') || ($rowVerificare['parere'] == 'd' && $parere == 'l')) {
                    $sqlUpdate = "update pareri_comentarii_articole set parere = '" . $parere . "' where u_id = " . $_SESSION['uid'] . " and c_id = " . $cId . ";";
                    $conn->query($sqlUpdate);
                }
            } else {
                $sqlInsert = "insert into pareri_comentarii_articole (c_id, u_id, parere) values ( ? , ? , ? );";
                $stmt2 = $conn->prepare($sqlInsert);
                $stmt2->bind_param("iis", $cId, $_SESSION['uid'], $parere);
                $stmt2->execute();
                $stmt2->close();
            }
            $sqlLike = "select count(*) as total from pareri_comentarii_articole where c_id = " . $cId . " and parere = 'l';";
            $sqlDislike = "select count(*) as total from pareri_comentarii_articole where c_id = " . $cId . " and parere = 'd';";
            $rLike = $conn->query($sqlLike);
            $rDislike = $conn->query($sqlDislike);
            $rowLike = $rLike->fetch_assoc();
            $rowDislike = $rDislike->fetch_assoc();
            $resultVerificare = $conn->query($sqlVerificare);
            if($resultVerificare->num_rows == 1) {
                $rowVerificare = $resultVerificare->fetch_assoc();
                if ($rowVerificare['parere'] == 'l') {
                    echo '<img c_id="' . $cId . '" class="pressed-c" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $cId . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                } else {
                    echo '<img c_id="' . $cId . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $cId . '" class="pressed-c" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                }
            } else {
                echo '<img c_id="' . $cId . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $cId . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
            }
        }
    }
