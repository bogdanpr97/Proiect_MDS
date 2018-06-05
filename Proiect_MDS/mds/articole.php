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
		.pagination-box {
			margin-top: 1%;
		}
		.wrapper-categorii {
			grid-area: wrapper-categorii;
		}
		.wrapper-categorii h3 {
			margin-left: 6%;
			margin-top: 0%;
			color: black;
			font-weight: 700;
		}
		.wrapper-categorii li {
			background: white;
			display: block;
			text-decoration: none;
			padding: 0.5rem;
			text-align: center;
			color: black;
			text-transform: uppercase;
			font-size: 1 rem;
			box-shadow: var(--shadow);
			margin-bottom: 2%;
			width: 100%;
			font-weight: 600;
			cursor: pointer;
		}
		.wrapper-categorii li:hover {
			background-color: var(--primary);
		}
		.wrapper {
			grid-template-columns: 1fr 1fr 1fr;
			grid-template-areas:
            "menu menu menu"
			"main-nav main-nav main-nav"
			"wrapper-articole wrapper-articole wrapper-categorii"
			"footer footer footer";
		}
		.wrapper-articole {
			grid-area: wrapper-articole;
			border: 2px solid black;
			padding: 25px;
		}
		.link-articole {
			text-decoration: none;
			color: var(--primary);
		}
		#lsc {
			margin-right: 0.5%;
		}
		.articole-box {
			border-bottom: 2px solid var(--dark);
			padding-bottom: 10px;
		}
		.articole-box::after {
			content: "";
			display: table;
			clear: both;
		}
		.articole-box:last-child {
			border: none;
			padding-bottom: 0;
		}
		.articole-box img {
			float: left;
			width: 200px;
			height: 100px;
			margin-right: 2%;
		}
		#next_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
		#prev_ctrl:hover {
			cursor: pointer;
			color: var(--primary);
		}
        @media only screen and (max-width: 920px) {
			.wrapper {
				grid-template-columns: 1fr 0.2fr !important;
				grid-template-areas:
				"menu menu"
				"main-nav main-nav"
				"wrapper-articole wrapper-categorii"
				"footer footer";
			}
			.wrapper-categorii li {
				width: 120%;
			}
            .wrapper-categorii ul{
                padding: 0;
            }
		}
		@media only screen and (max-width: 600px) {
			.wrapper {
				grid-template-columns: 1fr 0.5fr;
				grid-template-areas:
				"menu menu"
				"main-nav main-nav"
				"wrapper-categorii wrapper-categorii"
				"wrapper-articole wrapper-articole"
				"footer footer";
			}
			.wrapper-categorii li {
				width: 100%;
			}
			body{
				margin: 30px 5px !important;
			}
			.wrapper-categorii h3 {
				margin-left: 0%;
			}
		}
		@media only screen and (max-width: 400px) {
			.wrapper-categorii li {
				width: 100%;
			}
		}
		@media only screen and (max-width: 300px) {
			.wrapper-categorii li {
				width: 100%;
			}
        }
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#search-input").on('input', function() {

				var searchVal = $(this).val();
				if(searchVal == '') {
					$(".articole-container").load("articole.php .articole-container");
				} else {
					//$(".articole-container").load("cautare-articole.php", {"search" : searchVal});
					$(".articole-container").load("articole.php .articole-container", {"search" : searchVal});
				}
			});

			$(".wrapper-categorii ul li").on('click', function() {
				var categorie = $(this).find('span:first').text();
				window.location = "articole-categorie.php?categorie=" + categorie;
			});

			$("#select_pag").on('change', function() {
				var pagina = $(this).val();
				window.location = "articole.php?pn=" + pagina;
			});

			$.urlParam = function(name){
                var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }

			select_S = function() {
				var pagina = parseInt($("#select_pagS").val());
				var searchVal = $("#search-input").val();
				$(".articole-container").load("articole.php?pn=" + pagina + " .articole-container", {"search" : searchVal});
			}

			prev_f = function(aux) {
				var searchVal = $("#search-input").val();
				var param = parseInt($(aux).attr("val"));
				$(".articole-container").load("articole.php?pn=" + (param-1) + " .articole-container", {"search" : searchVal});
			}

			 next_f = function(aux) {
				var searchVal = $("#search-input").val();
				var param = parseInt($(aux).attr("val"));
				$(".articole-container").load("articole.php?pn=" + (param+1) + " .articole-container", {"search" : searchVal});
			}
		})
	</script>
         	 <div class="wrapper-articole">
			 <?php
			 	 if(!isset($_POST['search'])) {
				 $sql = "select * from articole order by a_data desc;";
				 $result = $conn->query($sql);
				 if(!$result) {
					 echo '<div class="articole-container">';
					 echo '<h2>Momentan aceasta cerere nu este disponibila!</h2>';
					 error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
				 } else {
					 if($result->num_rows > 0) {
						 $total = $result->num_rows;
						 $total_p = ceil($total / 5);
						 if(!isset($_GET['pn']) || intval($_GET['pn']) == 1) {
							 $pn = 1;
							 $sql2 = "select * from articole order by a_data desc limit " . 5*($pn-1) . " , 5;";
							 $rpp = $conn->query($sql2);
							 echo '<label id="lsc" for="search-articole">Cauta articole:</label><input id="search-input" class="form-control" style = "max-width:200px" type="text" placeholder="Search..." name="search-articole">';
							 echo '<div class="articole-container">';
							 echo '<h2>Toate articolele</h2>
							 	   <hr/>';
							 while($row = $rpp->fetch_assoc()) {
								 $sql3 = "select c_nume from categorii_articole c join articole a on(c.c_id = a.a_categorie) where a.a_categorie = " . $row['a_categorie'] . ";";
								 $res = $conn->query($sql3);
								 $row2 = $res->fetch_assoc();
								 echo '<div class="articole-box">
								 		 <div style="float: left; margin-right: 1%;">
											 <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
											 <p> Data: ' . $row['a_data'] . '</p>
 										 	 <p> Autor: ' . $row['a_autor'] . '</p>
											 <p> Categorie: ' . ucfirst($row2['c_nume']) . '</p>
											 <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
											 '&data='.$row['a_data'].'">Citeste tot articolul</a>
										 </div>
										 <div>
										 	<h3>' . $row['a_titlu'] . '</h3>
											<p>' . $row['a_descriere'] . '</p>
										 </div>
									 </div>';
							 }
							 echo '<div class="pagination-box">';
						 	 if($total_p >= 2) {
								echo '<a href="articole.php?pn=' . 2 . '" style="margin-right: 1.5%;">Next</a>';
							 }
							 echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
							 echo '<select name="pag" id="select_pag" style="margin-left: 1%;">';
							 for($i = 1; $i <= $total_p; $i++) {
								 if($i == $pn) {
									 echo '<option value="' . $i . '" selected>' . $i . '</option>';
								 } else {
									 echo '<option value="' . $i . '">' . $i . '</option>';
								 }
							 }
							 echo '</select>';
							 echo '</div>';
						 } else {
							 if(!is_numeric($_GET['pn'])) {
								 echo '<div class="articole-container">
										   <div style="display: grid; justify-content: center;">
											 <h2>Pagina ceruta nu exista!</h2>
											 <a href="articole.php">Click aici pentru a merge la articole.</a>
										   </div>';
							 } else {
								 if(intval($_GET['pn']) < 1 || intval($_GET['pn']) > $total_p) {
									 echo '<div class="articole-container">
										 	   <div style="display: grid; justify-content: center;">
												 <h2>Pagina ceruta nu exista!</h2>
												 <a href="articole.php">Click aici pentru a merge la articole.</a>
											   </div>';
								 } else {
									 $pn = intval($_GET['pn']);
									 $sql2 = "select * from articole order by a_data desc limit " . 5*($pn-1) . " , 5;";
									 $rpp = $conn->query($sql2);
									 echo '<label id="lsc" for="search-articole">Cauta articole:</label><input id="search-input" type="text" placeholder="Search..." name="search-articole">';
									 echo '<div class="articole-container">';
									 echo '<h2>Toate articolele</h2>
									 	   <hr/>';
									 while($row = $rpp->fetch_assoc()) {
										 $sql3 = "select c_nume from categorii_articole c join articole a on(c.c_id = a.a_categorie) where a.a_categorie = " . $row['a_categorie'] . ";";
										 $res = $conn->query($sql3);
										 $row2 = $res->fetch_assoc();
										 echo '<div class="articole-box">
										 		 <div style="float: left; margin-right: 1%;">
													 <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
													 <p> Data: ' . $row['a_data'] . '</p>
		 										 	 <p> Autor: ' . $row['a_autor'] . '</p>
													 <p> Categorie: ' . ucfirst($row2['c_nume']) . '</p>
													 <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
													 '&data='.$row['a_data'].'">Citeste tot articolul</a>
												 </div>
												 <div>
												 	<h3>' . $row['a_titlu'] . '</h3>
													<p>' . $row['a_descriere'] . '</p>
												 </div>
											 </div>';
									 }
									 echo '<div class="pagination-box">';
									 if($total_p > $pn) {
										 echo '<a href="articole.php?pn=' . ($pn-1) . '" style="margin-right: 1.5%;">Prev</a>';
										 echo '<a href="articole.php?pn=' . ($pn+1) . '" style="margin-right: 1.5%;">Next</a>';
									 } else if($total_p == $pn) {
										 echo '<a href="articole.php?pn=' . ($pn-1) . '" style="margin-right: 1.5%;">Prev</a>';
									 }
									 echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
									 echo '<select name="pag" id="select_pag" style="margin-left: 1%;">';
									 for($i = 1; $i <= $total_p; $i++) {
										 if($i == $pn) {
											 echo '<option value="' . $i . '" selected>' . $i . '</option>';
										 } else {
											 echo '<option value="' . $i . '">' . $i . '</option>';
										 }
									 }
									 echo '</select>';
									 echo '</div>';
								 }
							 }
						 }
					 } else {
						 echo '<h2>Niciun articol disponibil momentan!</h2>';
					 }
				 }
				 echo '</div>';
			 } else {
				 echo '<div class="articole-container">';
				 $search = $conn->real_escape_string($_POST['search']);
				 $sql = "select * from articole where a_titlu like ?
				 or a_descriere like ? or a_autor like ? or
				 a_data like ? order by a_data desc";
				 $stmt = $conn->prepare($sql);
				 $stmt->bind_param("ssss", $search, $search, $search, $search);
				 $search = "%".$search."%";
				 $stmt->execute();
				 $result = $stmt->get_result();
				 $stmt->close();
				 if(!$result) {
					 echo '<h2>Momentan aceasta cerere nu este disponibila!</h2>';
					 error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
				 } else {
					 if($result->num_rows > 0) {
						 $total = $result->num_rows;
						 $total_p = ceil($total / 5);
						 if(!isset($_GET['pn']) || intval($_GET['pn']) == 1) {
							 $pn = 1;
							 echo '<h2>Toate articolele</h2>
							 	   <hr/>';
						     $sql2 = "select * from articole where a_titlu like ?
							 or a_descriere like ? or a_autor like ? or
							 a_data like ? order by a_data desc limit ? , 5;";
							 $stmt2 = $conn->prepare($sql2);
							 $pn2 = 5*($pn-1);
							 $stmt2->bind_param("ssssi", $search, $search, $search, $search, $pn2);
							 $stmt2->execute();
							 $result2 = $stmt2->get_result();
							 $stmt2->close();
							 if(!$result2) {
								 echo '<h2>Momentan aceasta cerere nu este disponibila!</h2>';
								 error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
							 } else {
								 echo '<p>' . $total . ' rezultate! </p>';
							 //while($row = $rpp->fetch_assoc()) {
							 	 while($row = $result2->fetch_assoc()) {
									 $sql3 = "select c_nume from categorii_articole c join articole a on(c.c_id = a.a_categorie) where a.a_categorie = " . $row['a_categorie'] . ";";
									 $res = $conn->query($sql3);
									 $row2 = $res->fetch_assoc();
									 echo '<div class="articole-box">
									 		 <div style="float: left; margin-right: 1%;">
												 <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
												 <p> Data: ' . $row['a_data'] . '</p>
	 										 	 <p> Autor: ' . $row['a_autor'] . '</p>
												 <p> Categorie: ' . ucfirst($row2['c_nume']) . '</p>
												 <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
												 '&data='.$row['a_data'].'">Citeste tot articolul</a>
											 </div>
											 <div>
											 	<h3>' . $row['a_titlu'] . '</h3>
												<p>' . $row['a_descriere'] . '</p>
											 </div>
										 </div>';
								 }
							 //}
							 echo '<div class="pagination-box">';
						 	 if($total_p >= 2) {
								echo '<span id="next_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "next_f(this)" val="'. $pn .'">Next</span>';
							 }
							 echo '<label for="pag">Pag: ' . 1 . '/' . $total_p . '</label>';
							 echo '<select name="pag" id="select_pagS" style="margin-left: 1%; display: inline;" onChange = "select_S()">';
							 for($i = 1; $i <= $total_p; $i++) {
								 if($i == $pn) {
									 echo '<option value="' . $i . '" selected>' . $i . '</option>';
								 } else {
									 echo '<option value="' . $i . '">' . $i . '</option>';
								 }
							 }
							 echo '</select>';
							 echo '</div>';
						  }
						 } else {
							 if(!is_numeric($_GET['pn'])) {
								 echo '<div class="articole-container">
										   <div style="display: grid; justify-content: center;">
											 <h2>Pagina ceruta nu exista!</h2>
											 <a href="articole.php">Click aici pentru a merge la articole.</a>
										   </div>';
							 } else {
								 if(intval($_GET['pn']) < 1 || intval($_GET['pn']) > $total_p) {
									 echo '<div class="articole-container">
										 	   <div style="display: grid; justify-content: center;">
												 <h2>Pagina ceruta nu exista!</h2>
												 <a href="articole.php">Click aici pentru a merge la articole.</a>
											   </div>';
								 } else {
									 $pn = intval($_GET['pn']);
									 echo '<h2>Toate articolele</h2>
									 	   <hr/>';
									$sql2 = "select * from articole where a_titlu like ?
	  								or a_descriere like ? or a_autor like ? or
	  								a_data like ? order by a_data desc limit ? , 5;";
	  								$stmt2 = $conn->prepare($sql2);
									$pn2 = 5*($pn-1);
	  								$stmt2->bind_param("ssssi", $search, $search, $search, $search, $pn2);
	  								$stmt2->execute();
	  								$result2 = $stmt2->get_result();
									$stmt2->close();
									if(!$result2) {
										echo '<h2>Momentan aceasta cerere nu este disponibila!</h2>';
	   									error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
									} else {
									 echo '<p>' . $total . ' rezultate! </p>';
									 //while($row = $rpp->fetch_assoc()) {
									 while($row = $result2->fetch_assoc()) {
										 $sql3 = "select c_nume from categorii_articole c join articole a on(c.c_id = a.a_categorie) where a.a_categorie = " . $row['a_categorie'] . ";";
										 $res = $conn->query($sql3);
										 $row2 = $res->fetch_assoc();
										 echo '<div class="articole-box">
										 		 <div style="float: left; margin-right: 1%;">
													 <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
													 <p> Data: ' . $row['a_data'] . '</p>
		 										 	 <p> Autor: ' . $row['a_autor'] . '</p>
													 <p> Categorie: ' . ucfirst($row2['c_nume']) . '</p>
													 <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
													 '&data='.$row['a_data'].'">Citeste tot articolul</a>
												 </div>
												 <div>
												 	<h3>' . $row['a_titlu'] . '</h3>
													<p>' . $row['a_descriere'] . '</p>
												 </div>
											 </div>';
									 }
									 //}
									 echo '<div class="pagination-box">';
									 if($total_p > $pn) {
										 echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. $pn .'">Prev</span>';
										 echo '<span id="next_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "next_f(this)" val="'. $pn .'">Next</span>';
									 } else if($total_p == $pn) {
										 echo '<span id="prev_ctrl" style="margin-right: 1.5%; display: inline;" onClick = "prev_f(this)" val="'. $pn .'">Prev</span>';
									 }
									 echo '<label for="pag">Pag: ' . $pn . '/' . $total_p . '</label>';
									 echo '<select name="pag" id="select_pagS" style="margin-left: 1%; display: inline;" onChange = "select_S()">';
									 for($i = 1; $i <= $total_p; $i++) {
										 if($i == $pn) {
											 echo '<option value="' . $i . '" selected>' . $i . '</option>';
										 } else {
											 echo '<option value="' . $i . '">' . $i . '</option>';
										 }
									 }
									 echo '</select>';
									 echo '</div>';
								 }
							 }
						  }
					   }
					 } else {
						 echo '<p style="margin-top: 3%;">Fara rezultate!</p>';
					 }
				 }
				 echo '</div>';
			 }
			 ?>
		 </div>
		 <div class="wrapper-categorii">
		 	<?php
				$sql1 = "select * from categorii_articole;";
				$result1 = $conn->query($sql1);
				if(!$result1) {
					echo '<h3>Momentan aceasta cerere nu este disponibila!</h3>';
					error_log("Error: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
				} else {
					if($result1->num_rows == 0) {
						echo '<h3>Momentan nu exista nicio categorie</h3>';
					} else {
						echo '<h3>Categorii</h3>';
						echo '<ul>';
						while($row = $result1->fetch_assoc()) {
							echo '<li><span>' . $row['c_nume'] . '</span>(' . $row['c_nr_articole'] . ')</li>';
						}
						echo '</ul>';
					}
				}
			?>
		 </div>
	 </div>
    <?php include "includes/footer.php";?>