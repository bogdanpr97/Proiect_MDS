<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST["tip"]) || !isset($_POST["pn"]) || !is_numeric($_POST["pn"])) {
            echo '<h2>Pagina ceruta nu exista!</h2>';
        } else {
            $tip = $conn->real_escape_string($_POST["tip"]);
            $pn = intval($conn->real_escape_string($_POST["pn"]));
            if($tip == "primite") {
                $sql = "select * from mesaje_utilizatori where m_destinatar_uid = " . $_SESSION['uid'] . ";";
                if($result = $conn->query($sql)) {
                        if($result->num_rows == 0) {
                            echo '<h3">0 mesaje primite</h3>';
                        } else {
                            $row = $result->fetch_assoc();
                            $total_c = $result->num_rows;
                            $total_p = ceil($total_c / 6);
                            if($pn > $total_p) {
                                echo '<h2>Pagina ceruta nu exista!</h2>';
                            } else {
                                $sqlMesajPag = "select * from mesaje_utilizatori where m_destinatar_uid = " . $_SESSION['uid'] . " order by m_data desc limit " . 6*($pn-1) . ", 6;";
                                $resP = $conn->query($sqlMesajPag);
                                if(!$resP) {
                                        echo '<h3>A fost intampinata o problema cu afisarea mesajelor primite!</h3';
                                        error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                } else {
                                    echo '<h3>Mesaje primite(' . $total_c . '):</h3>';
                                    echo '<div class="mesaje-big-box">'; // mesaje-big-box
                                    while($lineC = $resP->fetch_assoc()) {
                                        $sqlUserName = "select u_username, if(img_profil is null, 'default.jpg', img_profil) as img from utilizatori where u_id = " . $row['m_expeditor_uid'] . ';';
                                        $resUserName = $conn->query($sqlUserName);
                                        $rowUserName = $resUserName->fetch_assoc();
                                        echo '<div class="mesaj-box">
                                                <section>
                                                    <p>De la: <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></p>
                                                    <p>Subiect: ' . $lineC['m_titlu'] . '</p>
                                                    <p>Mesaj: ' . nl2br($lineC['m_text']) . '</p>
                                                    <p>Data: ' . $lineC['m_data'] . '</p>
                                                    <span class="span-raspunde" username="' . $rowUserName['u_username'] . '" subiect="RE: ' . $lineC['m_titlu'] . '" onClick="raspunde_mesaj(this)">Raspunde</span>
                                                </section>
                                              </div>';
                                    }
                                    echo '</div>'; // mesaje-big-box
                                    if($pn == 1) {
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
                                    } else {
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
                                    }
                                    echo '</div>';
                                }
                            }
                        }
                    } else {
                            echo '<h3>A fost intampinata o problema cu afisarea mesajelor primite!</h3>';
                            error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
            } else if($tip == "trimise") {
                $sql = "select * from mesaje_utilizatori where m_expeditor_uid = " . $_SESSION['uid'] . ";";
                if($result = $conn->query($sql)) {
                        if($result->num_rows == 0) {
                            echo '<h3">0 mesaje trimise</h3>';
                        } else {
                            $row = $result->fetch_assoc();
                            $total_c = $result->num_rows;
                            $total_p = ceil($total_c / 6);
                            if($pn > $total_p) {
                                echo '<h2>Pagina ceruta nu exista!</h2>';
                            } else {
                                $sqlMesajPag = "select * from mesaje_utilizatori where m_expeditor_uid = " . $_SESSION['uid'] . " order by m_data desc limit " . 6*($pn-1) . ", 6;";
                                $resP = $conn->query($sqlMesajPag);
                                if(!$resP) {
                                        echo '<h3>A fost intampinata o problema cu afisarea mesajelor trimise!</h3';
                                        error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                } else {
                                    echo '<h3>Mesaje trimise(' . $total_c . '):</h3>';
                                    echo '<div class="mesaje-big-box">'; // mesaje-big-box
                                    while($lineC = $resP->fetch_assoc()) {
                                        $sqlUserName = "select u_username, if(img_profil is null, 'default.jpg', img_profil) as img from utilizatori where u_id = " . $row['m_destinatar_uid'] . ';';
                                        $resUserName = $conn->query($sqlUserName);
                                        $rowUserName = $resUserName->fetch_assoc();
                                        echo '<div class="mesaj-box">
                                                <section>
                                                    <p>Catre: <img class="img-profil" src="img-profil-utilizatori/' . $rowUserName['img'] . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"><a href="profil.php?username=' . $rowUserName['u_username'] . '">' . $rowUserName['u_username'] . '</a></p>
                                                    <p>Subiect: ' . $lineC['m_titlu'] . '</p>
                                                    <p>Mesaj: ' . nl2br($lineC['m_text']) . '</p>
                                                    <p>Data: ' . $lineC['m_data'] . '</p>
                                                </section>
                                              </div>';
                                    }
                                    echo '</div>'; // mesaje-big-box
                                    if($pn == 1) {
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
                                    } else {
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
                                    }
                                    echo '</div>';
                                }
                            }
                        }
                    } else {
                            echo '<h3>A fost intampinata o problema cu afisarea mesajelor trimise!</h3>';
                            error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
             }
        }
    }
