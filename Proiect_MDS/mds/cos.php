<?php
    require_once '../../../dbC.php';
    session_start();
    if(isset($_COOKIE['rememberme']) && !isset($_SESSION['uid'])) {
		$sql = "select u_id, u_username, u_privilegiu_id from utilizatori where u_rememberme = '" . $_COOKIE['rememberme'] . "';";
		$result = $conn->query($sql);
		if($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$_SESSION['uid'] = $row['u_id'];
			$_SESSION['uname'] = $row['u_username'];
			$_SESSION['uprivilegiu'] = $row['u_privilegiu_id'];
		}
	}
    if(isset($_GET['sterge-produs'])) {
        if(isset($_SESSION['produse'][$_GET['sterge-produs']])) {
            unset($_SESSION['produse'][$_GET['sterge-produs']]);
            if(count($_SESSION['produse']) == 0) {
                unset($_SESSION['produse']);
            }
        }
    }
?>
<?php include "includes/head.php";?>
 <style>
        .titlu-side-produse {
            margin: 0;
            padding: 4%;
            background-color: var(--primary);
            border-radius: 5px;
        }
        .ul-side-produse {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
        .li-side-produse {
            border-bottom: 2px dotted black;
            padding: 5% 5%;
        }
        .li-side-produse:last-child {
            border-bottom: none;
        }
        .li-side-produse a {
            text-decoration: none;
            font-weight: 600;
            color: var(--dark);

        }
        .li-side-produse a:hover{
            color: var(--primary);
        }
        .container-categorii-p {
            background-color: var(--light);
            margin-bottom: 5%;
        }
        .wrapper-side {
            grid-area: wrapper-side;
        }
        .container-producatori-p {
            background-color: var(--light);
        }
        .container-cos {
            grid-area: container-cos;
            padding-left: 2%;
            border-left: 2px solid var(--dark);
        }
        .wrapper-produse {
            display: grid;
            grid-template-columns: 0.3fr 0.05fr 1.15fr;
            grid-template-areas:
            "wrapper-side . container-cos"
            "wrapper-side . container-cos";
            grid-area: wrapper-produse;
        }
        .wrapper {
            grid-template-areas:
            'account-box account-box account-box'
        	'main-nav main-nav main-nav'
        	'wrapper-produse wrapper-produse wrapper-produse'
        	'footer footer footer';
        }
        footer {
			display: flex;
			align-items: center;
  			justify-content: center;
		}
        table th, td  {
            padding: 1%;
            width: 7.5%;
            text-align: center;
            border: 2px solid var(--primary);
        }
        table {
            border-collapse: collapse;
        }
        table td a {
            text-decoration: none;
            color: var(--dark);
        }
        .td-produs {
            height: 85px;
            display: grid;
            align-items: center;
            justify-content: center;
        }
        .td-produs a {
            text-decoration: underline;
        }
    </style>
    <script>
        $(document).ready(function() {
            $(".input-cantitate-cos").on('input', function() {
                var val = parseInt($(this).val());
                var cod = $(this).attr("produs_cod");
                if(Number.isInteger(val) && val >= 1) {
                    $.ajax ({
                        url: "actualizare-cos.php",
                        type: "POST",
                        dataType: "json",
                        data: "produs_cod=" + cod + "&produs_cantitate=" + val + "&s",
                        success: function() {
                            location.reload();
                        }
                     });
                }
            });
        })
    </script>
         <div class="wrapper-produse">
             <div class="wrapper-side">
                 <div class="container-categorii-p">
                     <section>
                         <h4 class="titlu-side-produse">Categorii:</h4>
                         <?php
                            $sql = "select categorie_nume, categorie_nr_produse from categorii_produse;";
                            $stmtCateg = $conn->prepare($sql);
                            $stmtCateg->execute();
                            $result = $stmtCateg->get_result();
                            $stmtCateg->close();
                            if(!$result) {
                                echo '<div class="categorie-box">';
                                echo '<p>Momentan aceasta cerere nu este disponibila!</p>';
                                echo '</div class="categorie-box">';
                                error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            } else {
                                if($result->num_rows == 0) {
                                    echo '<div class="categorie-box">';
                                    echo '<p>Momentan nu este disponibila nicio categorie!</p>';
                                    echo '</div class="categorie-box">';
                                } else {
                                    echo '<div class="categorie-box">';
                                    echo '<ul class="ul-side-produse">';
                                    while($row = $result->fetch_assoc()) {
                                        echo '<li class="li-side-produse"><a href="produse-categorie.php?categorie=' . strtolower($row['categorie_nume']) . '">' . $row['categorie_nume'] . '</a>(' . $row['categorie_nr_produse'] . ')</li>';
                                    }
                                    echo '</ul>';
                                    echo '<div class="categorie-box">';
                                }
                                $result->close();
                            }
                         ?>
                     </section>
                 </div>
                 <div class="container-producatori-p">
                     <section>
                         <h4 class="titlu-side-produse">Producatori:</h4>
                         <?php
                            $sql = "select producator_nume, producator_nr_produse from producatori_produse;";
                            $stmtProd = $conn->prepare($sql);
                            $stmtProd->execute();
                            $result = $stmtProd->get_result();
                            $stmtProd->close();
                            if(!$result) {
                                echo '<div class="producator-box">';
                                echo '<p>Momentan aceasta cerere nu este disponibila!</p>';
                                echo '</div class="producator-box">';
                                error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            } else {
                                if($result->num_rows == 0) {
                                    echo '<div class="producator-box">';
                                    echo '<p>Momentan nu este disponibila nicio producator!</p>';
                                    echo '</div class="producator-box">';
                                } else {
                                    echo '<div class="producator-box">';
                                    echo '<ul class="ul-side-produse">';
                                    while($row = $result->fetch_assoc()) {
                                        echo '<li class="li-side-produse"><a href="produse-producator.php?producator=' . strtolower($row['producator_nume']) . '">' . $row['producator_nume'] . '</a>(' . $row['producator_nr_produse'] . ')</li>';
                                    }
                                    echo '</ul>';
                                    echo '<div class="producator-box">';
                                }
                                $result->close();
                            }
                         ?>
                     </section>
                </div>
            </div>
            <div class="container-cos">
                <?php
                if(!isset($_SESSION['produse']) || count($_SESSION['produse']) == 0) {
                    echo '<h2>Cosul tau este de cumparaturi este gol!</h2>
                          <p>Pentru a putea adauga un produs in cos, gaseste produsul dorit si apasa pe butonul "Adauga in cos".</p>
                          <p>Fa click <a href="produse.php">aici</a> pentru a vedea toate produsele.</p>';
                } else {
                    if(count($_SESSION['produse']) == 1) {
                        echo '<h3>Cosul meu(1 produs)</h3>';
                    } else {
                        echo '<h3>Cosul meu(' . count($_SESSION['produse']) . ' produse)</h3>';
                    }
                    echo '<table class="tabel-cos" id="rezultate-cos-produse">
                            <thead>
                                <tr>
                                    <th>Produs</th>
                                    <th>Pret</th>
                                    <th>Cantitate</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>';
                            $total = 0;
                            $sql = "select imagine_nume from produse p join imagini_produse i on(p.produs_img_id = i.imagine_id) where p.produs_cod = ? ;";
                            $stmt = $conn->prepare($sql);
                            foreach($_SESSION["produse"] as $produs) {
                                $produs_nume = $produs["produs_nume"];
                                $produs_pret = $produs["produs_pret"];
                                $produs_cod = $produs["produs_cod"];
                                $produs_cantitate = $produs["produs_cantitate"];
                                $subtotal = ($produs_pret * $produs_cantitate);
                                $total = ($total + $subtotal);
                                $stmt->bind_param("s", $produs_cod);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                echo '<tr>
                                        <td style="width: 10%;"><div style="width: 50%; float:left;"><a href="produs.php?cod_produs=' . $produs_cod . '"><img src="img-produse/' . $row['imagine_nume'] . '" style="width: 100px; height: 95px;"></a></div><div class="td-produs"><a href="produs.php?cod_produs=' . $produs_cod . '">' . $produs_nume . '</a></div></td>
                                        <td>' . $produs_pret . ' RON</td>
                                        <td><input type="text" produs_cod="' . $produs_cod . '" class="input-cantitate-cos" value="' . $produs_cantitate . '"></td>
                                        <td>' . sprintf("%01.2f", ($produs_pret * $produs_cantitate)) . ' RON</td>
                                        <td>
                                        <a href="cos.php?sterge-produs=' . $produs_cod  . '" class="buton-sterge-item" produs_cod="' . $produs_cod . '">Sterge</a>
                                        </td>
                                    </tr>';
                            }
                            $stmt->close();
                            echo '<tfoot>
                                    <tr>
                                    <td><a href="produse.php" class="buton-continua">Continua cumparaturile</a></td>
                                    <td colspan="2"></td>';
                                echo '<td><div> Subtotal: ' . sprintf("%01.2f", $total) . ' RON</div>';
                                if($total >= 200) {
                                    echo '<div>Transport: 0 RON</div>';
                                    echo '<div><strong>Total: ' . sprintf("%01.2f", $total+0) . ' RON</strong></div></td>';
                                } else {
                                    echo '<div>Transport: 12 RON</div>';
                                    echo '<div><strong>Total: ' . sprintf("%01.2f", $total+12) . ' RON</strong></div></td>';
                                }
                                echo '<td><a href="checkout.php" class="buton-checkout">Trimite comanda</a></td>';
                                echo '</tr>
                                </tfoot>
                            </tbody>
                          </table>';
                          echo '<h3>La comenzile de peste 200 lei, transportul este gratuit.</h3>';
                    }
                ?>
            </div>
        </div>
      <?php include "includes/footer.php";?>