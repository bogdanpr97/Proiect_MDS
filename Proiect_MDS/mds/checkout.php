<?php
    require_once '../../../dbC.php';
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
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
    if(isset($_POST['submit'])) {
            if(!isset($_POST['metoda-plata']) || $_POST['metoda-plata'] != "ramburs") {
                $output = json_encode(array("error-plata"));
                exit($output);
            } else {
                $numePrenume = $conn->real_escape_string(filter_var(trim($_POST["nume-prenume"]), FILTER_SANITIZE_STRING));
                $telefon = $conn->real_escape_string(filter_var(trim($_POST["telefon"]), FILTER_SANITIZE_STRING));
                $judet = $conn->real_escape_string(filter_var(trim($_POST["judet"]), FILTER_SANITIZE_STRING));
                $localitate = $conn->real_escape_string(filter_var(trim($_POST["localitate"]), FILTER_SANITIZE_STRING));
                $strada = $conn->real_escape_string(filter_var(trim($_POST["strada"]), FILTER_SANITIZE_STRING));
                $apartament = $conn->real_escape_string(filter_var(trim($_POST["apartament"]), FILTER_SANITIZE_STRING));
                $bloc = $conn->real_escape_string(filter_var(trim($_POST["bloc"]), FILTER_SANITIZE_STRING));
                $informatiiSup = $conn->real_escape_string(filter_var(trim($_POST["informatii-suplimentare"]), FILTER_SANITIZE_STRING));
                if(preg_match("/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])*))*(\s)*$/i", $numePrenume) == 0) {
                    $output = json_encode(array("error-nume"));
                    exit($output);
                } else {
                    if(preg_match("/^[0-9]{10}$/", $telefon) == 0) {
                        $output = json_encode(array("error-telefon"));
                        exit($output);
                    } else {
                        if(preg_match("/^[a-zA-Z\-]{3,}$/i", $judet) == 0) {
                            $output = json_encode(array("error-judet"));
                            exit($output);
                        } else {
                            if(preg_match("/^[a-zA-Z\-]{3,}$/i", $localitate) == 0) {
                                $output = json_encode(array("error-localitate"));
                                exit($output);
                            } else {
                                if(!preg_match("/^[a-zA-Z]([a-zA-Z-]+\s)+\d{1,4}$/i", $strada) || !preg_match("/^[a-z0-9]+$/i", $bloc) || !preg_match("/^[a-z0-9]+$/i", $apartament)) {
                                    $output = json_encode(array("error-adresa"));
                                    exit($output);
                                } else {
                                    $total = 0;
                                    foreach($_SESSION["produse"] as $produs) {
                                        $total += ($produs['produs_pret'] * $produs['produs_cantitate']);
                                        $sql = "select * from produse where produs_cod = '" . $produs['produs_cod'] . "';";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        if(intval($produs['produs_cantitate']) > intval($row['produs_cantitate'])) {
                                            $output = json_encode(array("error-stoc", $row['produs_nume']));
                                            exit($output);
                                        }
                                    }
                                    if($total >= 200) {
                                        $sql = "insert into comenzi (comanda_u_id, comanda_taxa_transport, comanda_informatii_sup) values (" . $_SESSION['uid'] . ", 0 , '" . $informatiiSup . "');";
                                    } else {
                                        $sql = "insert into comenzi (comanda_u_id, comanda_informatii_sup) values (" . $_SESSION['uid'] . ", '" . $informatiiSup . "');";
                                    }
                                    if($result = $conn->query($sql)) {
                                        $last_id = $conn->insert_id;
                                        foreach($_SESSION["produse"] as $produs) {
                                            $sqlId = "select produs_id from produse where produs_cod = '" . $produs['produs_cod'] . "';";
                                            if($resultId = $conn->query($sqlId)) {
                                                $rowId = $resultId->fetch_assoc();
                                                $sqlDetalii = "insert into comenzi_detalii (cd_c_id, cd_p_id, cd_p_cantitate, cd_p_pret, cd_p_pret_total) values ( ? , ? , ? , ? , ? );";
                                                if($stmt = $conn->prepare($sqlDetalii)) {
                                                    $total = floatval($produs['produs_pret']) * floatval($produs['produs_cantitate']);
                                                    $stmt->bind_param("iiiii", $last_id, $rowId['produs_id'], $produs['produs_cantitate'], $produs['produs_pret'], $total);
                                                    $stmt->execute();
                                                    if($stmt->affected_rows == 0) {
                                                        error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                        $sqlDelete = "delete from comenzi where comanda_id = " . $last_id . ";";
                                                        $conn->query($sqlDelete);
                                                        $sqlDelete2 = "delete from comenzi_detalii where cd_c_id = " . $last_id . ";";
                                                        $conn->query($sqlDelete2);
                                                        $output = json_encode(array("error-cerere"));
                                                        exit($output);
                                                    }
                                                } else {
                                                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                    $sqlDelete = "delete from comenzi where comanda_id = " . $last_id . ";";
                                                    $conn->query($sqlDelete);
                                                    $sqlDelete2 = "delete from comenzi_detalii where cd_c_id = " . $last_id . ";";
                                                    $conn->query($sqlDelete2);
                                                    $output = json_encode(array("error-cerere"));
                                                    exit($output);
                                                }
                                            } else {
                                                $sqlDelete = "delete from comenzi where comanda_id = " . $last_id . ";";
                                                $conn->query($sqlDelete);
                                                $sqlDelete2 = "delete from comenzi_detalii where cd_c_id = " . $last_id . ";";
                                                $conn->query($sqlDelete2);
                                                $output = json_encode(array("error-cerere"));
                                                error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                exit($output);
                                            }
                                        }
                                        $sqlDateUtilizator = "insert into date_utilizator_comanda (comanda_id, u_id, nume, prenume, telefon, judet, localitate, strada, bloc, apartament) values
                                                              ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? );";
                                        if($stmtDate = $conn->prepare($sqlDateUtilizator)) {
                                            $numePrenume = explode(" ", $numePrenume);
                                            $stmtDate->bind_param("iissssssss", $last_id, $_SESSION['uid'], $numePrenume[0], $numePrenume[1], $telefon, $judet, $localitate, $strada, $bloc, $apartament);
                                            $stmtDate->execute();
                                            if($stmtDate->affected_rows == 0) {
                                                $sqlDelete = "delete from comenzi where comanda_id = " . $last_id . ";";
                                                $conn->query($sqlDelete);
                                                $sqlDelete2 = "delete from comenzi_detalii where cd_c_id = " . $last_id . ";";
                                                $conn->query($sqlDelete2);
                                                $output = json_encode(array("error-cerere"));
                                                error_log("Error: " . $stmtDate->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                                exit($output);
                                            } else {
                                                $mail = new PHPMailer(true);
                                                $mail->isSMTP();
                                                $mail->Host = 'smtp.mail.yahoo.com';
                                                $mail->SMTPAuth = true;
                                                $mail->Username = 'robertgrmds@yahoo.com';
                                                $mail->Password = 'zxc567bnM0';
                                                $mail->Port = 465;
                                                $mail->SMTPSecure = "ssl";
                                                $mail->setFrom('robertgrmds@yahoo.com');
                                                $sqlEmail = "select u_email from utilizatori where u_id = " . $_SESSION['uid'] . ";";
                                                $resultEmail = $conn->query($sqlEmail);
                                                $rowEmail = $resultEmail->fetch_assoc();
                                                $mail->addAddress($rowEmail['u_email']);
                                                $mesaj = "Va multumim pentru comanda!<br/>Numarul comenzii este " . $last_id . "<br/>Pentru a vedea detaliile comenzii, intrati in contul dumneavoastra la sectiunea istoric comenzi.<br/>Daca aveti orice fel de problema, contactati-ne telefonic sau pe email.";
                                                $mail->isHTML(true);
                                                $mail->Subject = "Comanda Pro Gains";
                                                $mail->Body = $mesaj;
                                                $mail->AltBody = $mesaj;
                                                $mail->send();
                                                if($_POST['salvare-adresa'] == 1) {
                                                    setcookie('adresa-salvata', '1', time() + 60*60*24*7*4*12*3);
                                                } else if($_POST['salvare-adresa'] == 0) {
                                                    setcookie('adresa-salvata', "", time()-3600);
                                                }
                                                unset($_SESSION['produse']);
                                                $output = json_encode(array("success"));
                                                exit($output);
                                            }
                                        } else {
                                            $sqlDelete = "delete from comenzi where comanda_id = " . $last_id . ";";
                                            $conn->query($sqlDelete);
                                            $sqlDelete2 = "delete from comenzi_detalii where cd_c_id = " . $last_id . ";";
                                            $conn->query($sqlDelete2);
                                            $output = json_encode(array("error-cerere"));
                                            error_log("Error: " . $stmtDate->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                            exit($output);
                                        }
                                    } else {
                                        $output = json_encode(array("error-cerere"));
                                        error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                        exit($output);
                                    }
                             }
                        }
                    }
                }
            }
        }
    }
?>
<?php include "includes/head.php";?>
<style media="screen">
        .wrapper-checkout {
            grid-area: wrapper-checkout;
        }
        .wrapper {
            grid-template-columns: 1fr;
            grid-template-areas:
            "menu"
            "main-nav"
            "wrapper-checkout"
            "footer";
        }
        .red {
            color: red;
        }
        #form-comanda {
            margin-left: 2%;
        }
        #form-comanda label:not(:last-child) {
            display: block;
        }
        #form-comanda div {
            margin-bottom: 1%;
        }
        #form-comanda button {
            background-color: var(--dark);
            color: var(--light);
            cursor: pointer;
            padding: 0.5%;
            border: 2px solid var(--primary);
            border-radius: 10%;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#btn-confirmare").on('click', function(e) {
                e.preventDefault();
                $("#result").remove();
                var numePrenume = $("input[name='nume-prenume']").val();
                var salvareAdresa = document.getElementById("salvare-adresa").checked;
                var telefon = $("input[name='telefon']").val();
                var judet = $("select[name='judet']").val();
                var localitate = $("input[name='localitate']").val();
                var strada = $("input[name='strada']").val();
                var bloc = $("input[name='bloc']").val();
                var apartament = $("input[name='apartament']").val();
                var infoSup = $("textarea[name='informatii-suplimentare']").val();
                var metodaPlata = $("input[name='metoda-plata']").val();
                var acceptarePolitica = document.getElementById("acceptare-politica").checked;
                if(acceptarePolitica == false) {
                    var result = document.createElement("div");
                    result.id = "result";
                    result.innerHTML = "<h3>Trebuie sa acceptati politica de confirmare pentru a plasa comanda.</h3>";
                    $(".wrapper-checkout").append(result);
                } else {
                    if(salvareAdresa == false) {
                        salvareAdresa = 0;
                    } else {
                        salvareAdresa = 1;
                    }
                    $.ajax({
			            type: 'post',
			            url: "checkout.php",
			            dataType: 'json',
			            data: 'submit=1&metoda-plata=' + metodaPlata + '&nume-prenume=' + numePrenume + '&telefon=' + telefon + "&judet=" + judet + "&localitate=" + localitate + "&strada=" + strada + "&bloc=" + bloc + "&apartament=" + apartament + "&informatii-suplimentare=" + infoSup + "&salvare-adresa=" + salvareAdresa,
			            success: function(data)
			            {
			                if(data[0] == 'success') {
                                $("#cos-produse-numar").load("checkout.php #cos-produse-numar");
                                $(".wrapper-checkout").html("<h3 style='text-align: center;'>Comanda a fost plasata cu succes, veti primi mai multe detalii pe email.</h3>");
			                } else if(data[0] == 'error-plata') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Metoda de plata incorecta</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-nume') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Nume incorect.</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-telefon') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Telefon incorect.</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-judet') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3> Judet incorect.</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-localitate') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Localitate incorecta.</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-adresa') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Adresa incorecta.</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-stoc') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>Stoc insuficient pentru produsul " + data[1] + ".</h3>";
                                $(".wrapper-checkout").append(result);
			                } else if(data[0] == 'error-cerere') {
                                var result = document.createElement("div");
                                result.id = "result";
                                result.innerHTML = "<h3>A fost intampianta o problema cu cererea, incercati mai tarziu.</h3>";
                                $(".wrapper-checkout").append(result);
			                }
			           }
			        });
                }
            })
        })
    </script>
         <?php
                 if(!isset($_SESSION['produse']) || count($_SESSION['produse']) == 0) {
                     echo '<div class="wrapper-checkout">';
                     echo '<h2 style="text-align: center;">Nu poti plasa comanda daca nu ai niciun produs in cosul de cumparaturi</h2>
                          <p style="text-align: center;">Fa click <a href="produse.php">aici</a> pentru a vedea toate produsele.</p>';
                 } else {
                     if(!isset($_SESSION['uid'])) {
                         echo '<div class="wrapper-checkout">';
                         echo '<h2 style="text-align: center;">Trebuie sa fii logat pentru a putea plasa comanda</h2>';
                         echo '<p style="text-align: center;">Click <a href="login.php">aici</a> pentru a te loga</p>';
                     } else {
                         $sql = "select * from date_utilizator_comanda where u_id = " . $_SESSION['uid'] . " order by id desc limit 0, 1;";
                         $result = $conn->query($sql);
                         echo '<div class="wrapper-checkout" style="padding-left: 10%;">';
                         if($result->num_rows == 0) {
                             echo '<h3>Detalii livrare</h3>';
                             echo '<form id="form-comanda" action="checkout.php" method="post">
                                        <div><label for="salvare-adresa">Salveaza adresa pentru urmatoarele comenzi</label><input id="salvare-adresa" type="checkbox" name="salvare-adresa" value=""></div>
                                        <div><label for="nume-prenume">Nume si prenume<span class="red">*</span></label><input type="text" name="nume-prenume" value=""></div>
                                        <div><label for="telefon">Telefon<span class="red">*</span></label><input type="text" name="telefon" value=""></div>
                                        <div><label for="judet">Judet<span class="red">*</span></label><select name="judet">
										<option value="">---</option>';
                                        $file = fopen("../../logsMDS/judete.txt", "r");
                                        while(($line = fgets($file, 4096)) !== false) {
                                            echo '<option value="' . trim($line) . '">' . $line . '</option>';
                                        }
                                        fclose($file);
									echo '</select></div>
                                        <div><label for="localitate">Localitate<span class="red">*</span></label><input type="text" name="localitate" value=""></div>
                                        <div><label for="strada">Strada(nume si numar)<span class="red">*</span></label><input type="text" name="strada" value=""></div>
                                        <div><label for="bloc">Bloc<span class="red">*</span></label><input type="text" name="bloc" value=""></div>
                                        <div><label for="apartament">Apartament<span class="red">*</span></label><input type="text" name="apartament" value=""></div>
                                        <div><label for="informatii-suplimentare">Informatii suplimentare(optional)</label>
                                        <textarea name="informatii-suplimentare" rows="5" cols="25" style="resize: none;"></textarea></div>
                                        <div><label for="metoda-plata">Metoda de plata</label><input type="radio" name="metoda-plata" value="ramburs" checked="checked">Plata ramburs(se efectueaza la primirea coletului)</div>
                                        <div><input id="acceptare-politica" type="checkbox" name="acceptare-politica" value=""><label for="acceptare-politica">Sunt de acord cu <a href="politica-confidentialitate.pdf">Politica de confidentialitate</a></label></div>
                                        <button id="btn-confirmare" type="submit" name="submit">Confirma comanda</button>
                                   </form>';
                         } else {
                             if(isset($_COOKIE['adresa-salvata'])) {
                                 $row = $result->fetch_assoc();
                                 echo '<h3>Detalii livrare</h3>';
                                 echo '<form id="form-comanda" action="checkout.php" method="post">
                                            <div><label for="salvare-adresa">Salveaza adresa pentru urmatoarele comenzi</label><input id="salvare-adresa" type="checkbox" name="salvare-adresa" value="" checked="checked"></div>
                                            <div><label for="nume-prenume">Nume si prenume<span class="red">*</span></label><input type="text" name="nume-prenume" value="' . $row['nume'] . ' ' . $row['prenume'] . '"></div>
                                            <div><label for="telefon">Telefon<span class="red">*</span></label><input type="text" name="telefon" value="' . $row['telefon'] . '"></div>
                                            <div><label for="judet">Judet<span class="red">*</span></label><select name="judet">
    										<option value="">---</option>';
                                            $file = fopen("../../logsMDS/judete.txt", "r");
                                            while(($line = fgets($file, 4096)) !== false) {
                                                if(trim($line) == trim($row['judet'])) {
                                                    echo '<option value="' . trim($line) . '" selected>' . $line . '</option>';
                                                } else {
                                                    echo '<option value="' . trim($line) . '">' . $line . '</option>';
                                                }
                                            }
                                            fclose($file);
    									echo '</select></div>
                                            <div><label for="localitate">Localitate<span class="red">*</span></label><input type="text" name="localitate" value="' . $row['localitate'] . '"></div>
                                            <div><label for="strada">Strada(nume si numar)<span class="red">*</span></label><input type="text" name="strada" value="' . $row['strada'] . '"></div>
                                            <div><label for="bloc">Bloc<span class="red">*</span></label><input type="text" name="bloc" value="' . $row['bloc'] . '"></div>
                                            <div><label for="apartament">Apartament<span class="red">*</span></label><input type="text" name="apartament" value="' . $row['apartament'] . '"></div>
                                            <div><label for="informatii-suplimentare">Informatii suplimentare(optional)</label>
                                            <textarea name="informatii-suplimentare" rows="5" cols="25" style="resize: none;"></textarea></div>
                                            <div><label for="metoda-plata">Metoda de plata</label><input type="radio" name="metoda-plata" value="ramburs" checked="checked">Plata ramburs(se efectueaza la primirea coletului)</div>
                                            <div><input id="acceptare-politica" type="checkbox" name="acceptare-politica" value=""><label for="acceptare-politica">Sunt de acord cu <a href="politica-confidentialitate.pdf">Politica de confidentialitate</a></label></div>
                                            <button id="btn-confirmare" type="submit" name="submit">Confirma comanda</button>
                                       </form>';
                             } else {
                                 echo '<h3>Detalii livrare</h3>';
                                 echo '<form id="form-comanda" action="checkout.php" method="post">
                                            <div><label for="salvare-adresa">Salveaza adresa pentru urmatoarele comenzi</label><input id="salvare-adresa" type="checkbox" name="salvare-adresa" value=""></div>
                                            <div><label for="nume-prenume">Nume si prenume<span class="red">*</span></label><input type="text" name="nume-prenume" value=""></div>
                                            <div><label for="telefon">Telefon<span class="red">*</span></label><input type="text" name="telefon" value=""></div>
                                            <div><label for="judet">Judet<span class="red">*</span></label><select name="judet">
    										<option value="">---</option>';
    										$file = fopen("../../logsMDS/judete.txt", "r");
                                            while(($line = fgets($file, 4096)) !== false) {
                                                echo '<option value="' . trim($line) . '">' . $line . '</option>';
                                            }
                                            fclose($file);
    								echo '</select></div>
                                            <div><label for="localitate">Localitate<span class="red">*</span></label><input type="text" name="localitate" value=""></div>
                                            <div><label for="strada">Strada(nume si numar)<span class="red">*</span></label><input type="text" name="strada" value=""></div>
                                            <div><label for="bloc">Bloc<span class="red">*</span></label><input type="text" name="bloc" value=""></div>
                                            <div><label for="apartament">Apartament<span class="red">*</span></label><input type="text" name="apartament" value=""></div>
                                            <div><label for="informatii-suplimentare">Informatii suplimentare(optional)</label>
                                            <textarea name="informatii-suplimentare" rows="5" cols="25" style="resize: none;"></textarea></div>
                                            <div><label for="metoda-plata">Metoda de plata</label><input type="radio" name="metoda-plata" value="ramburs" checked="checked">Plata ramburs(se efectueaza la primirea coletului)</div>
                                            <div><input id="acceptare-politica" type="checkbox" name="acceptare-politica" value=""><label for="acceptare-politica">Sunt de acord cu <a href="politica-confidentialitate.pdf">Politica de confidentialitate</a></label></div>
                                            <button id="btn-confirmare" type="submit" name="submit">Confirma comanda</button>
                                       </form>';
                             }
                         }
                     }
                 }
         ?>
     </div>
<?php include "includes/footer.php";?>
