<?php
    require_once '../../../dbC.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    session_start();
    if(!isset($_GET['cod_verificare']) || !isset($_GET['email'])) {
        header("Location: page-not-found.php");
        exit();
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
    <style media="screen">
        .resetare-parola-container {
            grid-area: resetare-parola-container;
            display: grid;
            align-items: center;
            justify-content: center;
        }
        .wrapper {
            grid-template-areas:
            "account-box account-box account-box"
            "main-nav main-nav main-nav"
            "resetare-parola-container resetare-parola-container resetare-parola-container"
            "footer footer footer";
        }
    </style>
</head>
<body>
     <div class="wrapper">
     	<!-- Navigation -->
	    <div class="account-box">
        	<a class="btn" href="login.php">Login</a>
        	<a class="btn" href="register.php">Register</a>
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
         <div class="resetare-parola-container">
             <?php
                 function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
                     $pieces = [];
                     $max = mb_strlen($keyspace, '8bit') - 1;
                     for ($i = 0; $i < $length; ++$i) {
                         $pieces []= $keyspace[random_int(0, $max)];
                     }
                     return implode('', $pieces);
                 }
                 $cod_verificare = filter_var(trim($_GET["cod_verificare"]), FILTER_SANITIZE_STRING);
                 $email = filter_var(trim($_GET["email"]), FILTER_SANITIZE_EMAIL);
                 if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email validation
                     echo '<h2>Email-ul nu este valid!</h2>';
                 } else {
                     $parolaNoua = random_str(16);
                     $hashedParolaNoua = password_hash($parolaNoua, PASSWORD_DEFAULT);
                     $sql = "update utilizatori set u_password = ? where u_cod_resetare_parola = ? and u_email = ? ;";
                     if($stmt = $conn->prepare($sql)) {
                         $stmt->bind_param("sss", $hashedParolaNoua, $cod_verificare, $email);
                         $stmt->execute();
                         if($stmt->affected_rows == 0) {
                             echo '<h2>Email sau cod de verificare invalid.</h2>';
                         } else {
                             $stmt->close();
                             $sql2 = "update utilizatori set u_cod_resetare_parola = null where u_email = ? ;";
                             $stmt2 = $conn->prepare($sql2);
                             $stmt2->bind_param("s", $email);
                             $stmt2->execute();
                             $stmt2->close();
                             $mail = new PHPMailer(true);
                             try {
                                 $mail->isSMTP();
                                 $mail->Host = 'smtp.mail.yahoo.com';
                                 $mail->SMTPAuth = true;
                                 $mail->Username = 'robertgrmds@yahoo.com';
                                 $mail->Password = 'zxc567bnM0';
                                 $mail->Port = 465;
                                 $mail->SMTPSecure = "ssl";
                                 $mail->setFrom('robertgrmds@yahoo.com');
                                 $mail->addAddress($email);
                                 $mesaj = "Mail cu noua parola a contului dumneavoastra <br>\r\n
                                           Va sugeram ca dupa intrarea in cont sa modificati imediat parola cu una gandita de dumneavoastra. <br> Noua parola: " . $parolaNoua;
                                 $mail->isHTML(true);
                                 $mail->Subject = "Noua parola cont Pro Gains";
                                 $mail->Body = $mesaj;
                                 $mail->AltBody = $mesaj;
                                 $mail->send();
                             } catch (Exception $e) {
                                 echo '<h2>A fost intampinata o problema cu aceasta cerere, incercati din nou cererea de resetare a parolei!';
                             }
                             echo '<h2>Noua parola a fost trimisa pe email.</h2>';
                         }
                     } else {
                         echo '<h2>A fost intampinata o problema cu aceasta cerere, ne cerem scuze, incercati mai tarziu!';
                     }
                 }
             ?>
         </div>
     </div>
     <footer>
        <p>Pro Gains &copy; 2018</p>
     </footer>
 </div>
 <script type="text/javascript" src="main.js"></script>
</body>
</html>
