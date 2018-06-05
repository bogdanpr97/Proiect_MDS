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
        body{
          background-image: url('img-site/login1.jpeg');

          background-repeat: no-repeat;

        }
        .loginbox{

           width: 580px;
           height: 810px !important;
           background-color: rgba(0,0,0,0.5);
           color: var(--light);
           position: absolute;
           top:50%;
           left: 50%;
           transform: translate(-50%,-50%);
           box-sizing: border-box;
           padding: 70px 30px;
        }
        #login-register{
        	height: 80%;
        }
        #login-register > .avatar{
        	top: -12%;
        	}
        .avatar{
           width: 200px;
           height: 200px;
           position: absolute;

           top: -20%;
           left: 33%;
        }
        .account-box > .avatar{
        	width: 150px;
           height: 150px;
        	position: absolute;
        	left: 3%;
        	top: -2%;
        }
        .loginbox h1{
        	margin:0;
        	padding: 0 0 20px;
        	text-align: center;
            font-size: 3rem;
        }
        .loginbox p{
        	margin: 0;
        	padding: 0;
        	font-weight: bold;
        	font-size: 1.6rem;
        }
        .loginbox input{
        	width: 100%;
        	margin-bottom: 5%;
        }

        .loginbox input[type="text"],input[type="password"]{
         border: none;
         border-bottom: 1px solid var(--light);
         color: var(--primary);
         background:transparent;
         outline: none;
         font-size: 1.5rem;
        }
        ::placeholder{
        	color: darkgrey;
        }

        .loginbox input[type="submit"]{
        	border :none;
        	outline: none;

        	background: var(--dark);
        	color: var(--light);
        	font-size: 2rem;
        	border-radius: 20px;
        }

        .loginbox input[type="submit"]:hover{
        	cursor: pointer;
            background-color: var(--primary);
            color: var(--dark);
        }
        .loginbox a{
        text-decoration:none;
        font-size: 1.5rem;
        color: var(--light);
        margin-left: 10%;
        font-weight: bold;
        float: left;
        }
		#register-error {
			margin-top: -1%;
			margin-bottom: 0%;
            overflow: auto;
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
        <div class="loginbox" id="login-register">
          <img src="img-site/logo.png" class="avatar">
          <h1>Inregistrare</h1>
          <form action="register.php" method="post">
            <p>Username</p>
            <span>Doar litere, cifre, "." , "-", "_" si minim 5 caractere.</span>
            <input type="text" name="username" placeholder="Username">
            <p>Email</p>
            <input type="text" name="email" placeholder="Email">
            <p>Parola</p>
            <span>Parola trebuie sa aiba minim 8 caractere, o litera mica, o litera mare, o cifra.</span>
            <input type="password" name="password"  placeholder="Parola">
            <p>Verificare Parola</p>
             <input type="password" name="cpassword"  placeholder="Verifica parola">
            <input type="submit" name="submit-register" value="Inregistrare">
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
                        echo '<a href="index.php" style="margin-left: 0;">Acasa</a>';
					}
				?>
			 </div>
         </div>
  <script type="text/javascript" src="main.js"></script>
</body>
</html>
