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
     	<nav class="navbar navbar-inverse navbar-fixed-top" style="grid-area:menu">
     	<div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Pro Gains</a>
        </div>
        <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" aria-expanded="false" style="height: 1px;">
        <ul class="nav navbar-nav">
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
					echo '<a id="link-cos" href="cos.php"><img style="max-width:20px;"src="img-site/shopping_basket.png"></a>';
				} else if(isset($_SESSION['produse']) && count($_SESSION['produse']) == 0 || !isset($_SESSION['produse'])) {
					echo '<a id="link-cos" href="cos.php"><img style="max-width:20px;"src="img-site/cos.png"></a>';
				} else {
					echo '<a id="link-cos" href="cos.php"><img style="max-width:20px;" src="img-site/cos.png"></a>';
				}
				?>
			</li>
            <?php
                if(isset($_SESSION["uprivilegiu"])){
                    if($_SESSION["uprivilegiu"] == 2){
                        echo "<li><a href='Admin/index.php'>Admin</a></li>";
                    }
                }
				if(!isset($_SESSION["uid"])) {
					echo '<li><a href="login.php">Login</a></li>
		        		  <li><a href="register.php">Register</a><li>';
				} else {
					$sqlImg = "select if(img_profil is NULL, 'default.jpg', img_profil) as img from utilizatori where u_username = ? ;";
					$stmt = $conn->prepare($sqlImg);
					$stmt->bind_param("s", $_SESSION['uname']);
					$stmt->execute();
					$result = $stmt->get_result();
					$rowImg = $result->fetch_assoc();
					$img = $rowImg['img'];
					echo '<li style="height:50px; display:grid; align-items: center; justify-content: center; grid-template-columns: 1fr; grid-template-rows:1fr;"><div id="divMare" style="display: grid; grid-template-columns: 1fr; align-items: center;
     justify-content: left;"><div id="divMare2"><div id="divImgProf" style="display:inline-block;"><img id="pozaProfil" src="img-profil-utilizatori/' . $img . '"></div> <div id="divScris" style="margin-left:5px; display:inline-block;"><a style="text-decoration: none; color: var(--light);" href="profil.php?username=' . $_SESSION["uname"] . '">' . $_SESSION["uname"] . '</a></div></div></div></li>';
					echo '<li><a href="logout.php">Logout</a></li>';
					$result->close();
					$stmt->close();
				}
			?>
     	</ul>
     	</div>
            </div>
     </nav>

     <!-- Header -->

