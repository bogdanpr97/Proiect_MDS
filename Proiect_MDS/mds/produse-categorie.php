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
<?php include "includes/head.php";?>
         <style>
        .li-side-produse a:hover{
            color: var(--primary);
        }
        .produse-big-box div {
            text-align: center;
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
            background-color: var(--light);
            margin-bottom: 5%;
        }
        .wrapper-side {
            grid-area: wrapper-side;
        }
        .container-producatori-p {
            background-color: var(--light);
        }
        .container-produse {
            grid-area: container-produse;
            border-left: 2px solid var(--dark);
        }
        .wrapper-produse {
            margin-top: 1%;
            display: grid;
            grid-template-columns: 0.3fr 0.05fr 1.15fr;
            grid-template-areas:
            "wrapper-side . container-produse"
            "wrapper-side . container-produse";
            grid-area: wrapper-produse;
        }
        .wrapper {
            grid-template-areas:
            'menu menu menu'
        	'main-nav main-nav main-nav'
        	'wrapper-produse wrapper-produse wrapper-produse'
        	'footer footer footer';
        }
        #titlu-produse {
            margin: 0;
            padding: 0.5% 1% 1% 3.5%;
            color: var(--dark);
        }
        #search-produse {
            margin-left: 3.5%;
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
			padding-top: 2%;
            padding-left: 2%;
            border-top: 2px solid var(--primary);
            text-align: right !important;
		}
        .produs-box {
            padding: 1%;
            border: 1px solid black;
            margin: 2%;
            width: 27%;
        }
        .produs-box {
            float: left;
            background-color: var(--primary);
        }
        .produs-box:last-child {
            float: left;
        }
        .produs-box:last-child::after {
            content: "";
            display: table;
            clear: both;
        }
        .produse-big-box {
            margin-left: 1.5%;
        }
        .pagination-box::before {
            content: "";
            display: table;
            clear: both;
        }
        .pagination-box::after {
            content: "";
            display: table;
            clear: both;
        }
        .clear::after {
            content: "";
            display: table;
            clear: both;
        }
        .clear::before {
            content: "";
            display: table;
            clear: both;
        }
        .produs-box a {
            text-decoration: none;
            color: var(--dark);
        }
    </style>
    <script type="text/javascript">
		$(document).ready(function () {
			$.urlParam = function(name){
                var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }
            $("#search-produse").on("input", function() {
                var searchVal = $(this).val();
                if(searchVal == "") {
                    $(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "pn" : 1});
                } else {
                    $(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "search" : searchVal, "pn" : 1});
                }
            })

			select_P = function() {
                if($("#search-produse").val() == "") {
                    var pagina = parseInt($("#select_pagP").val());
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($("#select_pagP").val());
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "search" : searchVal, "pn" : pagina});
                }
			}

			prev_f = function(aux) {
                if($("#search-produse").val() == "") {
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "search" : searchVal, "pn" : pagina});
                }
			}

			 next_f = function(aux) {
                if($("#search-produse").val() == "") {
    				var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "search" : searchVal, "pn" : pagina});
                }
			}

            adauga_cos = function(aux) {
              var cod_produs = $(aux).attr("id");
              $(aux).html("<span>Se adauga..</span>");
              $.ajax ({
                  url: "actualizare-cos.php",
                  type: "POST",
                  dataType: "json",
                  data: "produs_cod=" + cod_produs + "&produs_cantitate=1",
                  complete: function() {
                      $(aux).html("<span>Adauga in cos</span>");
                  },
                  success: function(data) {
                      output = data.text
                      $("#cos-produse-numar").load("produse.php #cos-produse-numar");
                  }
               });
           };

			$(function () {
				$(".produse-big-box").load("generare-produse-all.php", {"categorie" : $.urlParam("categorie"), "pn" : 1});
			})();
		})
	</script>
         <div class="wrapper-produse">
         <div class="wrapper-side">
             <div class="container-categorii-p">
                 <section>
                     <h4 class="titlu-side-produse">Categorii:</h4>
                     <?php
                        $categAux = filter_var(trim($_GET['categorie']), FILTER_SANITIZE_STRING);
                        $categAux = $conn->real_escape_string($categAux);
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
                                    if(strtolower($row['categorie_nume']) == $categAux) {
                                        echo '<li class="li-side-produse"><a href="produse-categorie.php?categorie=' . strtolower($row['categorie_nume']) . '" style="color: var(--primary);">' . $row['categorie_nume'] . '</a>(' . $row['categorie_nr_produse'] . ')</li>';
                                    } else {
                                        echo '<li class="li-side-produse"><a href="produse-categorie.php?categorie=' . strtolower($row['categorie_nume']) . '">' . $row['categorie_nume'] . '</a>(' . $row['categorie_nr_produse'] . ')</li>';
                                    }
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
                                    echo '<li class="li-side-produse"><a href="produse-producator.php?producator=' . strtolower($row['producator_nume']) . '">' . $row['producator_nume'] . '</a>(' . $row['producator_nr_produse'] . ')</li>';
                                }
                                echo '</ul>';
                                echo '<div class="producator-box">';
                            }
                        }
                     ?>
                 </section>
            </div>
        </div>
        <div class="container-produse">
            <?php
                echo '<h2 id=titlu-produse>Categorie: ' . ucfirst($categAux) . '<a href="produse.php"><img src="img-site/cancel-icon.png" alt="cancel" style="width: 25px; height: 25px;"></a></h2>';
            ?>
            <input id="search-produse" type="text" name="search" placeholder="Cauta produse">
            <div class="produse-big-box">

            </div>
        </div>
        </div>
<?php include "includes/footer.php";?>