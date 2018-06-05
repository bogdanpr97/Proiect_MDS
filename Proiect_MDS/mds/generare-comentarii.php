<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST["titlu"]) || !isset($_POST["pn"]) || !is_numeric($_POST["pn"])) {
            echo '<h2>Pagina ceruta nu exista!</h2>';
        } else {
            $titluAux = $conn->real_escape_string($_POST["titlu"]);
            $titlu = str_replace("%20", " ", $titluAux);
            $pn = intval($conn->real_escape_string($_POST["pn"]));
            $sqlID = "select a_id from articole where a_titlu = ?;";
            if($stmt = $conn->prepare($sqlID)) {
                $stmt->bind_param("s", $titlu);
                $stmt->execute();
                $resID = $stmt->get_result();
                $stmt->close();
                $row = $resID->fetch_assoc();
                $resID->close();
                $sqlComentarii = "select * from comentarii_articole where a_id = " . $row['a_id'] . ";";
                $comentarii = $conn->query($sqlComentarii);
                if(!$comentarii) {
                    echo '<p style="font-size: 22px;">A fost intampinata o problema cu afisarea comentariilor!</p>';
                    error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                } else {
                    if($comentarii->num_rows == 0) {
                        echo '<p style="font-size: 22px;">0 comentarii</p>';
                    } else {
                        $total_c = $comentarii->num_rows;
                        $total_p = ceil($total_c / 5);
                        if($pn > $total_p) {
                            echo '<h2>Pagina ceruta nu exista!</h2>';
                        } else {
                            $sqlComPag = "select a_id, c_id, u_id, c_text, c_data from comentarii_articole where a_id = " . $row['a_id'] . " order by c_id desc limit " . 5*($pn-1) . ", 5;";
                            $resP = $conn->query($sqlComPag);
                            if(!$resP) {
                                    echo '<p style="font-size: 22px;">A fost intampinata o problema cu afisarea comentariilor!</p>';
                                    error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            } else {
                                if($pn == 1) {
                                echo '<p style="font-size: 22px;">Comentarii(' . $total_c . '):</p>';
                                while($lineC = $resP->fetch_assoc()) {
                                    $sqlLikeComentarii = "select count(*) as total from pareri_comentarii_articole where c_id = " . $lineC['c_id'] . " and parere = 'l';";
                                    $sqlDislikeComentarii = "select count(*) as total from pareri_comentarii_articole where c_id = " . $lineC['c_id'] . " and parere = 'd';";
                                    $sqlUserName = "select u_username, if(img_profil is null, 'default.jpg', img_profil) as img from utilizatori where u_id = " . $lineC['u_id'] . ';';
                                    $resUserName = $conn->query($sqlUserName);
                                    $resLike = $conn->query($sqlLikeComentarii);
                                    $resDislike = $conn->query($sqlDislikeComentarii);
                                    $rowUserName = $resUserName->fetch_assoc();
                                    $rowLike = $resLike->fetch_assoc();
                                    $rowDislike = $resDislike->fetch_assoc();
                                    if(isset($_SESSION['uid'])) {
                                        $sqlParere = "select parere from pareri_comentarii_articole where u_id = " . $_SESSION['uid'] . " and c_id = " . $lineC['c_id'] . ";";
                                        $resultParere = $conn->query($sqlParere);
                                        if($resultParere->num_rows == 1) {
                                            $rowParere = $resultParere->fetch_assoc();
                                            if($rowParere['parere'] == 'l') {
                                                echo '<div class="comentariu-box">
                                                        <section>
                                                            <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span  style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                                <p>' . nl2br($lineC['c_text']) . '</p>
                                                                <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" class="pressed-c" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                                if($lineC['u_id'] == $_SESSION['uid']) {
                                                                    echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                          <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                                } else {
                                                                    echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                                }
                                                        echo '</section>
                                                        </div>';
                                            } else {
                                                echo '<div class="comentariu-box">
                                                        <section>
                                                            <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                                <p>' . nl2br($lineC['c_text']) . '</p>
                                                                <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" class="pressed-c" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                                if($lineC['u_id'] == $_SESSION['uid']) {
                                                                    echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                          <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                                } else {
                                                                    echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                                }
                                                        echo '</section>
                                                        </div>';
                                            }
                                        } else {
                                            echo '<div class="comentariu-box">
                                                    <section>
                                                        <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                            <p>' . nl2br($lineC['c_text']) . '</p>
                                                            <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                            if($lineC['u_id'] == $_SESSION['uid']) {
                                                                echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                      <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                            } else {
                                                                echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                            }
                                                    echo '</section>
                                                    </div>';
                                        }
                                    } else {
                                        echo '<div class="comentariu-box">
                                                <section>
                                                    <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                        <p>' . nl2br($lineC['c_text']) . '</p>
                                                        <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'] . '</span></p>
                                                </section>
                                                </div>';
                                    }
                                }
                                echo '<div class="pagination-box">';
                                if($total_p >= 2) {
                                   echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                }
                                echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                echo '<select name="pag" id="select_pagC" style="margin-left: 1%; display: inline;" onChange = "select_C()">';
                                for($i = 1; $i <= $total_p; $i++) {
                                    if($i == $pn) {
                                        echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                    } else {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                }
                                echo '</select>';
                                echo '</div>';
                            } else {
                                while($lineC = $resP->fetch_assoc()) {
                                    $sqlLikeComentarii = "select count(*) as total from pareri_comentarii_articole where c_id = " . $lineC['c_id'] . " and parere = 'l';";
                                    $sqlDislikeComentarii = "select count(*) as total from pareri_comentarii_articole where c_id = " . $lineC['c_id'] . " and parere = 'd';";
                                    $sqlUserName = "select u_username, if(img_profil is null, 'default.jpg', img_profil) as img from utilizatori where u_id = " . $lineC['u_id'] . ';';
                                    $resUserName = $conn->query($sqlUserName);
                                    $resLike = $conn->query($sqlLikeComentarii);
                                    $resDislike = $conn->query($sqlDislikeComentarii);
                                    $rowLike = $resLike->fetch_assoc();
                                    $rowDislike = $resDislike->fetch_assoc();
                                    $rowUserName = $resUserName->fetch_assoc();
                                    if(isset($_SESSION['uid'])) {
                                        $sqlParere = "select parere from pareri_comentarii_articole where u_id = " . $_SESSION['uid'] . " and c_id = " . $lineC['c_id'] . ";";
                                        $resultParere = $conn->query($sqlParere);
                                        if($resultParere->num_rows == 1) {
                                            $rowParere = $resultParere->fetch_assoc();
                                            if($rowParere['parere'] == 'l') {
                                                echo '<div class="comentariu-box">
                                                        <section>
                                                            <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                                <p>' . nl2br($lineC['c_text']) . '</p>
                                                                <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" class="pressed-c" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                                if($lineC['u_id'] == $_SESSION['uid']) {
                                                                    echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                          <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                                } else {
                                                                    echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                                }
                                                        echo '</section>
                                                        </div>';
                                            } else {
                                                echo '<div class="comentariu-box">
                                                        <section>
                                                            <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                                <p>' . nl2br($lineC['c_text']) . '</p>
                                                                <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" class="pressed-c" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                                if($lineC['u_id'] == $_SESSION['uid']) {
                                                                    echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                          <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                                } else {
                                                                    echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                                }
                                                        echo '</section>
                                                        </div>';
                                            }
                                        } else {
                                            echo '<div class="comentariu-box">
                                                    <section>
                                                        <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                            <p>' . nl2br($lineC['c_text']) . '</p>
                                                            <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'];
                                                            if($lineC['u_id'] == $_SESSION['uid']) {
                                                                echo '</span><span class="sterge-span" c_id="' . $lineC['c_id'] . '" onClick="sterge_comentariu(this)">Sterge</span>
                                                                      <span class="editeaza-span" c_id="' . $lineC['c_id'] . '" onClick="editeaza_comentariu(this)">Editeaza</span></p>';
                                                            } else {
                                                                echo '</span><span class="raporteaza-span" onClick="raportare_comentariu(this)">Raporteaza comentariul</span></p>';
                                                            }
                                                    echo '</section>
                                                    </div>';
                                        }
                                    } else {
                                        echo '<div class="comentariu-box">
                                                <section>
                                                    <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><span style="font-size: 18px;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></span><span class="data_comm" style="margin-left: 5%; font-size: 12px;">' . $lineC['c_data'] . '</span>
                                                        <p>' . nl2br($lineC['c_text']) . '</p>
                                                        <p c_id="' . $lineC['c_id'] . '"><span><img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-up-com.png" style="position: relative; top: 10px;" val="l" onClick="parere_comentariu_articol(this)"> ' . $rowLike['total'] . ' <img c_id="' . $lineC['c_id'] . '" src="img-site/thumbs-down-com.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_comentariu_articol(this)"> ' . $rowDislike['total'] . '</span></p>
                                                </section>
                                                </div>';
                                    }
                                }
                                echo '<div class="pagination-box">';
                                if($total_p > $pn) {
                                    echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                    echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                } else if($total_p == $pn) {
                                    echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                }
                                echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                echo '<select name="pag" id="select_pagC" style="margin-left: 1%; display: inline;" onChange = "select_C()">';
                                for($i = 1; $i <= $total_p; $i++) {
                                    if($i == $pn) {
                                        echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                    } else {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                }
                                echo '</select>';
                                echo '</div>';
                            }
                        }
                    }
                }
            }
        } else {
                echo '<p style="font-size: 22px;">A fost intampinata o problema cu afisarea comentariilor!</p>';
                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
            }
      }
  }
