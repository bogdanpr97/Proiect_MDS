<?php
	include_once '../../../dbC.php';
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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pro Gains</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width , initial-scale=1">
	<script
  	  src=" http://code.jquery.com/jquery-3.3.1.min.js "
  	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  	  crossorigin="anonymous"></script>
      <link rel="stylesheet" type="text/css" href="style.css">
	<style>
		.not-found-wrapper {
			grid-area: not-found-wrapper;
			display: grid;
			grid-template-columns: 1fr;
			grid-template-rows: 1fr 1fr;
			text-align: center;
		}
		.wrapper {
			grid-template-areas:
			"account-box account-box account-box"
			"main-nav main-nav main-nav"
			" not-found-wrapper not-found-wrapper not-found-wrapper"
			"footer footer footer";
		}
	</style>
	<body>
	     <div class="wrapper">
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
			<section class="not-found-wrapper">
				<h2>Eroare 404: Pagina nu a fost gasita!</h2>
				<p>Pagina ceruta nu exista.</p>
			</section>
			<footer>
			  <p>Pro Gains &copy; 2018</p>
			</footer>
		 </div>
		 <script type="text/javascript" src="main.js"></script>
	</body>
</html>
