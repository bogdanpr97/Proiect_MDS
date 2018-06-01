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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pro Gains</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width , initial-scale=1">
	<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
  	<script
	  src=" http://code.jquery.com/jquery-3.3.1.min.js "
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style media="screen">
        .profil-container {
            grid-area: profil-container;
        }
        .wrapper {
            grid-template-columns: 1fr;
            grid-template-areas:
            "account-box"
            "main-nav"
            "profil-container"
            "footer";
        }
        .schimba-parola-box, .schimba-poza-box, .schimba-email-box, .navigare-profil {
            margin-left: 5%;
        }
        .label-schimba-email {
            margin-right: 1%;
        }
        .navigare-profil a {
            text-decoration: none;
            color: var(--dark);
            margin-right: 0.5%;
        }
        .navigare-profil a:hover {
            color: var(--primary);
        }
        #form-schimba-email, #form-schimba-poza, #form-schimba-parola {
            border: 2px solid var(--dark);
            padding-left: 1%;
            padding-bottom: 1%;
        }
        #form-schimba-poza {
            padding-top: 1%;
        }
        #form-schimba-email div, #form-schimba-parola div {
            margin: 1% 0;
        }
        .label-schimba-email {
            margin-right: 1%;
        }
        .nume-box {
            margin-left: 5%;
        }
        .bar-nav-profil {
            margin-right: 0.5%;
        }
        #btn-mesaj, #btn-blocare, #btn-raportare, #btn-deblocare {
            cursor: pointer;
            color: var(--dark);
        }
        #btn-mesaj:hover, #btn-blocare:hover, #btn-raportare:hover, #btn-deblocare:hover {
            color: var(--primary);
        }
        .subiectMesaj, .textareaMesaj {
            margin-top: 1%;
        }
        #result-box {
            margin-top: 2%:
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $.urlParam = function(name){
                var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }

            deblocare_utilizator = function(aux) {
                var username = $(aux).attr("username");
                if($.urlParam("username") == username) {
                    $.ajax({
                        type: 'post',
                        url: "operatii-utilizatori.php",
                        dataType: 'json',
                        data: 'username=' + username + "&tip=" + "deblocare",
                        success: function(data)
                        {
                            if(data.type == 'success')
                            {
                                location.reload();
                            }
                       }
                   });
                }
            }

            blocare_utilizator = function(aux) {
                var username = $(aux).attr("username");
                if($.urlParam("username") == username) {
                    $.ajax({
                        type: 'post',
                        url: 'operatii-utilizatori.php',
                        dataType: 'json',
                        data: 'username=' + username + "&tip=" + "blocare",
                        success: function(data)
                        {
                            if(data.type == 'success')
                            {
                                location.reload();
                            } else {
                                alert(';afafwa');
                            }
                       },
                   });
                }
            }

            raportare_utilizator = function(aux) {
                var username = $(aux).attr("username");
                var parentBox = $(aux).parent();
                if($(parentBox).has("textarea").length == 0) {
                    var motivArea = document.createElement('textarea');
                    motivArea.name = "raporteaza-utilizator";
                    motivArea.rows = "7";
                    motivArea.cols = "30";
                    motivArea.placeholder = "Motiv";
                    motivArea.style.resize = "none";
                    motivArea.style.display = "block";
                    $(parentBox).append(motivArea);
                    var raporteazaBtn = document.createElement("button");
                    raporteazaBtn.type = "button";
                    raporteazaBtn.innerHTML = "Raporteaza";
                    raporteazaBtn.name = "buton-raporteaza";
                    $(raporteazaBtn).on('click', function() {
                        var motiv = $(parentBox).children("textarea").first().val();
                        if(motiv != '' && $.urlParam("username") == username) {
                            $.ajax({
                                type: 'post',
                                url: "operatii-utilizatori.php",
                                dataType: 'json',
                                data: 'username=' + username + "&motiv=" + motiv,
                                success: function(data)
                                {
                                    if(data.type == 'success')
                                    {
                                        $(parentBox).children("textarea").val('');
                                        $(parentBox).children("button").toggle();
                                        $(parentBox).children("textarea").toggle();
                                        $(parentBox).children("div").first().html("<h3>Utilizatorul a fost raportat.</h3>");
                                    } else {
                                        $(parentBox).children("textarea").val('');
                                        $(parentBox).children("button").toggle();
                                        $(parentBox).children("textarea").toggle();
                                        $(parentBox).children("div").first().html("<h3>Eroare la raportare, incercati mai tarziu</h3>");
                                    }
                               }
                           });
                       }
                    });
                    $(parentBox).append(raporteazaBtn);
                    var boxResult = document.createElement("div");
                    $(parentBox).append(boxResult);
                } else {
                    $(parentBox).children("div").html('');
                    $(parentBox).children("button").toggle();
                    $(parentBox).children("textarea").toggle();
                    $(parentBox).children("div").toggle();
                }
            }

            trimite_mesaj = function(aux) {
				var username = $(aux).attr("username");
				var parentBox = $(aux).parent();
					if($(parentBox).has("textarea").length == 0) {
                        var subiectArea = document.createElement('input');
                        subiectArea.type = "text";
                        subiectArea.name = 'subiect-utilizator';
                        subiectArea.classList = 'subiectMesaj';
                        subiectArea.style.display = "block";
                        subiectArea.placeholder = "Subiect";
                        $(parentBox).append(subiectArea);
						var mesajArea = document.createElement('textarea');
						mesajArea.name = "mesaj-utilizator";
						mesajArea.rows = "10";
						mesajArea.cols = "50";
						mesajArea.classList.add("textareaMesaj");
						mesajArea.placeholder = "Mesaj";
						mesajArea.style.resize = "none";
						mesajArea.style.display = "block";
						$(parentBox).append(mesajArea);
						var trimiteBtn = document.createElement("button");
						trimiteBtn.type = "button";
						trimiteBtn.innerHTML = "Trimite";
						trimiteBtn.name = "buton-mesaj";
						$(trimiteBtn).on('click', function() {
                            var subiect = $(parentBox).children("input").first().val();
							var mesaj = $(parentBox).children("textarea").first().val();
                            if(subiect != '' && mesaj != '' && $.urlParam("username") == username) {
                                $.ajax({
    					            type: 'post',
    					            url: "trimite-mesaj.php",
    					            dataType: 'json',
    					            data: 'username=' + username + "&subiect=" + subiect + "&mesaj=" + mesaj,
    					            success: function(data)
    					            {
    					                if(data.type == 'success')
    					                {
                                            $(parentBox).children("input").val('');
                    						$(parentBox).children("textarea").val('');
                                            $(parentBox).children("input").toggle();
                                            $(parentBox).children("button").toggle();
                                            $(parentBox).children("textarea").toggle();
    					                    $("#result-box").html("<h3>Mesajul a fost trimis.</h3>");
    					                } else {
                                            $(parentBox).children("input").val('');
                    						$(parentBox).children("textarea").val('');
                                            $(parentBox).children("input").toggle();
                                            $(parentBox).children("button").toggle();
                                            $(parentBox).children("textarea").toggle();
                                            $("#result-box").html("<h3>Eroare la trimiterea mesajului, incercati mai tarziu</h3>");
                                        }
    					           }
    						   });
                            }
						});
						$(parentBox).append(trimiteBtn);
                        var boxResult = document.createElement("div");
                        boxResult.id = "result-box";
                        $(parentBox).append(boxResult);
					} else {
                        $(parentBox).children("div").html('');
                        $(parentBox).children("input").toggle();
						$(parentBox).children("button").toggle();
						$(parentBox).children("textarea").toggle();
                        $(parentBox).children("div").toggle();
					}
				}
        })
    </script>
</head>
<body>
     <div class="wrapper">
     	<!-- Navigation -->
		<div class="account-box">
			<?php
				if(!isset($_SESSION["uid"])) {
					echo '<a class="btn" href="login.php">Login</a>
		        		  <a class="btn" href="register.php">Register</a>';
				} else {
                    $sqlImg = "select if(img_profil is NULL, 'default.jpg', img_profil) as img from utilizatori where u_username = ? ;";
                    $stmt = $conn->prepare($sqlImg);
                    $stmt->bind_param("s", $_SESSION['uname']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rowImg = $result->fetch_assoc();
                    $img = $rowImg['img'];
                    echo '<div style="width: 30%; text-align: right;"><span style="margin-right: 1.5%;"><img src="img-profil-utilizatori/' . $img . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"> <a style="text-decoration: none; color: var(--dark);" href="profil.php?username=' . $_SESSION["uname"] . '">' . $_SESSION["uname"] . '</a></span>';
                    echo '<a class="btn" href="logout.php">Logout</a></div>';
                    $result->close();
                    $stmt->close();
				}
			?>
        </div>
     	<nav class="main-nav">
     	<ul>
     		<li>
     			<a href="index.php">Acasa</a>
     		</li>
     		<li>
     			<a href="produse.php">Produse</a>
     		</li>
     		<li>
     			<a href="articole.php">Articole</a>
     		</li>
     		<li>
     			<a href="contact.php">Contact</a>
     		</li>
			<li>
				<?php
				if(isset($_SESSION['produse']) && count($_SESSION['produse']) > 1) {
					echo '<a id="link-cos" href="cos.php">Cosul meu(' .  count($_SESSION['produse']) . ' produse)</a>';
				} else if(isset($_SESSION['produse']) && count($_SESSION['produse']) == 0 || !isset($_SESSION['produse'])) {
					echo '<a id="link-cos" href="cos.php">Cosul meu(0 produse)</a>';
				} else {
					echo '<a id="link-cos" href="cos.php">Cosul meu(1 produs)</a>';
				}
				?>
			</li>
     	</ul>
     </nav>

         <div class="profil-container">
                 <?php
                    if(isset($_SESSION['uname'])) {
                        if(isset($_GET['username']) && ($_GET['username'] == $_SESSION['uname'])) {
                            echo '<div class="navigare-profil">';
                            echo '<a href="profil.php?username=' . $_SESSION['uname'] . '">Profil</a><span class="bar-nav-profil">/</span><a href="mesaje-primite.php?username=' . $_SESSION['uname'] . '">Mesaje primite</a><span class="bar-nav-profil">/</span><a href="mesaje-trimise.php?username=' . $_SESSION['uname'] . '">Mesaje trimise</a><span class="bar-nav-profil">/</span><a href="istoric-comenzi.php?username=' . $_SESSION['uname'] . '">Istoric comenzi</a>';
                            echo '</div>';
                            $sqlImg = "select if(img_profil is NULL, 'default.jpg', img_profil) as img from utilizatori where u_username = '" . $_SESSION['uname'] . "';";
                            $result = $conn->query($sqlImg);
                            $rowImg = $result->fetch_assoc();
                            $img = $rowImg['img'];
                            echo '<div class="nume-box"><h3>Profil: <span style="margin-left: 1%; position: relative; top: 0.65rem;"><img src="img-profil-utilizatori/' . $img . '" style="width: 35px; height: 35px;"></span><span style="margin-left: 1%;">' . $_SESSION['uname'] . '</span></h3></div>';
                            echo '<div class="schimba-poza-box">';
                            echo '<h4>Schimba imaginea de profil</h4>';
                            echo '<p>Dimensiune maxima: 0.30MB</p>';
                            echo '<form id="form-schimba-poza" action="imagine-profil.php" method="post" enctype="multipart/form-data">
                                    <input type="file" name="file">
                                    <button type="submit" name="submit">Schimba</button>
                                </form>';
                                if(isset($_GET['ie'])) {
                                    if($_GET['ie'] == "success") {
                                        echo '<p>Imaginea de profil a fost schimbata cu succes.</p>';
                                    } else if ($_GET['ie'] == "error") {
                                        echo '<p>Eroare la modificarea imaginii de profil, incercati mai tarziu.</p>';
                                    } else if ($_GET['ie'] == "error-ext") {
                                        echo '<p>Eroare la modificarea imaginii de profil, extensia nu este valida.</p>';
                                    } else if ($_GET['ie'] == "error-size") {
                                        echo '<p>Eroare la modificarea imaginii de profil, dimensiunea este prea mare</p>';
                                    }
                                }
                            echo '</div>';
                            echo '<div class="schimba-parola-box">';
                            echo '<h4>Schimba parola</h4>';
                            echo '<form id="form-schimba-parola" action="schimba-parola.php" method="post">
                                        <div><label class="label-schimba-parola" for="parola-curenta">Parola curenta:</label><input type="password" name="parola-curenta" value="" placeholder="Parola curenta" style="margin-right: 2%;"></div>
                                        <div><label class="label-schimba-parola" for="parola-noua">Parola noua:</label><input type="password" name="parola-noua" value="" placeholder="Parola noua" style="margin-right: 2%;">Parola trebuie sa aiba minim 8 caractere, o litera mare, o cifra</div>
                                        <div><label class="label-schimba-parola" for="parola-noua-c">Confirma parola:</label><input type="password" name="parola-noua-c" value="" placeholder="Confirma noua parola" style="margin-right: 2%;"></div>
                                        <button type="submit" name="submit">Schimba parola</button>
                                    </form>';
                            if(isset($_GET['sp'])) {
                                if($_GET['sp'] == "success") {
                                    echo '<p>Parola a fost schimbata cu succes, la urmatoarea autentificare, folositi noua parola.</p>';
                                } else if ($_GET['sp'] == "error") {
                                    echo '<p>Eroare la modificarea parolei, verificati daca ati introdus corect campurile.</p>';
                                }
                            }
                            echo '</div>';
                            echo '<div class="schimba-email-box">';
                            echo '<h4>Schimba adresa de email</h4>';
                            echo '<form id="form-schimba-email" action="schimba-email.php" method="post">
                                        <div><label class="label-schimba-email" for="email-nou">Adresa noua:</label><input type="text" name="email-nou" value="" placeholder="Email" style="margin-right: 2%;"></div>
                                        <div><label class="label-schimba-email" for="parola-curenta">Parola curenta:</label><input type="password" name="parola-curenta" value="" placeholder="Parola curenta" style="margin-right: 2%;"></div>
                                        <div><label class="label-schimba-email" for="parola-curenta-c">Confirma parola:</label><input type="password" name="parola-curenta-c" value="" placeholder="Confirma parola" style="margin-right: 2%;"></div>
                                        <button type="submit" name="submit">Schimba email</button>
                                    </form>';
                            if(isset($_GET['se'])) {
                                if($_GET['se'] == "success") {
                                    echo '<p>Adresa de email a fost schimbata cu succes.</p>';
                                } else if ($_GET['se'] == "error") {
                                    echo '<p>Eroare la modificarea adresei de email, verificati daca ati introdus corect campurile.</p>';
                                }
                            }
                            echo '</div>';
                        } else {
                            $sqlVerificare = "select * from utilizatori where u_username = ? ;";
                            if($stmt = $conn->prepare($sqlVerificare)) {
                                $stmt->bind_param("s", $_GET['username']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $stmt->close();
                                if($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    $username = $row['u_username'];
                                    $sqlImg = "select if(img_profil is NULL, 'default.jpg', img_profil) as img from utilizatori where u_username = '" . $username . "';";
                                    $result = $conn->query($sqlImg);
                                    $rowImg = $result->fetch_assoc();
                                    $img = $rowImg['img'];
                                    $sqlVerifBlocare = "select * from utilizatori_blocati where uid_i = " . $_SESSION['uid'] . " and uid_b = " . $row['u_id'] . ";";
                                    $resultBlocare = $conn->query($sqlVerifBlocare);
                                    echo '<div class="nume-box"><h3>Profil: <span style="margin-left: 1%; position: relative; top: 0.65rem;"><img src="img-profil-utilizatori/' . $img . '" style="width: 35px; height: 35px;"></span><span style="margin-left: 1%;">' . $username . '</span></h3>';
                                    if($resultBlocare->num_rows == 1) {
                                        echo '<p><span id="btn-deblocare" username="' . $username . '" onClick="deblocare_utilizator(this)">Deblocheaza</span></p>
                                              <p><span id="btn-raportare" username="' . $username . '" onClick="raportare_utilizator(this)">Raporteaza</span></p></div>';
                                    } else {
                                            $sqlVerifBlocare2 = "select * from utilizatori_blocati where uid_b = " . $_SESSION['uid'] . " and uid_i = " . $row['u_id'] . ";";
                                            $resultBlocare2 = $conn->query($sqlVerifBlocare2);
                                            if($resultBlocare2->num_rows == 1) {
                                                echo '<p>Acest utilizator te-a blocat, nu ii poti trimite mesaje private.</p>
                                                      <p><span id="btn-raportare" username="' . $username . '" onClick="raportare_utilizator(this)">Raporteaza</span></p></div>';
                                            } else {
                                                echo '<p><span id="btn-mesaj" username="' . $username . '" onClick="trimite_mesaj(this)">Trimite un mesaj privat</span></p>
                                                <p><span id="btn-blocare" username="' . $username . '" onClick="blocare_utilizator(this)">Blocheaza</span></p>
                                                <p><span id="btn-raportare" username="' . $username . '" onClick="raportare_utilizator(this)">Raporteaza</span></p></div>';
                                            }
                                    }
                                } else {
                                    echo '<h3 style="text-align: center;">Pagina ceruta nu exista</h3>';
                                }
                            } else {
                                echo '<h3 style="text-align: center;">A fost intampinata o problema</h3>';
                                error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                            }
                        }
                    } else {
                        echo '<h3 style="text-align: center;"> Trebuie sa fii logat pentru a vedea aceasta pagine</h3>
                        <p style="text-align: center;">Click <a href="login.php">aici</a> pentru a intra in cont.</p>';
                    }
                 ?>
         </div>
     <footer>
       <p>Pro Gains &copy; 2018</p>
     </footer>
 </div>
 <script type="text/javascript" src="main.js"></script>
</body>
</html>
