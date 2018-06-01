<?php
	require_once '../../../dbC.php';
	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
	session_start();
    if(isset($_SESSION['uid'])) {
        header("Location: index.php");
        exit();
    }
    if(isset($_POST['submit-register'])) {
        $username = $conn->real_escape_string(filter_var(trim($_POST["username"]), FILTER_SANITIZE_STRING));
        $email = $conn->real_escape_string(filter_var(trim($_POST["email"]), FILTER_SANITIZE_STRING));
        $password = $conn->real_escape_string($_POST["password"]);
        $cpassword = $conn->real_escape_string($_POST["cpassword"]);
        if(empty($username) || empty($email)  || empty($password) || empty($cpassword)) {
            header("Location: register.php?error=empty");
            exit();
        } else {
            if($password != $cpassword) {
                header("Location: register.php?error=password-match");
                exit();
            } else {
                if(!preg_match("/^[a-zA-Z0-9_.-]*$/", $username) || strlen($username) < 5 || !preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strpos(trim($username), "admin") !== false || strpos(trim($username), "moderator") !== false) {
                    header("Location: register.php?error=invalid");
                    exit();
                } else {
                    $sql = "select * from utilizatori where u_username = ? or u_email = ? ;";
                    if(!($stmt = $conn->prepare($sql))) {
                        header("location: register.php?error=error");
                        error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                        exit();
                    } else {
                        $stmt->bind_param("ss", $username, $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows == 1) {
                            header("Location: register.php?error=existent");
                            exit();
                        } else {
                                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                                $sqlInsert = "insert into utilizatori (u_email, u_username, u_password, u_data_inregistrare, u_verificare_token) values ( ? , ? , ? , now() , ? );";
                                if(!($stmt2 = $conn->prepare($sqlInsert))) {
                                    header("location: register.php?error=error");
                                    error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                    exit();
                                } else {
									$key = "cod_verificare_inregistrare";
		                            $time = time();
		                            $cod_verificare = hash_hmac('sha256', $time, $key);
                                    $stmt2->bind_param("ssss", $email, $username, $hashedPass, $cod_verificare);
                                    $stmt2->execute();
                                    if($stmt2->affected_rows == 1) {
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
			                                $mesaj = "Va multumim ca v-ati inregistrat pe site-ul Pro Gains\r\n
			                                         Pentru a va confirma inregistrarea, introduceti codul urmator la prima intrare in cont : " . $cod_verificare;
			                                $mail->isHTML(true);
			                                $mail->Subject = "Inregistrare Pro Gains";
			                                $mail->Body = $mesaj;
			                                $mail->AltBody = $mesaj;
			                                $mail->send();
			                            } catch (Exception $e) {
			                                header("Location: register.php?register=error");
			                                exit();
			                            }
                                        header("Location: register.php?register=success");
                                        exit();
                                    } else {
                                        header("location: register.php?error=error");
                                        error_log("Error: " . $stmt2->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
                                        exit();
                                    }
                                }
                        }
                    }
                }
            }
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
    <style media="screen">
        .register-container {
            grid-area: register-container;
            display: grid;
            align-items: center;
            justify-content: center;
        }
        .wrapper {
            grid-template-areas:
            "account-box account-box account-box"
            "main-nav main-nav main-nav"
            "register-container register-container register-container"
            "footer footer footer";
        }
        .label-register-form {
            margin-right: 2%;
        }
        .register-container div {
            margin-bottom: 2%;
        }
		#register-error {
			margin-top: 2%;
			margin-bottom: 0%;
		}
    </style>
	<script>
		$(document).ready(function () {
			$("#buton-parola-uitata").on('click', function() {
				$("#form-parola-uitata").toggle();
			});
		})
	</script>
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
         <div class="register-container">
             <form class="" action="register.php" method="post" style="width: 1000px;">
                 <div><label class="label-register-form" for="username">Username</label><input type="text" name="username" value="" placeholder="Username" style="margin-right: 2%;">Doar litere, cifre, "." , "-", "_" si minim 5 caractere</div>
                 <div><label class="label-register-form" for="email">Email</label><input type="text" name="email" value="" placeholder="Email"></div>
                 <div><label class="label-register-form" for="password">Parola</label><input type="password" name="password" value="" placeholder="Parola" style="margin-right: 2%;">Parola trebuie sa aiba minim 8 caractere, o litera mica, o litera mare, o cifra</div>
                 <div><label class="label-register-form" for="cpassword">Confirmare parola</label><input type="password" name="cpassword" value="" placeholder="Confirmare parola"></div>
                 <button  id="buton-register-form" type="submit" name="submit-register">Inregistreaza-te</button>
             </form>
			 <div id="register-error">
			 	<?php
					if(isset($_GET['error'])) {
						if($_GET['error'] == 'empty') {
							echo '<h4>Trebuie sa completati toate campurile.</h4>';
						} else if($_GET['error'] == 'password-match') {
							echo '<h4>Parolele nu corespund.</h4>';
						} else if($_GET['error'] == 'existent') {
							echo '<h4>Username-ul sau email-ul exista deja.</h4>';
						} else if($_GET['error'] == 'invalid') {
							echo '<h4>Campurile nu corespund cerintelor.</h4>';
						} else if($_GET['error'] == 'erorr') {
							echo '<h4>A fost intampinata o problema, incercati mai tarziu, ne cerem scuze!</h4>';
						}
					}
					if(isset($_GET['register']) && $_GET['register'] == 'success') {
						echo '<h4>Contul a fost creat, veti primi un mail cu codul de verificare pe care trebuie sa-l introduceti la prima intrare in cont pentru a-l activa.</h4>';
					}
				?>
			 </div>
         </div>
      </div>
      <footer>
      	<p>Pro Gains &copy; 2018</p>
      </footer>
  </div>
  <script type="text/javascript" src="main.js"></script>
</body>
</html>
