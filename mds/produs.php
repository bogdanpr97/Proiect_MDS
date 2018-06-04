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
    <style>

    input[type="text"]{
 border: none;
 border-bottom: 1px solid var(--light);
 color: var(--dark);
 background:transparent;
 outline: none;
 font-size: 1.5rem;
}
 .btn-produs{

  border :none;
  outline: none;
  margin: 1%;
  background: var(--dark);
  color: var(--light);
  font-size: 2rem;
  
}
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
        .container-categorii-p {
            background-color: #f4e1d2;
            margin-bottom: 5%;
        }
        .wrapper-side {
            grid-area: wrapper-side;
        }
        .container-producatori-p {
            background-color: #f4e1d2;
        }
        .container-produs {
            grid-area: container-produs;
            background-color: var(--primary);
            
            border-left: 2px solid var(--dark);
            margin-left: 5%;
            padding-left: 3%;
            padding-right: 3%;
            margin-right: 2%;
        }
        .container-similare {
            grid-area: container-similare;
            border-left: 2px solid var(--primary);
            padding-left: 5%;
        }

        .wrapper-produs {
            display: grid;
            grid-template-columns: 0.25fr 1fr 0.3fr;
            grid-template-areas:
            "wrapper-side container-produs container-similare"
            "wrapper-side container-produs container-similare";
            grid-area: wrapper-produs;
        }
        .wrapper {
            grid-template-areas:
            'account-box account-box account-box'
        	'main-nav main-nav main-nav'
        	'wrapper-produs wrapper-produs wrapper-produs'
        	'footer footer footer';
        }
        #titlu-produs {
            margin: 0;
            padding: 0.5% 1% 1% 3.5%;
            color: var(--dark);
        }
        footer {
			display: flex;
			align-items: center;
  			justify-content: center;
		}
        #next_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
		#prev_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
		.pagination-box {
			margin-top: 1%;
		}
		.pagination-box {
            margin-top: 1%;
			padding-top: 2%;
            padding-left: 2%;
            text-align: right !important;
		}
        .li-side-produse a:hover{
            color: var(--primary);
        }
        .produs-similar-box {
            text-align: center;
            padding-bottom: 5%;
            margin-bottom: 5%;
            border-bottom: 2px solid var(--dark);
        }
        .produs-similar-box .box-imag-prod {
            margin: 0 auto;
        }
        .produs-similar-box:last-child {
            border-bottom: none;
        }
        .box-imag-prod {
            width: 50%;
        }
        .produs-box {
            

            text-align: center;
            font-size: 1.5rem;
            color: var(--dark);
            border-bottom: 2px solid var(--primary);
        }
        .produs-box a {
            text-decoration: none;
            color: var(--primary);
        }
        .comentarii-container {
			margin-top: 3%;
		}
        .comentariu-box {
			border-bottom: 2px solid var(--primary);
			margin-top: 1.5%;
			padding: 1%;
		}
		.comentariu-box:last-child {
			border: none;
		}
        #container-form-comentariu {
			border-bottom: 2px solid var(--primary);
			padding-bottom: 2%;
		}
        .comentariu-box a {
            text-decoration: none;
            color: var(--dark);
        }
        img.pressed-c {
			opacity: 1 !important;
		}
        img.img-profil {
            opacity: 1 !important;
            cursor: default !important;
            margin-left: 0% !important;
        }
		#pareri-comentarii img {
			cursor: pointer;
			opacity: 0.4;
		}
        .sterge-span, .editeaza-span, .raporteaza-span {
			margin-left: 3%;
			cursor: pointer;
			color: var(--primary);
		}
		p[c_id] {
			display: inline;
		}
        .textareaEditare, .motivArea {
			margin-top: 1%;
		}
        #img-produs {
            width: 20%;
        }
        @media only screen and (max-width: 800px){
          .wrapper-produs {
            display: grid;
            grid-template-columns: 0.25fr 1fr 0.3fr;
            grid-template-areas:
            "wrapper-side wrapper-side"
            " container-produs container-produs"
            "container-similare container-similare";
            grid-area: wrapper-produs;
        }
        .wrapper {
            font-size: 1rem;
            grid-template-areas:
            'account-box account-box account-box'
            'main-nav main-nav main-nav'
            'wrapper-produs'
            'wrapper-produs'
            'footer footer footer';
        }
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $.urlParam = function(name){
                var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }

			select_C = function() {
				var pagina = parseInt($("#select_pagC").val());
				$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : pagina});
			}

			prev_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : pagina});
			}

			 next_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : pagina});
			}

            parere_comentariu_produs = function(aux) {
				var parere = $(aux).attr("val");
				var id = parseInt($(aux).attr("c_id"));
				if(!isNaN(id)) {
					if(parere == 'l' || parere == 'd') {
						$("p[c_id='" + id + "']").children("span").first().load("adaugare-parere-comentariu-produs.php", {"parere" : parere, "id" : id});
					}
				}
			}

            raportare_comentariu = function(aux) {
                var commId = $(aux).parent().attr("c_id");
                var parentBox = $(aux).parent();
                if($(parentBox).has("textarea").length == 0) {
                    var motivArea = document.createElement('textarea');
                    motivArea.name = "raporteaza-comentariu";
                    motivArea.rows = "7";
                    motivArea.cols = "30";
                    motivArea.placeholder = "Motiv";
                    motivArea.style.resize = "none";
                    motivArea.style.display = "block";
					motivArea.classList.add("motivArea");
                    $(parentBox).append(motivArea);
                    var raporteazaBtn = document.createElement("button");
                    raporteazaBtn.type = "button";
                    raporteazaBtn.innerHTML = "Raporteaza";
                    raporteazaBtn.name = "buton-raporteaza";
                    $(raporteazaBtn).on('click', function() {
                        var motiv = $(parentBox).children("textarea").first().val();
                        if(motiv != '' && !isNaN(commId)) {
                            $.ajax({
                                type: 'post',
                                url: "raporteaza-comentarii.php",
                                dataType: 'json',
                                data: 'tip=produs' + "&motiv=" + motiv + "&c_id=" + commId,
                                success: function(data)
                                {
                                    if(data.type == 'success')
                                    {
                                        $(parentBox).children("textarea").val('');
                                        $(parentBox).children("button").toggle();
                                        $(parentBox).children("textarea").toggle();
                                        $(parentBox).children("div").first().html("<h4>Comentariul a fost raportat.</h4>");
                                    } else {
                                        $(parentBox).children("textarea").val('');
                                        $(parentBox).children("button").toggle();
                                        $(parentBox).children("textarea").toggle();
                                        $(parentBox).children("div").first().html("<h4>Eroare la raportare, incercati mai tarziu</h4>");
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

            editeaza_comentariu = function(aux) {
				var id = parseInt($(aux).attr("c_id"));
				if(!isNaN(id)) {
					var sectiune = $(aux).parent().parent();
					if($(sectiune).has("textarea").length == 0) {
						var id = parseInt($(aux).attr("c_id"));
						var editArea = document.createElement('textarea');
						editArea.name = "comentariu-editat";
						editArea.rows = "10";
						editArea.cols = "50";
						editArea.classList.add("textareaEditare");
						editArea.placeholder = "Comentariu editat";
						editArea.style.resize = "none";
						editArea.style.display = "block";
						editArea.innerHTML = $(sectiune).children("p").first().html();
						$(sectiune).append(editArea);
						var editBtn = document.createElement("button");
						editBtn.type = "button";
						editBtn.innerHTML = "Editeaza";
						editBtn.name = "buton-editare";
						$(editBtn).attr("c_id", id);
						$(editBtn).on('click', function() {
							var mesaj = $(sectiune).children("textarea").first().val();
							$.ajax({
					            type: 'post',
					            url: "editeaza-comentariu-produs.php",
					            dataType: 'json',
					            data: 'c_id=' + id + "&mesaj=" + mesaj,
					            success: function(data)
					            {
					                if(data.type == 'success')
					                {
					                    var pagina = parseInt($("#select_pagC option:selected").text());
										$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : pagina})
					                } else {
										alert('abc');
									}
					           }
						   });
						});
						$(sectiune).append(editBtn);
					} else {
						$(sectiune).children("button").toggle();
						$(sectiune).children("textarea").html($(sectiune).children("p").first().html());
						$(sectiune).children("textarea").toggle();
					}
				}
			}

            sterge_comentariu = function(aux) {
				var id = parseInt($(aux).attr("c_id"));
				if(!isNaN(id)) {
					$.ajax({
			            type: 'post',
			            url: "sterge-comentariu-produs.php",
			            dataType: 'json',
			            data: 'c_id=' + id,
			            success: function(data)
			            {
			                if(data.type == 'success')
			                {
			                    var pagina = parseInt($("#select_pagC option:selected").text());
								$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : pagina})
			                }
			           }
			        });
				}
			}

            $(".btn-produs").on('click', function() {
                var val = parseInt($("#input-cantitate").val());
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
            })

			$(function () {
				$(".comentarii-container").load("generare-comentarii-produse.php", {"produs_cod" : $.urlParam("cod_produs"), "pn" : 1});
			})();
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
         <div class="wrapper-produs">
            <div class="wrapper-side">
                 <div class="container-categorii-p">
                     <section>
                         <h4 class="titlu-side-produse">Categorii:</h4>
                         <?php
                            $sql = "select categorie_nume, categorie_nr_produse from categorii_produse;";
                            $result = $conn->query($sql);
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
                            }
                         ?>
                     </section>
                 </div>
                 <div class="container-producatori-p">
                     <section>
                         <h4 class="titlu-side-produse">Producatori:</h4>
                         <?php
                            $sql = "select producator_nume, producator_nr_produse from producatori_produse;";
                            $result = $conn->query($sql);
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
                                        echo '<li class="li-side-produse"><a href="produse-producator.php?producator=' . strtolower($row['producator_nume']) . '">' . $row['producator_nume'] .'</a>(' . $row['producator_nr_produse'] . ')</li>';
                                    }
                                    echo '</ul>';
                                    echo '<div class="producator-box">';
                                }
                            }
                            ?>
                     </section>
                </div>
            </div>
            <div class="container-produs">
            <?php
                $cod = filter_var(trim($_GET['cod_produs']), FILTER_SANITIZE_STRING);
                $cod = $conn->real_escape_string($cod);
                $sql = "select * from produse where produs_cod = ? ;";
                if($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $cod);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if(!$result) {
                        echo '<div class="produs-box">';
                        echo '<h3>Momentan aceasta cerere nu este disponibila!</h3>';
                        echo '</div class="produs-box">';
                        echo '</div>'; //container-produs
                        error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                    } else {
                        if($result->num_rows == 1) {
                            $row = $result->fetch_assoc();
                            $sqlImg = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $row['produs_id'] . ";";
                            $resImg = $conn->query($sqlImg);
                            echo '<div class="produs-box">';
                            if(!$resImg || $resImg->num_rows == 0) {
                                echo    '<div class="img-titlu-box">
                                            <h3>' . $row['produs_nume'] . '</h3>
                                            <div class="img-box">
                                                <a href="img-produse/default.png"><img id="img-produs" src="img-produse/default.png" alt="' . $row['produs_nume'] . '"></a>
                                            </div>';
                            } else {
                                $row2 = $resImg->fetch_assoc();
                                echo    '<div class="img-titlu-box">
                                            <h3>' . $row['produs_nume'] . '</h3>
                                            <div class="img-box">
                                                <a href="img-produse/' . $row2['imagine_nume'] . '"><img id="img-produs" src="img-produse/' . $row2['imagine_nume'] . '" alt="' . $row['produs_nume'] . '"></a>
                                            </div>';
                            }
                            echo       '</div>';
                            $sqlCategorie = "select categorie_nume from categorii_produse c join produse p on(c.categorie_id = p.produs_categorie_id) where p.produs_id = " . $row['produs_id'] . ';';
                            $sqlProducator = "select producator_nume from producatori_produse pp join produse p on(pp.producator_id = p.produs_producator_id) where produs_id = " . $row['produs_id'] . ';';
                            $resultCateg = $conn->query($sqlCategorie);
                            $resultProd = $conn->query($sqlProducator);
                            $rowCateg = $resultCateg->fetch_assoc();
                            $rowProd = $resultProd->fetch_assoc();
                          echo '<div class="prod-body-box">
                                    <div class="pret-adauga-box">
                                        <p><span>Pret: </span><span>' . $row['produs_pret'] . ' RON</span></p>';
                                        if($row['produs_cantitate'] == 0) {
                                            echo '<h4>Momentan produsul nu este in stoc.</h4>';
                                        } else {
                                            echo '<label for="cantitate">Cantitate: </label><input type="text" name="cantitate" id="input-cantitate" placeholder="cantitate">
                                            <button produs_cod="' . $row['produs_cod'] . '" class="btn-produs" type="button">Adauga in cos</button>';
                                        }
                                        echo '<p>Pretul include taxele, produsele se pot livra in tara prin URGENT CURIER sau POSTA ROMANA(cost 10 lei). La comenzi ce depasesc 500 lei transportul este GRATUIT.</p>
                                        <p><span style="font-weight: 600;">3217 336 987 - Comenzi telefonice</span><br/>
                                                11:00-20:00</p>
                                    </div>
                                    <div class="prezentare-box">
                                        <section>
                                            <h4 id="titlu-prezentare-produs">Prezentare produs:</h4>
                                            <p>' . nl2br($row['produs_prezentare']) . '</p>
                                        </section>
                                    </div>
                                    <div class="detalii-produs">
                                        <h4 id="titlu-detalii-produs">Detalii produs:</h4>
                                        <p>Categorie: <a href="produse-categorie.php?categorie=' . strtolower($rowCateg['categorie_nume']) . '">' . ucfirst($rowCateg['categorie_nume']) . '</a></p>
                                        <p>Producator: <a href="produse-producator.php?producator=' . strtolower($rowProd['producator_nume']) . '">' . ucfirst($rowProd['producator_nume']) . '</a></p>
                                    </div>
                                  </div>';
                          echo '</div>'; //produs-box
                          if(isset($_SESSION['uid'])) {
                              echo '<div id="container-form-comentariu">
                                        <h3>Adauga un comentariu</h3>';
                              echo     '<form id="form-comentariu-produs" action="adauga-comentariu-produs.php" method="post">
                                          <input type="hidden" name="produs-cod" value="' . $row['produs_cod'] . '">
                                          <input type="hidden" name="produs-id" value="' . $row['produs_id'] . '">
                                          <textarea name="comentariu-produs" rows="10" cols="50" placeholder="Comentariu" style="resize: none;"></textarea><br/>
                                          <button type="submit" name="submit">Adauga</button>
                                        </form>';
                                        if(isset($_GET['add']) && $_GET['add'] == "success") {
                                            echo '<h4>Comentariul a fost adaugat</h4>';
                                        } else if(isset($_GET['add']) && $_GET['add'] == "error") {
                                            echo '<h4>Eroarea la adaugarea comentariului, incercati mai tarziu</h4>';
                                        }
                              echo '</div>';
                          } else {
                              echo '<h4>Trebuie sa intri in cont pentru a posta un comentariu.</h4>';
                          }
                          if(isset($_SESSION['uid'])) {
                              echo '<div class="comentarii-container" id="pareri-comentarii">';
                          } else {
                              echo '<div class="comentarii-container">';
                          }
                          echo '</div>'; //comentarii-container
                          echo '</div>'; //container-produs
                          echo '<div class="container-similare">'; //container-similare
                              $sqlSimilare = "select * from produse where produs_categorie_id = " . $row['produs_categorie_id'] . " and produs_id != " . $row['produs_id'] . " order by produs_id desc limit 0 , 4;";
                              $result2 = $conn->query($sqlSimilare);
                              if($result2->num_rows == 0) {
                                  echo '<h4>Momentan nu exista produse similare!</h4>';
                              } else {
                                  echo '<h3 style="text-align: center;">Produse similare</h3>';
                                  echo '<hr/>';
                                  while($line = $result2->fetch_assoc()) {
                                      $sqlImagine = "select imagine_nume from imagini_produse join produse on(imagine_id = produs_img_id) where produs_id = " . $line['produs_id'] . ';';
                                      $resImg = $conn->query($sqlImagine);
                                      $rowImg = $resImg->fetch_assoc();
                                      echo '<div class="produs-similar-box">';
                                      echo    '<div class="wrapper-prod-box">';
                                      echo        '<div class="box-imag-prod">';
                                      echo            '<a href="produs.php?cod_produs=' . $line['produs_cod'] . '" title="' . $line['produs_nume'] . '">
                                                       <img style="width: 78%; height: 78%;" src="img-produse/' . $rowImg['imagine_nume'] . '" alt="' . $line['produs_nume'] . '"></a>';
                                        echo          '</div>
                                                   <div class="box-body-prod">
                                                      <div class="titlu-prod">
                                                          <a href="produs.php?cod_produs=' . $line['produs_cod'] . '" alt="' . $row['produs_nume'] . '">' . $line['produs_nume'] . '</a>
                                                      </div>
                                                      <div class="descriere-prod">
                                                          <p style="margin : 0; padding : 0;">' .  $line['produs_descriere'] . '</p>
                                                      </div>
                                                      <div class="pret-prod">
                                                          <p style="margin : 0; padding : 0;">' .  $line['produs_pret'] . ' RON</p>
                                                      </div>
                                                      <div class="actiune-prod">
                                                          <button id="' . $line["produs_cod"] . '" class="btn-cart">
                                                              <span>Adauga in cos</span>
                                                          </button>
                                                      </div>
                                                  </div>
                                              </div>
                                            </div>';
                                  }
                              }
                        echo '</div>'; //container-similare
                        } else {
                            echo '<div class="produs-box">';
                            echo '<h3>Acest produs nu exista!</h3>';
                            echo '</div class="produs-box">';
                            echo '</div>'; //container-produs
                        }
                    }
                } else {
                    echo '<div class="produs-box">';
                    echo '<h3>Momentan aceasta cerere nu este disponibila!</h3>';
                    echo '</div class="produs-box">';
                    echo '</div>'; //container-produs
                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
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
