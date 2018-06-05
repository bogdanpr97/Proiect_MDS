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
<?php include "includes/head.php";?>
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
			<section class="not-found-wrapper">
				<h2>Eroare 404: Pagina nu a fost gasita!</h2>
				<p>Pagina ceruta nu exista.</p>
			</section>
<?php include "includes/footer.php";?>
