<?php
	include_once '../../../dbC.php';
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
<?php include "includes/head.php";?>
         <style>
             p{
                 margin-top:15px;
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
			margin-top: 1.5%;
		}
		.comentariu-box {
			border-bottom: 2px solid var(--primary);
			margin-top: 1.5%;
			padding: 1%;
            padding-bottom: 4%;
		}
		.comentariu-box:last-child {
			border: none;
		}
	    .articol-container {
			border-bottom: 2px solid var(--primary);
			padding-bottom: 1%;
		}
	    .comentarii-container {
			margin-top: 3%;
		}
	    .similare-wrapper {
            margin-top: 5%;
			grid-area: similare-wrapper;
			border-left: 2px solid var(--primary);
			padding-left: 6%;
		}
        .wrapper-articol {
            margin-top: 2%;
            grid-area: wrapper-articol;
        }
        .wrapper {
            grid-template-columns: 0.07fr 0.9fr 0.005fr 0.5fr;
            grid-template-areas:
            "main-nav main-nav main-nav main-nav"
            ". wrapper-articol . similare-wrapper"
            "footer footer footer footer";
        }
		.link-articole {
			text-decoration: none;
			color: var(--primary);
		}
		.articol-similar-box {
			border-bottom: 2px solid var(--dark);
			padding-bottom: 10px;
		}
		.articol-similar-box::after {
			content: "";
			display: table;
			clear: both;
		}
		.articol-similar-box:last-child {
			border: none;
			padding-bottom: 0;
		}
		.articol-similar-box img {
			float: left;
			width: 200px;
			height: 100px;
			margin-right: 2%;
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
		img#pressed {
			opacity: 1 !important;
		}
		img.img-profil {
			opacity: 1 !important;
			cursor: default !important;
			margin-left: 0% !important;
		}
		#pareri-articol img {
			cursor: pointer;
			opacity: 0.4;
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
             
        @media only screen and (max-width: 1200px) {
            .wrapper {
                 grid-template-areas:
                 "main-nav main-nav main-nav main-nav"
                 "wrapper-articol wrapper-articol wrapper-articol wrapper-articol"
                 "similare-wrapper similare-wrapper similare-wrapper similare-wrapper"
                 "footer footer footer footer" !important;
            } 
            .similare-wrapper{
                border: 0;
                padding-left: 2%;
            }
            p{
                margin-top:10px;
            }
            .comentariu-box{
                padding-bottom: 3%;
            }
         } 
             @media only screen and (max-width: 400px){
                 .comentariu-box{
                padding-bottom: 5%;
            }
                 .data_comm{
                     font-size: 10px;
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
				$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : pagina});
			}

			prev_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : pagina});
			}

			 next_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : pagina});
			}

			parere_articol = function(aux) {
				var parere = $(aux).attr("val");
				if(parere == 'l' || parere == 'd') {
					$("#pareri-articol").load("adaugare-parere-articol.php", {"parere" : parere, "titlu" : $.urlParam("titlu"), "data" : $.urlParam("data")});
				}
			}

			parere_comentariu_articol = function(aux) {
				var parere = $(aux).attr("val");
				var id = parseInt($(aux).attr("c_id"));
				if(!isNaN(id)) {
					if(parere == 'l' || parere == 'd') {
						$("p[c_id='" + id + "']").children("span").first().load("adaugare-parere-comentariu-articol.php", {"parere" : parere, "id" : id});
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
                                data: 'tip=articol' + "&motiv=" + motiv + "&c_id=" + commId,
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

			sterge_comentariu = function(aux) {
				var id = parseInt($(aux).attr("c_id"));

				if(!isNaN(id)) {
					$.ajax({
			            type: 'post',
			            url: "sterge-comentariu.php",
			            dataType: 'json',
			            data: 'c_id=' + id,
			            success: function(data)
			            {
			                if(data.type == 'success')
			                {
			                    var pagina = parseInt($("#select_pagC option:selected").text());
								$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : pagina})
			                }
			           }
			        });
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
					            url: "editeaza-comentariu.php",
					            dataType: 'json',
					            data: 'c_id=' + id + "&mesaj=" + mesaj,
					            success: function(data)
					            {
					                if(data.type == 'success')
					                {
					                    var pagina = parseInt($("#select_pagC option:selected").text());
										$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : pagina})
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

			$(function () {
				$(".comentarii-container").load("generare-comentarii.php", {"titlu" : $.urlParam("titlu"), "pn" : 1});
			})();
		})
	</script>
         <div class="wrapper-articol">
            <?php
                if(!isset($_GET['data']) || !isset($_GET['titlu'])) {
					echo '<div class="articol-container">
							  <div style="display: grid; justify-content: center;">
								<h2>Pagina ceruta nu exista!</h2>
								<a href="articole.php">Click aici pentru a merge la articole.</a>
							  </div>';
                } else {
					$date = $conn->real_escape_string($_GET['data']);
					$title = $conn->real_escape_string($_GET['titlu']);
	                $sql = "select * from articole where a_titlu = ? and a_data = ?;";
	                if($stmt = $conn->prepare($sql)) {
	                    $stmt->bind_param("ss", $title, $date);
	                    $stmt->execute();
	                    $result = $stmt->get_result();
	                    if($result->num_rows == 1) {
							echo '<div class="articol-container">';
	                        //while($row = $result->fetch_assoc()) {
							    $row = $result->fetch_assoc();
								$sqlLike = "select count(*) as total from pareri_articole where a_id = " . $row['a_id'] . " and parere = 'l';";
								$sqlDislike = "select count(*) as total from pareri_articole where a_id = " . $row['a_id'] . " and parere = 'd';";
								$rLike = $conn->query($sqlLike);
								$rDislike = $conn->query($sqlDislike);
								$rowLike = $rLike->fetch_assoc();
								$rowDislike = $rDislike->fetch_assoc();
								$sqlCategorie = "select c_nume from articole a join categorii_articole c on(a.a_categorie = c.c_id) where a.a_categorie = " . $row['a_categorie'] . ";";
								$rs = $conn->query($sqlCategorie);
								$row2 = $rs->fetch_assoc();
								$rs->close();
		                            echo    '<article>
											<section>
		                                    <h2>' . $row['a_titlu'] . '</h3>
		                                    <img src="img-articole/' . $row['a_img_name'] . '">
		                                    <p style="max-width:800px; word-wrap:break-word;">' . nl2br($row['a_text']) . '</p>
		                                    <p> Data: ' . $row['a_data'] . '</p>
		                                    <p> Autor: ' . $row['a_autor'] . '</p>
											<p> Categorie: <a href="articole-categorie.php?categorie=' . $row2['c_nume'] . '" style="color: var(--primary); text-decoration: none;">' . ucfirst($row2['c_nume']) . '</a></p>';
											if(isset($_SESSION['uid'])) {
												echo '<div id="pareri-articol">';
											} else {
												echo '<div>';
											}
											if(isset($_SESSION['uid'])) {
												$sqlParere = "select parere from pareri_articole where u_id = " . $_SESSION['uid'] . " and a_id = " . $row['a_id'] . ";";
												$resultParere = $conn->query($sqlParere);
												if($resultParere->num_rows == 1) {
													$rowParere = $resultParere->fetch_assoc();
													if($rowParere['parere'] == 'l') {
														echo '<img id="pressed" src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'] . '</div></p>
					                                    </section>
														</article>';
													} else {
														echo '<img src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img id="pressed" src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'] . '</div></p>
					                                    </section>
														</article>';
													}
												} else {
													echo '<img src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'] . '</div></p>
				                                    </section>
													</article>';
												}
											} else {
												echo '<img src="img-site/thumbs-up.png" style="position: relative; top: 10px;" val="l" onClick="parere_articol(this)"> ' . $rowLike['total'] . ' <img src="img-site/thumbs-down.png" style="position: relative; top: 6px; margin-left: 1%;" val="d" onClick="parere_articol(this)"> ' . $rowDislike['total'] . '</div></p>
			                                    </section>
												</article>';
											}
								echo '</div>';
								if(isset($_SESSION['uid'])) {
									echo '<div id="container-form-comentariu">
										      <h3>Adauga un comentariu</h3>';
									echo     '<form id="form-comentariu-articol" action="adauga-comentariu-articol.php" method="post">
												<input type="hidden" name="articol-titlu" value="' . $row['a_titlu'] . '">
												<input type="hidden" name="articol-data" value="' . $row['a_data'] . '">
												<input type="hidden" name="articol-id" value="' . $row['a_id'] . '">
												<textarea class="form-control" cols="8" rows="6" name="comentariu-articol" rows="10" cols="50" placeholder="Comentariu" style="resize: none;"></textarea><br/>
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
								echo '</div>'; // comentarii-container
							echo '</div>'; //wrapper-articol
								echo '<div class="similare-wrapper">';
								echo '<h3>Articole similare</h3>';
								echo '<hr/>';
								$sqlSimilare = "select * from articole where a_categorie = " . $row['a_categorie'] . " and a_id != " . $row['a_id'] . " order by a_data desc limit 0, 3;";
								$result2 = $conn->query($sqlSimilare);
								while($line = $result2->fetch_assoc()) {
									echo '<div class="articol-similar-box">
											<div style="float: left; margin-right: 1%;">
												<img src="img-articole/' . $line['a_img_name']. '" style="margin-bottom: 10%;">
												<p> Data: ' . $line['a_data'] . '</p>
												<p> Autor: ' . $line['a_autor'] . '</p>
												<a class="link-articole" href="articol.php?titlu='.$line['a_titlu'].
												'&data='.$line['a_data'].'">Citeste tot articolul</a>
											</div>
											<div>
											   <h3>' . $line['a_titlu'] . '</h3>
											   <p>' . $line['a_descriere'] . '</p>
											</div>
										 </div>';
								}
								$result2->close();
								echo '</div>';
	                        //}
	                    } else {
							echo '<div class="articole-container">
									  <div style="display: grid; justify-content: center;">
										<h2>Pagina ceruta nu exista!</h2>
										<a href="articole.php">Click aici pentru a merge la articole.</a>
									  </div>
								  </div>';
							echo '</div>'; //wrapper-articol
	                    }
	                  $stmt->close();
	                } else {
	                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu, ne cerem scuze!</h2>';
	                    error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
						echo '</div>'; //wrapper-articol
	                }
			    }
            ?>
        <?php include "includes/footer.php";?>
