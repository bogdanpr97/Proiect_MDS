<?php
    require_once '../../../dbC.php';
    session_start();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: page-not-found.php");
        exit();
    } else {
        if(!isset($_POST["pn"]) || !is_numeric($_POST["pn"])) {
            echo '<h2>Pagina ceruta nu exista!</h2>';
        } else {
            $pn = intval($conn->real_escape_string($_POST["pn"]));
                $sql = "select * from comenzi where comanda_u_id = " . $_SESSION['uid'] . ";";
                if($result = $conn->query($sql)) {
                        if($result->num_rows == 0) {
                            echo '<h3">0 comenzi</h3>';
                        } else {
                            $row = $result->fetch_assoc();
                            $total_c = $result->num_rows;
                            $total_p = ceil($total_c / 7);
                            if($pn > $total_p) {
                                echo '<h2>Pagina ceruta nu exista!</h2>';
                            } else {
                                $sqlComPag = "select * from comenzi where comanda_u_id = " . $_SESSION['uid'] . " order by comanda_id desc limit " . 7*($pn-1) . ", 7;";
                                $resP = $conn->query($sqlComPag);
                                if(!$resP) {
                                        echo '<h3>A fost intampinata o problema cu afisarea comenzilor!</h3';
                                        error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                } else {
                                    echo '<h3>Istoric comenzi(' . $total_c . '):</h3>';
                                    echo '<div class="comenzi-big-box">'; // comenzi-big-box
                                    while($lineC = $resP->fetch_assoc()) {
                                        $sqlComandaDetalii = "select * from comenzi_detalii where cd_c_id = " . $lineC['comanda_id'] . ';';
                                        $resC = $conn->query($sqlComandaDetalii);
                                        echo '<div class="comanda-box">
                                                <section>
                                             <p>Cod comanda: #' . $lineC['comanda_id'] . '</p>';
                                        $total = 0;
                                        echo '<table class="tabel-comenzi">
                                                <thead>
                                                    <tr>
                                                        <th>Produs</th>
                                                        <th>Pret</th>
                                                        <th>Cantitate</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                        while($lineDet = $resC->fetch_assoc()) {
                                            $total += $lineDet['cd_p_pret_total'];
                                            $sqlProdus = "select * from produse where produs_id = " . $lineDet['cd_p_id'] . ";";
                                            $resProdus = $conn->query($sqlProdus);
                                            $rowProdus = $resProdus->fetch_assoc();
                                                    echo '<tr>
                                                            <td><div class="td-produs"><a href="produs.php?cod_produs=' . $rowProdus['produs_cod'] . '">' . $rowProdus['produs_nume'] . '</a></div></td>
                                                            <td>' . $lineDet['cd_p_pret'] . ' RON</td>
                                                            <td>' . $lineDet['cd_p_cantitate'] . '</td>
                                                            <td>' . sprintf("%01.2f", ($lineDet['cd_p_pret_total'])) . ' RON</td>
                                                        </tr>';
                                                }
                                                echo '<tfoot>';
                                                    echo '<td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <td><div> Subtotal: ' . sprintf("%01.2f", $total) . ' RON</div>';
                                                        if($lineC['comanda_taxa_transport'] != 0) {
                                                            echo '<div>Transport: 12 RON</div>';
                                                        } else {
                                                            echo '<div>Transport: 0 RON</div>';
                                                        }
                                                         echo '<div><strong>Total: ' . sprintf("%01.2f", $total+$lineC['comanda_taxa_transport']) . ' RON</strong></div></td>';
                                                    echo '</tr>
                                                    </tfoot>
                                                </tbody>
                                              </table>';
                                        echo '<p>Status: ' . ucfirst($lineC['comanda_status']) . '</p>';
                                        echo '<p>Data: ' . $lineC['comanda_data_creata'] . '</p>';
                                        if($lineC['comanda_status'] == 'nepreluata') {
                                            echo '<span class="span-anuleaza" c_id="' . $lineC['comanda_id'] . '" onClick="anulare_comanda(this)">Anuleaza comanda</span>';
                                        }
                                        echo '</section>
                                      </div>'; // comanda-box
                                    }
                                    echo '</div>'; // comenzi-big-box
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
                            echo '<h3>A fost intampinata o problema cu afisarea comenzilor!</h3>';
                            error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    }
        }
    }
