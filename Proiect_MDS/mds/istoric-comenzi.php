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
            "menu"
            "main-nav"
            "profil-container"
            "footer";
        }
        .navigare-profil, .comenzi-container {
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
        .pagination-box {
            padding-top: 1%;
        }
        .span-anuleaza {
            color: var(--dark);
            cursor: pointer;
        }
        .span-anuleaza:hover {
            color: var(--primary);
        }
        .comanda-box {
            margin-bottom: 1%;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 1%;
        }
        .tabel-comenzi {
            width: 75%;
        }
        .tabel-comenzi td {
            text-align: center;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            select_C = function() {
				var pagina = parseInt($("#select_pagC").val());
				$(".comenzi-container").load("generare-comenzi.php", {"pn" : pagina});
			}

			prev_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comenzi-container").load("generare-comenzi.php", {"pn" : pagina});
			}

			 next_f = function(aux) {
				var pagina = parseInt($(aux).attr("val"));
				$(".comenzi-container").load("generare-comenzi.php", {"pn" : pagina});
			}

            anulare_comanda = function(aux) {
				var id = parseInt($(aux).attr("c_id"));
				if(!isNaN(id)) {
					$.ajax({
			            type: 'post',
			            url: "anulare-comanda.php",
			            dataType: 'json',
			            data: 'c_id=' + id,
			            success: function(data)
			            {
			                if(data.type == 'success')
			                {
			                    var pagina = parseInt($("#select_pagC option:selected").text());
								$(".comenzi-container").load("generare-comenzi.php", {"pn" : pagina})
			                } else {
                                $(aux).parent().append("<h3>Eroare la anularea comenzii</h3>");
                            }
			           }
			        });
				}
			}


            $(function () {
                $(".comenzi-container").load("generare-comenzi.php", {"pn" : 1});
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
                            echo '<div class="comenzi-container">'; // comenzi-container

                            echo '</div>'; // comenzi-container
                        }
                    }
                 ?>
         </div>
<?php include "includes/footer.php";?>
