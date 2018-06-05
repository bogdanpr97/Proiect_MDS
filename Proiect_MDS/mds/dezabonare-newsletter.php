<?php
    require_once '../../../dbC.php';
    if(!isset($_GET['cod_verificare']) || !isset($_GET['email'])) {
        header("Location: page-not-found.php");
        exit();
    } else {
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
        echo '<!DOCTYPE html>
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
            <style>
            .dezabonare-nl-container {
                grid-area: dezabonare-container;
                text-align: center;
            }
            .wrapper{
                display: grid;
                grid-gap: 20px;
                grid-template-columns: 0.4fr 1fr 0.1fr;
                grid-template-areas:
                "account-box account-box account-box"
                "main-nav main-nav main-nav"
                "dezabonare-container dezabonare-container dezabonare-container"
                "footer footer footer";
            }
            </style>
        </head>
        <body>
             <div class="wrapper">
             	<!-- Navigation -->'
                ?>
        			<?php
        				if(!isset($_SESSION["uid"])) {
                            echo '<div class="account-box">';
        					echo '<a class="btn" href="login.php">Login</a>
        		        		  <a class="btn" href="register.php">Register</a>';
                            echo '</div>';
        				} else {
                            echo '<div class="account-box">';
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
                            echo '</div>';
        				}
          echo '<nav class="main-nav">
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
                    <li>';?>
        				<?php
        				if(isset($_SESSION['produse']) && count($_SESSION['produse']) > 1) {
        					echo '<a id="link-cos" href="cos.php">Cosul meu(' .  count($_SESSION['produse']) . ' produse)</a>';
        				} else if(isset($_SESSION['produse']) && count($_SESSION['produse']) == 0 || !isset($_SESSION['produse'])) {
        					echo '<a id="link-cos" href="cos.php">Cosul meu(0 produse)</a>';
        				} else {
        					echo '<a id="link-cos" href="cos.php">Cosul meu(1 produs)</a>';
        				}
        			echo '</li>
             	</ul>
             </nav>
             <div class="dezabonare-nl-container">';
        $cod_verificare = filter_var(trim($_GET["cod_verificare"]), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_GET["email"]), FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email validation
            echo '<h2>Email-ul nu este valid!</h2>';
        } else {
            $sql = "delete from abonati_newsletter where a_cod_verificare = ? and a_email = ? ;";
            if($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $cod_verificare, $email);
                $stmt->execute();
                if($stmt->affected_rows == 0) {
                    echo '<h2>Acest email nu este abonat la newsletter-ul Pro Gains sau codul de verificare nu corespunde.</h2>';
                } else {
                    echo '<h2>V-ati dezabonat cu succes de la newsletter-ul Pro Gains.</h2>';
                }
            } else {
                echo '<h2>A fost intampinata o problema cu aceasta cerere, ne cerem scuze, incercati mai tarziu!';
            }
        }
        echo '</div>
              <footer>
              	<p>Pro Gains &copy; 2018</p>
              </footer>
              </div>
              <script type="text/javascript" src="main.js"></script>
        </body>
        </html>';
    }
