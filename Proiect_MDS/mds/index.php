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
<?php include "includes/head.php"?>
    
    <style>

        .wrapper{
            
            grid-template-columns: 1fr 1fr 0.25fr;
 grid-template-areas:
 'menu menu menu'
 'main-nav main-nav main-nav'
 'top-container top-container top-container'
 'boxes boxes boxes'
 'footer footer footer';
        }
</style>
    
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
      <?php include "includes/footer.php";?>
