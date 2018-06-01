<?php
	require_once '../../../../dbC.php';
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
    <link rel="stylesheet" type="text/css" href="../style.css">
	<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
    <style media="screen">
		.sidebar-contact {
			grid-area: sidebar-contact;
		}
		.li-sidebar-contact {
			width: 50%;
			background-color: var(--primary);
			color: var(--light);
			border: 2px solid var(--dark);
			padding: 2%;
			margin-bottom: 2%;
			text-align: center;
			transition: 0.5s linear;
		}
		.li-sidebar-contact:hover {
			background-color: var(--dark);
		}
        .wrapper {
            grid-template-columns: 0.4fr 1fr 0.1fr;
            grid-template-areas:
            "account-box account-box account-box"
            "main-nav main-nav main-nav"
            "sidebar-contact wrapper-transp-ret . "
            "footer footer footer";
        }
        .wrapper-transp-ret {
            grid-area: wrapper-transp-ret;
        }
    </style>
</head>
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
	                    echo '<div style="width: 30%; text-align: right;"><span style="margin-right: 1.5%;"><img src="../img-profil-utilizatori/' . $img . '" style="position: relative; top: 0.65rem; width: 35px; height: 35px; margin: 0 1%;"> <a style="text-decoration: none; color: var(--dark);" href="profil.php?username=' . $_SESSION["uname"] . '">' . $_SESSION["uname"] . '</a></span>';
	                    echo '<a class="btn" href="logout.php">Logout</a></div>';
	                    $result->close();
	                    $stmt->close();
	 				}
	 			?>
	         </div>
            <nav class="main-nav">
            <ul>
                <li>
                    <a href="../index.php">Acasa</a>
                </li>
                <li>
                    <a href="../produse.php">Produse</a>
                </li>
                <li>
                    <a href="../articole.php">Articole</a>
                </li>
                <li>
                    <a href="../contact.php">Contact</a>
                </li>
				<li>
					<?php
					if(isset($_SESSION['produse']) && count($_SESSION['produse']) > 1) {
						echo '<a id="link-cos" href="../cos.php">Cosul meu(' .  count($_SESSION['produse']) . ' produse)</a>';
					} else if(isset($_SESSION['produse']) && count($_SESSION['produse']) == 0 || !isset($_SESSION['produse'])) {
						echo '<a id="link-cos" href="../cos.php">Cosul meu(0 produse)</a>';
					} else {
						echo '<a id="link-cos" href="../cos.php">Cosul meu(1 produs)</a>';
					}
					?>
				</li>
            </ul>
         </nav>
             <div class="sidebar-contact">
               <ul style="list-style-type: none;">
                   <a style="text-decoration: none;"href="cum-cumpar.php"><li class="li-sidebar-contact">Cum cumpar</li></a>
                   <a style="text-decoration: none;"href="confidentialitate.php"><li class="li-sidebar-contact">Confidentialitate</li></a>
                   <a style="text-decoration: none;"href="transport-retur.php"><li class="li-sidebar-contact">Transport si retur</li></a>
                   <a style="text-decoration: none;"href="termeni-conditii.php"><li class="li-sidebar-contact">Termeni si conditii</li></a>
                   <a style="text-decoration: none;"href="intrebari-frecvente.php"><li class="li-sidebar-contact">Intrebari frecvente</li></a>
                   <a style="text-decoration: none;"href="../contact.php"><li class="li-sidebar-contact">Contact</li></a>
               </ul>
           </div>
         <div class="wrapper-transp-ret">
              <div class="transp-ret-container">
                  <section>
                      <h2>Transport si retur</h2>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  </section>
             </div>
         </div>
     </div>
         <footer>
           <p>Pro Gains &copy; 2018</p>
         </footer>
     </div>
       <script type="text/javascript" src="../main.js"></script>
     </body>
 </html>
