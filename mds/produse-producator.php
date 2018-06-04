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
 color: var(--primary);
 background:transparent;
 outline: none;
 font-size: 1.5rem;
}
        h2{
          font-size: 3rem;
        }
      .btn-cart{
  border :none;
  outline: none;
  
  background: var(--dark);
  color: var(--light);
  font-size: 2rem;
  
}

.btn-cart:hover{
  cursor: pointer;
    background-color: var(--light);
    color: var(--dark);
}
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
            background-color: #f4e1d2;
            margin-bottom: 5%;
        }
        .wrapper-side {
            grid-area: wrapper-side;
        }
        .container-producatori-p {
            background-color: #f4e1d2;
        }
        .container-produse {
            grid-area: container-produse;
            border-left: 2px solid var(--dark);
        }
        .wrapper-produse {
            display: grid;
            grid-template-columns: 0.3fr 0.05fr 1.15fr;
            grid-template-areas:
            "wrapper-side . container-produse"
            "wrapper-side . container-produse";
            grid-area: wrapper-produse;
        }
        .wrapper {
            grid-template-areas:
            'account-box account-box account-box'
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
                    $(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "pn" : 1});
                } else {
                    $(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "search" : searchVal, "pn" : 1});
                }
            })

			select_P = function() {
                if($("#search-produse").val() == "") {
                    var pagina = parseInt($("#select_pagP").val());
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($("#select_pagP").val());
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "search" : searchVal, "pn" : pagina});
                }
			}

			prev_f = function(aux) {
                if($("#search-produse").val() == "") {
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "search" : searchVal, "pn" : pagina});
                }
			}

			 next_f = function(aux) {
                if($("#search-produse").val() == "") {
    				var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "pn" : pagina});
                } else {
                    var searchVal = $("#search-produse").val();
                    var pagina = parseInt($(aux).attr("val"));
    				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "search" : searchVal, "pn" : pagina});
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
				$(".produse-big-box").load("generare-produse-all.php", {"producator" : $.urlParam("producator"), "pn" : 1});
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
                <li id="cos-produse-numar">
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
         <div class="wrapper-produse">
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
                        $prodAux = filter_var(trim($_GET['producator']), FILTER_SANITIZE_STRING);
                        $prodAux = $conn->real_escape_string($prodAux);
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
                                    if(strtolower($row['producator_nume']) == $prodAux) {
                                        echo '<li class="li-side-produse"><a href="produse-producator.php?producator=' . strtolower($row['producator_nume']) . '" style="color: var(--primary);">' . $row['producator_nume'] . '</a>(' . $row['producator_nr_produse'] . ')</li>';
                                    } else {
                                        echo '<li class="li-side-produse"><a href="produse-producator.php?producator=' . strtolower($row['producator_nume']) . '">' . $row['producator_nume'] . '</a>(' . $row['producator_nr_produse'] . ')</li>';
                                    }
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
                echo '<h2 id=titlu-produse>Producator: ' . ucfirst($prodAux) . '<a href="produse.php"><img src="img-site/cancel-icon.png" alt="cancel" style="width: 25px; height: 25px;"></a></h2>';
            ?>
            <input id="search-produse" type="text" name="search" placeholder="Cauta produse">
            <div class="produse-big-box">

            </div>
        </div>
        </div>
        <footer>
        	<p>Pro Gains &copy; 2018</p>
        </footer>
    </div>
    <script type="text/javascript" src="main.js"></script>
</body>
</html>
