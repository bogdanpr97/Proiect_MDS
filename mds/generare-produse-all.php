<?php
    require_once '../../../dbC.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST["pn"]) || (isset($_POST['pn']) && !is_numeric($_POST["pn"])) || (is_numeric($_POST["pn"] && intval($_POST["pn"]) < 1))) {
            echo '<h2>Pagina ceruta nu exista!</h2>';
        } else {
            if(!isset($_POST["categorie"]) && !isset($_POST["producator"])) {
                if(!isset($_POST["search"])) {
                    $pn = intval($conn->real_escape_string($_POST["pn"]));
                    $sql = "select * from produse;";
                    $result = $conn->query($sql);
                    if(!$result) {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    } else {
                        if($result->num_rows == 0) {
                            echo '<h2 style="margin-left: 2%">0 produse</h2>';
                        } else {
                            $total_prod = $result->num_rows;
                            $total_p = ceil($total_prod / 12);
                            if($pn > $total_p) {
                                echo '<h2>Pagina ceruta nu exista!</h2>';
                            } else {
                                if((int)$pn == $pn) {
                                    $sqlProdPag = "select * from produse order by produs_id desc limit ? , 12;";
                                    if($stmt = $conn->prepare($sqlProdPag)) {
                                        $pn2 = 12*($pn-1);
                                        $stmt->bind_param("i", $pn2);
                                        $stmt->execute();
                                        $resProd = $stmt->get_result();
                                        if(!$resProd) {
                                            echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                            error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                        } else {
                                                $stmt->close();
                                                while($row = $resProd->fetch_assoc()) {
                                                    $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                    $resImg = $conn->query($sqlImg);
                                                    if(!$resImg || $resImg->num_rows == 0) {
                                                        echo '<div class="produs-box">';
                                                        echo    '<div class="wrapper-prod-box">';
                                                        echo        '<div class="box-imag-prod">';
                                                        echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                            <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                     </div>
                                                                     <div class="box-body-prod">
                                                                        <div class="titlu-prod">
                                                                            <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                        </div>
                                                                        <div class="descriere-prod">
                                                                            <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                        </div>
                                                                        <div class="pret-prod">
                                                                            <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                        </div>
                                                                        <div class="actiune-prod">
                                                                            <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                <span>Adauga in cos</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';

                                                    } else {
                                                        $row2 = $resImg->fetch_assoc();
                                                        echo '<div class="produs-box">';
                                                        echo    '<div class="wrapper-prod-box">';
                                                        echo        '<div class="box-imag-prod">';
                                                        echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                            <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                     </div>
                                                                     <div class="box-body-prod">
                                                                        <div class="titlu-prod">
                                                                            <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                        </div>
                                                                        <div class="descriere-prod">
                                                                            <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                        </div>
                                                                        <div class="pret-prod">
                                                                            <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                        </div>
                                                                        <div class="actiune-prod">
                                                                            <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                <span>Adauga in cos</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                }
                                                echo '<div class="clear"></div>';
                                                if($pn == 1) {
                                                    echo '<div class="pagination-box">';
                                                    if($total_p >= 2) {
                                                       echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                    }
                                                    echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                    echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                    echo '<div class="pagination-box">';
                                                    if($total_p > $pn) {
                                                        echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                        echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                    } else if($total_p == $pn) {
                                                        echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                    }
                                                    echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                    echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                } else {
                                    echo '<h2>Pagina ceruta nu exista!</h2>';
                                }
                            }
                        }
                    }
                } else {
                    $pn = intval($conn->real_escape_string($_POST["pn"]));
                    $sql = "select * from produse p join categorii_produse c on(p.produs_categorie_id = c.categorie_id) join producatori_produse pp on(p.produs_producator_id = pp.producator_id)
                    where producator_nume like ? or categorie_nume like ? or produs_nume like ? or produs_descriere like ? or produs_prezentare like ? ;";
                    $search = filter_var(trim($_POST["search"], FILTER_SANITIZE_STRING));
                    $search = $conn->real_escape_string($search);
                    if($stmt = $conn->prepare($sql)) {
                        $search = "%".$search."%";
                        $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if(!$result) {
                            echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                            error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        } else {
                            if($result->num_rows == 0) {
                                echo '<p style="margin-top: 3%; margin-left: 2%;">Fara rezultate!</p>';
                            } else {
                                $stmt->close();
                                $total_prod = $result->num_rows;
       						    $total_p = ceil($total_prod / 12);
                                if((int)$pn == $pn) {
                                    $sql2 = "select * from produse p join categorii_produse c on(p.produs_categorie_id = c.categorie_id) join producatori_produse pp on(p.produs_producator_id = pp.producator_id) where producator_nume like ? or categorie_nume like ? or produs_nume like ? or produs_descriere like ? or produs_prezentare like ? order by produs_id desc limit ? , 12;";
                                    if($stmt2 = $conn->prepare($sql2)) {
                                        $pn2 = 12*($pn-1);
                                        $stmt2->bind_param("sssssi", $search, $search, $search, $search, $search, $pn2);
                                        $stmt2->execute();
                                        $resProd = $stmt2->get_result();
                                        if(!$resProd) {
                                            echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                            error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                        } else {
                                            $stmt2->close();
                                            echo '<p style="margin-left: 2%;">' . $total_prod . ' rezultate! </p>';
                                            while($row = $resProd->fetch_assoc()) {
                                                $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                $resImg = $conn->query($sqlImg);
                                                if(!$resImg || $resImg->num_rows == 0) {
                                                    echo '<div class="produs-box">';
                                                    echo    '<div class="wrapper-prod-box">';
                                                    echo        '<div class="box-imag-prod">';
                                                    echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                        <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                 </div>
                                                                 <div class="box-body-prod">
                                                                    <div class="titlu-prod">
                                                                        <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                    </div>
                                                                    <div class="descriere-prod">
                                                                        <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                    </div>
                                                                    <div class="pret-prod">
                                                                        <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                    </div>
                                                                    <div class="actiune-prod">
                                                                        <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                            <span>Adauga in cos</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';

                                                } else {
                                                    $row2 = $resImg->fetch_assoc();
                                                    echo '<div class="produs-box">';
                                                    echo    '<div class="wrapper-prod-box">';
                                                    echo        '<div class="box-imag-prod">';
                                                    echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                        <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                 </div>
                                                                 <div class="box-body-prod">
                                                                    <div class="titlu-prod">
                                                                        <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                    </div>
                                                                    <div class="descriere-prod">
                                                                        <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                    </div>
                                                                    <div class="pret-prod">
                                                                        <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                    </div>
                                                                    <div class="actiune-prod">
                                                                        <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                            <span>Adauga in cos</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                }
                                            }
                                            echo '<div class="clear"></div>';
                                            if($pn == 1) {
                                                echo '<div class="pagination-box">';
                                                if($total_p >= 2) {
                                                   echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                }
                                                echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                echo '<div class="pagination-box">';
                                                if($total_p > $pn) {
                                                    echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                    echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                } else if($total_p == $pn) {
                                                    echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                }
                                                echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                    } else {
                                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                        error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                    }
                                } else {
                                    echo '<h2>Pagina ceruta nu exista!</h2>';
                                }
                            }
                        }
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
                }
            } else if (isset($_POST["categorie"]) && !isset($_POST["producator"])) {
                if(!isset($_POST["search"])) {
                    $categ = str_replace("%20", " ", $_POST["categorie"]);
                    $categ = filter_var(trim($categ), FILTER_SANITIZE_STRING);
                    $categ = $conn->real_escape_string($categ);
                    $sqlCategorie = "select categorie_id from categorii_produse where categorie_nume = ? ;";
                    if($stmtCategorie = $conn->prepare($sqlCategorie)) {
                        $stmtCategorie->bind_param("s", $categ);
                        $stmtCategorie->execute();
                        $resCategorie = $stmtCategorie->get_result();
                        $stmtCategorie->close();
                        if($resCategorie->num_rows == 1) {
                            $rowCateg = $resCategorie->fetch_assoc();
                            $categorie = $rowCateg["categorie_id"];
                            $pn = intval($conn->real_escape_string($_POST["pn"]));
                            $sql = "select * from produse where produs_categorie_id = " . $categorie . ";";
                            $result = $conn->query($sql);
                            if(!$result) {
                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            } else {
                                if($result->num_rows == 0) {
                                    echo '<h2 style="margin-left: 2%">0 produse</h2>';
                                } else {
                                    $total_prod = $result->num_rows;
                                    $total_p = ceil($total_prod / 12);
                                    if($pn > $total_p) {
                                        echo '<h2>Pagina ceruta nu exista!</h2>';
                                    } else {
                                        if((int)$pn == $pn) {
                                            $sqlProdPag = "select * from produse where produs_categorie_id = " . $categorie . " order by produs_id desc limit ? , 12;";
                                            if($stmt = $conn->prepare($sqlProdPag)) {
                                                $pn2 = 12*($pn-1);
                                                $stmt->bind_param("i", $pn2);
                                                $stmt->execute();
                                                $resProd = $stmt->get_result();
                                                if(!$resProd) {
                                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                } else {
                                                        $stmt->close();
                                                        while($row = $resProd->fetch_assoc()) {
                                                            $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                            $resImg = $conn->query($sqlImg);
                                                            if(!$resImg || $resImg->num_rows == 0) {
                                                                echo '<div class="produs-box">';
                                                                echo    '<div class="wrapper-prod-box">';
                                                                echo        '<div class="box-imag-prod">';
                                                                echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                    <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                             </div>
                                                                             <div class="box-body-prod">
                                                                                <div class="titlu-prod">
                                                                                    <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                                </div>
                                                                                <div class="descriere-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                                </div>
                                                                                <div class="pret-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                                </div>
                                                                                <div class="actiune-prod">
                                                                                    <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                        <span>Adauga in cos</span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';

                                                            } else {
                                                                $row2 = $resImg->fetch_assoc();
                                                                echo '<div class="produs-box">';
                                                                echo    '<div class="wrapper-prod-box">';
                                                                echo        '<div class="box-imag-prod">';
                                                                echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                    <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                             </div>
                                                                             <div class="box-body-prod">
                                                                                <div class="titlu-prod">
                                                                                    <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                                </div>
                                                                                <div class="descriere-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                                </div>
                                                                                <div class="pret-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                                </div>
                                                                                <div class="actiune-prod">
                                                                                    <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                        <span>Adauga in cos</span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                            }
                                                        }
                                                        echo '<div class="clear"></div>';
                                                        if($pn == 1) {
                                                            echo '<div class="pagination-box">';
                                                            if($total_p >= 2) {
                                                               echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                            }
                                                            echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                            echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                            echo '<div class="pagination-box">';
                                                            if($total_p > $pn) {
                                                                echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                                echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                            } else if($total_p == $pn) {
                                                                echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                            }
                                                            echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                            echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                        } else {
                                            echo '<h2>Pagina ceruta nu exista!</h2>';
                                        }
                                    }
                                }
                            }
                        } else {
                            echo '<h2>Pagina ceruta nu exista!</h2>';
                        }
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $stmtCategorie->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
                } else {
                    $categ = str_replace("%20", " ", $_POST["categorie"]);
                    $categ = filter_var(trim($categ), FILTER_SANITIZE_STRING);
                    $categ = $conn->real_escape_string($categ);
                    $sqlCategorie = "select categorie_id from categorii_produse where categorie_nume = ? ;";
                    if($stmtCategorie = $conn->prepare($sqlCategorie)) {
                        $stmtCategorie->bind_param("s", $categ);
                        $stmtCategorie->execute();
                        $resCategorie = $stmtCategorie->get_result();
                        $stmtCategorie->close();
                        if($resCategorie->num_rows == 1) {
                            $rowCateg = $resCategorie->fetch_assoc();
                            $categorie = intval($rowCateg["categorie_id"]);
                            $pn = intval($conn->real_escape_string($_POST["pn"]));
                            $sql = "select * from produse where ( produs_nume like ? or produs_descriere like ? or produs_prezentare like ? ) and produs_categorie_id = ? ;";
                            $search = filter_var(trim($_POST["search"], FILTER_SANITIZE_STRING));
                            $search = $conn->real_escape_string($search);
                            if($stmt = $conn->prepare($sql)) {
                                $search = "%".$search."%";
                                $stmt->bind_param("sssi", $search, $search, $search, $categorie);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if(!$result) {
                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                } else {
                                    if($result->num_rows == 0) {
                                        echo '<p style="margin-top: 3%; margin-left: 2%;">Fara rezultate!</p>';
                                    } else {
                                        $stmt->close();
                                        $total_prod = $result->num_rows;
               						    $total_p = ceil($total_prod / 12);
                                        if((int)$pn == $pn) {
                                            $sql2 = "select * from produse where ( produs_nume like ? or produs_descriere like ? or produs_prezentare like ? ) and produs_categorie_id = ? order by produs_id desc limit ? , 12;";
                                            if($stmt2 = $conn->prepare($sql2)) {
                                                $pn2 = 12*($pn-1);
                                                $stmt2->bind_param("sssii", $search, $search, $search, $categorie, $pn2);
                                                $stmt2->execute();
                                                $resProd = $stmt2->get_result();
                                                if(!$resProd) {
                                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                    error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                } else {
                                                    $stmt2->close();
                                                    echo '<p style="margin-left: 2%;">' . $total_prod . ' rezultate! </p>';
                                                    while($row = $resProd->fetch_assoc()) {
                                                        $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                        $resImg = $conn->query($sqlImg);
                                                        if(!$resImg || $resImg->num_rows == 0) {
                                                            echo '<div class="produs-box">';
                                                            echo    '<div class="wrapper-prod-box">';
                                                            echo        '<div class="box-imag-prod">';
                                                            echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                         </div>
                                                                         <div class="box-body-prod">
                                                                            <div class="titlu-prod">
                                                                                <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                            </div>
                                                                            <div class="descriere-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                            </div>
                                                                            <div class="pret-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                            </div>
                                                                            <div class="actiune-prod">
                                                                                <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                    <span>Adauga in cos</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';

                                                        } else {
                                                            $row2 = $resImg->fetch_assoc();
                                                            echo '<div class="produs-box">';
                                                            echo    '<div class="wrapper-prod-box">';
                                                            echo        '<div class="box-imag-prod">';
                                                            echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                         </div>
                                                                         <div class="box-body-prod">
                                                                            <div class="titlu-prod">
                                                                                <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                            </div>
                                                                            <div class="descriere-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                            </div>
                                                                            <div class="pret-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                            </div>
                                                                            <div class="actiune-prod">
                                                                                <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                    <span>Adauga in cos</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                        }
                                                    }
                                                    echo '<div class="clear"></div>';
                                                    if($pn == 1) {
                                                        echo '<div class="pagination-box">';
                                                        if($total_p >= 2) {
                                                           echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                        }
                                                        echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                        echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                        echo '<div class="pagination-box">';
                                                        if($total_p > $pn) {
                                                            echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                            echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                        } else if($total_p == $pn) {
                                                            echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                        }
                                                        echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                        echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                            } else {
                                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                            }
                                        } else {
                                            echo '<h2>Pagina ceruta nu exista!</h2>';
                                        }
                                    }
                                }
                            } else {
                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            }
                        } else {
                            echo '<h2>Pagina ceruta nu exista!</h2>';
                        }
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $stmtCategorie->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
                }
            } else if (!isset($_POST["categorie"]) && isset($_POST["producator"])) {
                if(!isset($_POST["search"])) {
                    $prod = str_replace("%20", " ", $_POST["producator"]);
                    $prod = filter_var(trim($prod), FILTER_SANITIZE_STRING);
                    $prod = $conn->real_escape_string($prod);
                    $sqlProducator = "select producator_id from producatori_produse where producator_nume = ? ;";
                    if($stmtProducator = $conn->prepare($sqlProducator)) {
                        $stmtProducator->bind_param("s", $prod);
                        $stmtProducator->execute();
                        $resProducator = $stmtProducator->get_result();
                        if($resProducator->num_rows == 1) {
                            $stmtProducator->close();
                            $rowProd = $resProducator->fetch_assoc();
                            $producator = $rowProd["producator_id"];
                            $pn = intval($conn->real_escape_string($_POST["pn"]));
                            $sql = "select * from produse where produs_producator_id = " . $producator . ";";
                            $result = $conn->query($sql);
                            if(!$result) {
                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            } else {
                                if($result->num_rows == 0) {
                                    echo '<h2 style="margin-left: 2%">0 produse</h2>';
                                } else {
                                    $total_prod = $result->num_rows;
                                    $total_p = ceil($total_prod / 12);
                                    if($pn > $total_p) {
                                        echo '<h2>Pagina ceruta nu exista!</h2>';
                                    } else {
                                        if((int)$pn == $pn) {
                                            $sqlProdPag = "select * from produse where produs_producator_id = " . $producator . " order by produs_id desc limit ? , 12;";
                                            if($stmt = $conn->prepare($sqlProdPag)) {
                                                $pn2 = 12*($pn-1);
                                                $stmt->bind_param("i", $pn2);
                                                $stmt->execute();
                                                $resProd = $stmt->get_result();
                                                if(!$resProd) {
                                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                } else {
                                                        $stmt->close();
                                                        while($row = $resProd->fetch_assoc()) {
                                                            $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                            $resImg = $conn->query($sqlImg);
                                                            if(!$resImg || $resImg->num_rows == 0) {
                                                                echo '<div class="produs-box">';
                                                                echo    '<div class="wrapper-prod-box">';
                                                                echo        '<div class="box-imag-prod">';
                                                                echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                    <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                             </div>
                                                                             <div class="box-body-prod">
                                                                                <div class="titlu-prod">
                                                                                    <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                                </div>
                                                                                <div class="descriere-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                                </div>
                                                                                <div class="pret-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                                </div>
                                                                                <div class="actiune-prod">
                                                                                    <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                        <span>Adauga in cos</span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';

                                                            } else {
                                                                $row2 = $resImg->fetch_assoc();
                                                                echo '<div class="produs-box">';
                                                                echo    '<div class="wrapper-prod-box">';
                                                                echo        '<div class="box-imag-prod">';
                                                                echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                    <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                             </div>
                                                                             <div class="box-body-prod">
                                                                                <div class="titlu-prod">
                                                                                    <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                                </div>
                                                                                <div class="descriere-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                                </div>
                                                                                <div class="pret-prod">
                                                                                    <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                                </div>
                                                                                <div class="actiune-prod">
                                                                                    <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                        <span>Adauga in cos</span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                            }
                                                        }
                                                        echo '<div class="clear"></div>';
                                                        if($pn == 1) {
                                                            echo '<div class="pagination-box">';
                                                            if($total_p >= 2) {
                                                               echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                            }
                                                            echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                            echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                            echo '<div class="pagination-box">';
                                                            if($total_p > $pn) {
                                                                echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                                echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                            } else if($total_p == $pn) {
                                                                echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                            }
                                                            echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                            echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                        } else {
                                            echo '<h2>Pagina ceruta nu exista!</h2>';
                                        }
                                    }
                                }
                            }
                        } else {
                            echo '<h2>Pagina ceruta nu exista!</h2>';
                        }
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $stmtProducator->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
                } else {
                    $prod = str_replace("%20", " ", $_POST["producator"]);
                    $prod = filter_var(trim($prod), FILTER_SANITIZE_STRING);
                    $prod = $conn->real_escape_string($prod);
                    $sqlProducator = "select producator_id from producatori_produse where producator_nume = ? ;";
                    if($stmtProducator = $conn->prepare($sqlProducator)) {
                        $stmtProducator->bind_param("s", $prod);
                        $stmtProducator->execute();
                        $resProducator = $stmtProducator->get_result();
                        if($resProducator->num_rows == 1) {
                            $stmtProducator->close();
                            $rowProd = $resProducator->fetch_assoc();
                            $producator = intval($rowProd["producator_id"]);
                            $pn = intval($conn->real_escape_string($_POST["pn"]));
                            $sql = "select * from produse where ( produs_nume like ? or produs_descriere like ? or produs_prezentare like ? ) and produs_producator_id = ? ;";
                            $search = filter_var(trim($_POST["search"], FILTER_SANITIZE_STRING));
                            $search = $conn->real_escape_string($search);
                            if($stmt = $conn->prepare($sql)) {
                                $search = "%".$search."%";
                                $stmt->bind_param("sssi", $search, $search, $search, $producator);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if(!$result) {
                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                } else {
                                    if($result->num_rows == 0) {
                                        echo '<p style="margin-top: 3%; margin-left: 2%;">Fara rezultate!</p>';
                                    } else {
                                        $stmt->close();
                                        $total_prod = $result->num_rows;
               						    $total_p = ceil($total_prod / 12);
                                        if((int)$pn == $pn) {
                                            $sql2 = "select * from produse where ( produs_nume like ? or produs_descriere like ? or produs_prezentare like ? ) and produs_producator_id = ? order by produs_id desc limit ? , 12;";
                                            if($stmt2 = $conn->prepare($sql2)) {
                                                $pn2 = 12*($pn-1);
                                                $stmt2->bind_param("sssii", $search, $search, $search, $producator, $pn2);
                                                $stmt2->execute();
                                                $resProd = $stmt2->get_result();
                                                if(!$resProd) {
                                                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                    error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                } else {
                                                    $stmt2->close();
                                                    echo '<p style="margin-left: 2%;">' . $total_prod . ' rezultate! </p>';
                                                    while($row = $resProd->fetch_assoc()) {
                                                        $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                                                        $resImg = $conn->query($sqlImg);
                                                        if(!$resImg || $resImg->num_rows == 0) {
                                                            echo '<div class="produs-box">';
                                                            echo    '<div class="wrapper-prod-box">';
                                                            echo        '<div class="box-imag-prod">';
                                                            echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                <img style="width: 100%; height: 100%;" src="img-produse/default.png" title="' . $row['produs_nume'] . '"></a>
                                                                         </div>
                                                                         <div class="box-body-prod">
                                                                            <div class="titlu-prod">
                                                                                <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                            </div>
                                                                            <div class="descriere-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                            </div>
                                                                            <div class="pret-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                            </div>
                                                                            <div class="actiune-prod">
                                                                                <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                    <span>Adauga in cos</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';

                                                        } else {
                                                            $row2 = $resImg->fetch_assoc();
                                                            echo '<div class="produs-box">';
                                                            echo    '<div class="wrapper-prod-box">';
                                                            echo        '<div class="box-imag-prod">';
                                                            echo            '<a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">
                                                                                <img style="width: 78%; height: 78%;" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                                                         </div>
                                                                         <div class="box-body-prod">
                                                                            <div class="titlu-prod">
                                                                                <a href="produs.php?cod_produs=' . $row['produs_cod'] . '" title="' . $row['produs_nume'] . '">' . $row['produs_nume'] . '</a>
                                                                            </div>
                                                                            <div class="descriere-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_descriere'] . '</p>
                                                                            </div>
                                                                            <div class="pret-prod">
                                                                                <p style="margin : 0; padding : 0;">' .  $row['produs_pret'] . 'RON</p>
                                                                            </div>
                                                                            <div class="actiune-prod">
                                                                                <button id="' . $row["produs_cod"] . '" class="btn-cart" onClick = "adauga_cos(this)">
                                                                                    <span>Adauga in cos</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                        }
                                                    }
                                                    echo '<div class="clear"></div>';
                                                    if($pn == 1) {
                                                        echo '<div class="pagination-box">';
                                                        if($total_p >= 2) {
                                                           echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                        }
                                                        echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
                                                        echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                                        echo '<div class="pagination-box">';
                                                        if($total_p > $pn) {
                                                            echo '<span id="prev_ctrl" style="margin-right: 1.5%;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                            echo '<span id="next_ctrl" style="margin-right: 1.5%;" onClick = "next_f(this)" val="'. ($pn+1) .'">Next</span>';
                                                        } else if($total_p == $pn) {
                                                            echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. ($pn-1) .'">Prev</span>';
                                                        }
                                                        echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
                                                        echo '<select name="pag" id="select_pagP" style="margin-left: 1%; display: inline;" onChange = "select_P()">';
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
                                            } else {
                                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                                error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                            }
                                        } else {
                                            echo '<h2>Pagina ceruta nu exista!</h2>';
                                        }
                                    }
                                }
                            } else {
                                echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            }
                        } else {
                            echo '<h2>Pagina ceruta nu exista!</h2>';
                        }
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
                        error_log("Error: " . $stmtProducator->error . PHP_EOL, 3, "errorLog.txt");
                    }
                }
            }
        }
    }
