<?php
    require_once '../../../dbC.php';
    session_start();
    if(isset($_COOKIE['rememberme']) && !isset($_SESSION['uid'])) {
        $sql = "select u_id, u_username from utilizatori where u_rememberme = '" . $_COOKIE['rememberme'] . "';";
        $result = $conn->query($sql);
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['uid'] = $row['u_id'];
            $_SESSION['uname'] = $row['u_username'];
        }
    }
    if(!isset($_SESSION['uid']) || $_SESSION['uname'] != $_GET['username']) {
        header("Location: page-not-found.php");
        exit();
    }
?>
<?php include "includes/head.php";?>
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
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            select_C = function() {
				var pagina = parseInt($("#select_pagC").val());
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "trimise", "pn" : pagina});
			}

			prev_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "trimise", "pn" : pagina});
			}

			 next_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".mesaje-container").load("generare-mesaje.php", {"tip" : "trimise", "pn" : pagina});
			}
            $(function () {
                $(".mesaje-container").load("generare-mesaje.php", {"tip" : "trimise", "pn" : 1});
            })();
        })
    </script>

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
<?php include "includes/footer.php";?>

