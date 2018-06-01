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
    if(!isset($_SESSION['uid']) || $_SESSION['uname'] != $_GET['username']) {
        header("Location: page-not-found.php");
        exit();
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
        .navigare-profil, .mesaje-container {
            margin-left: 5%;
        }
        .navigare-profil a {
            text-decoration: none;
            color: var(--dark);
            margin-right: 0.5%;
            margin-left: 0.5%;
        }
        .navigare-profil a:first-child {
            margin-left: 0;
        }
        .navigare-profil a:hover {
            color: var(--primary);
        }
        .nume-box {
            margin-left: 5%;
        }
        .mesaje-big-box {
            margin: 1% 0;
        }
        .mesaj-box:first-child {
            border-top: 2px solid var(--primary);
        }
        .mesaj-box {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5%;
        }
        #next_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
		#prev_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
        .mesaj-box a {
            text-decoration: none;
            color: var(--dark);
        }
        .mesaj-box a:hover {
            color: var(--primary);
        }
        .span-raspunde {
            color: var(--dark);
            cursor: pointer;
        }
        .span-raspunde:hover {
            color: var(--primary);
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            select_C = function() {
				var pagina = parseInt($("#select_pagC").val());
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "primite", "pn" : pagina});
			}

			prev_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "primite", "pn" : pagina});
			}

			 next_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "primite", "pn" : pagina});
			}

            raspunde_mesaj = function(aux) {
				var username = $(aux).attr("username");
				var parentBox = $(aux).parent();
                var subiect = $(aux).attr("subiect");
					if($(parentBox).has("textarea").length == 0) {
						var mesajArea = document.createElement('textarea');
						mesajArea.name = "mesaj-utilizator";
						mesajArea.rows = "10";
						mesajArea.cols = "50";
						mesajArea.classList.add("textareaMesaj");
						mesajArea.placeholder = "Mesaj";
						mesajArea.style.resize = "none";
						mesajArea.style.display = "block";
                        mesajArea.style.marginTop = "0.4%";
						$(parentBox).append(mesajArea);
						var trimiteBtn = document.createElement("button");
						trimiteBtn.type = "button";
						trimiteBtn.innerHTML = "Trimite";
						trimiteBtn.name = "buton-mesaj";
						$(trimiteBtn).on('click', function() {
							var mesaj = $(parentBox).children("textarea").first().val();
                            if(subiect != '' && mesaj != '') {
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
    					                    $("#result-box").html("<h4>Mesajul a fost trimis.</h4>");
    					                } else {
                                            $(parentBox).children("input").val('');
                    						$(parentBox).children("textarea").val('');
                                            $(parentBox).children("input").toggle();
                                            $(parentBox).children("button").toggle();
                                            $(parentBox).children("textarea").toggle();
                                            $("#result-box").html("<h4>Eroare la trimiterea mesajului, incercati mai tarziu</h4>");
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
					}
				}

            $(function () {
                $(".mesaje-container").load("generare-mesaje.php", {"tip" : "primite", "pn" : 1});
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
                            echo '<div class="mesaje-container">'; //mesaje-container

                            echo '</div>'; //mesaje-container
                        }
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
