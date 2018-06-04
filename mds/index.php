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
</head>
<body>
     <div class="wrapper">
     	<!-- Navigation -->
		<div class="account-box">
			<img src="img/logo.png" class="avatar">
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

     <!-- Header -->


     <section class="top-container">
     <header id="show" class="showcase">

     	<h1>Pro Gains</h1>

     	<p>Lorem ipsum</p>
       <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
       <a class="next" onclick="plusSlides(1)">&#10095;</a>

         <div class="dots" style="text-align:center">
            <span class="dot" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
     </div>
     </header>

     <div class="topbox topbox-1">
		 <?php
		if(!isset($_GET['abonare-nl'])) {
			echo '<h3>Newsletter</h3>
	     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
	     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
				<input type="text" name="nume-nl" placeholder="Nume">
				<input type="text" name="email-nl" placeholder="Email">
				<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
	     	</form>';
		} else {
			if ($_GET['abonare-nl'] == 'succes') {
			  echo '<h3>Newsletter</h3>
		      <p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		      <form class="newsletter-form" action="abonare-newsletter.php" method="post">
				 <input type="text" name="nume-nl" placeholder="Nume">
				 <input type="text" name="email-nl" placeholder="Email">
				 <button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		      </form>
			  <p style="background-color: white; color:green;">V-ati abonat cu succes la newsletter! Multumim!</p>';
			} else if ($_GET['abonare-nl'] == 'error-exista') {
				echo '<h3>Newsletter</h3>
		     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
					<input type="text" name="nume-nl" placeholder="Nume">
					<input type="text" name="email-nl" placeholder="Email">
					<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		     	</form>
				<p style="background-color: white; color:red;">Aceasta adresa de email exista deja!</p>';
			} else if ($_GET['abonare-nl'] == 'error-nume') {
				echo '<h3>Newsletter</h3>
		     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
					<input type="text" name="nume-nl" placeholder="Nume">
					<input type="text" name="email-nl" placeholder="Email">
					<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		     	</form>
				<p style="background-color: white; color:red;">Nume invalid!</p>';
			} else if ($_GET['abonare-nl'] == 'error-email') {
				echo '<h3>Newsletter</h3>
		     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
					<input type="text" name="nume-nl" placeholder="Nume">
					<input type="text" name="email-nl" placeholder="Email">
					<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		     	</form>
				<p style="background-color: white; color:red;">Email invalid!</p>';
			} else if ($_GET['abonare-nl'] == 'error-q') {
				echo '<h3>Newsletter</h3>
		     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
					<input type="text" name="nume-nl" placeholder="Nume">
					<input type="text" name="email-nl" placeholder="Email">
					<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		     	</form>
				<p style="background-color: white; color:red;">Cererea nu este disponibila momentan, ne cerem scuze!</p>';
			} else {
				echo '<h3>Newsletter</h3>
		     	<p class="price">Vrei sa fii informat prin email despre reduceri, promotii si noutati?</p>
		     	<form class="newsletter-form" action="abonare-newsletter.php" method="post">
					<input type="text" name="nume-nl" placeholder="Nume">
					<input type="text" name="email-nl" placeholder="Email">
					<button id="button-newsletter" type="submit" name="submit-nl">Aboneaza-te</button>
		     	</form>';
			}
		}
		?>
     </div>
      <div class="topbox topbox-2">
     	<h4>Abonament Pro</h4>
     	<p class="price">250 RON</p>
     	<a href="" class="btn">Cumpara Acum</a>
     </div>
     </section>
      <section class="boxes">
      	<div class="box">
      		<h3>Despre Noi</h3>
      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      		tempor incididunt ut labore et dolore magna aliqua.</p>
      	</div>
      	<div class="box">
      		<h3>De ce sa aplici</h3>
      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      		tempor incididunt ut labore et dolore magna aliqua.</p>
      	</div>
      </section>

      <footer>
      	<p>Pro Gains &copy; 2018</p>
      </footer>
  </div>
  <script type="text/javascript" src="main.js"></script>
</body>
</html>
