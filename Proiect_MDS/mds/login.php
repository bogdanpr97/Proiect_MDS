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
    if(isset($_SESSION['uid'])) {
        header("Location: index.php");
        exit();
    }
    if(isset($_POST['login-submit'])) {
        $userOrEmail = $conn->real_escape_string(filter_var(trim($_POST["username-email"]), FILTER_SANITIZE_STRING));
        $password = $conn->real_escape_string(filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING));
        if(empty($userOrEmail) || empty($password)) {
			if(isset($_GET['cv'])) {
				header("Location: login.php?cv=1&login=empty");
	            exit();
			} else {
				header("Location: login.php?login=empty");
	            exit();
			}
        } else {
            $sql = "select * from utilizatori where ( u_username = ? or u_email = ? );";
            if(!$stmt = $conn->prepare($sql)) {
				if($_GET["cv"] == 1) {
					header("Location: login.php?cv=1&login=error");
					error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
		            exit();
				} else {
					header("Location: login.php?login=error");
					error_log("Error: " . $stmt->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
		            exit();
				}
            } else {
                $stmt->bind_param("ss", $userOrEmail, $userOrEmail);
                $stmt->execute();
                $result = $stmt->get_result();
				$stmt->close();
                if($result->num_rows < 1) {
					if($_GET["cv"] == 1) {
						header("Location: login.php?cv=1&login=error");
			            exit();
					} else {
						header("Location: login.php?login=error");
			            exit();
					}
                } else {

					$row = $result->fetch_assoc();
					$sqlBan = "select ifnull(data_ultima_restrictie, 'allow') as status from utilizatori where u_id = " . $row['u_id'] . ";";
					$resultBan = $conn->query($sqlBan);
					$rowBan = $resultBan->fetch_assoc();
					if($rowBan['status'] != 'allow') {
						$sqlBan2 = "select TIME_TO_SEC(TIMEDIFF(data_ultima_restrictie, now())) as data from utilizatori where u_id = " . $row['u_id'] . ";";
						$resultBan2 = $conn->query($sqlBan2);
						$rowBan2 = $resultBan2->fetch_assoc();
						if($rowBan2['data'] > 0) {
							header("Location: login.php?login=error-ban&ban=" . $rowBan['status']);
							exit();
						} else {
							$sqlDeleteData = "update utilizatori set data_ultima_restrictie = null where u_id = " . $row['u_id'] . ";";
							$conn->query($sqlDeleteData);
						}
					}
                    $passwordCheck = password_verify($password, $row["u_password"]);
                    if($passwordCheck == false) {
						if($_GET["cv"] == 1) {
							header("Location: login.php?cv=1&login=error");
				            exit();
						} else {
							header("Location: login.php?login=error");
				            exit();
						}
                    } else if($passwordCheck == true) {
						if(!isset($_POST["cod_verificare"])) {
							if(!isset($_POST["rememberme"])) {
								if($row["u_verificat"] == 0) {
		                            header("Location: login.php?cv=1");
		                            exit();
								} else {
									$_SESSION['uprivilegiu'] = $row['u_privilegiu_id'];
									$_SESSION['uid'] = $row['u_id'];
		                            $_SESSION['uname'] = $row['u_username'];
		                            header("Location: index.php");
		                            exit();
								}
	                        } else {
								if($row["u_verificat"] == 0) {
		                            header("Location: login.php?cv=1");
		                            exit();
								} else {
									$token1 = $row['u_id'] . random_bytes(256);
									$token2 = random_bytes(256);
									$token = $token1 . $token2;
									$time = time();
									$cookie = hash_hmac('sha256', $token, $time);
									$sql = "update utilizatori set u_rememberme = '" . $cookie . "' where u_id = " . $row['u_id'] . ';';
									$result = $conn->query($sql);
									if(!$result) {
										error_log("ErrorT: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
									}
									setcookie('rememberme', $cookie, time() + 60*60*24*7*4*12*3);
									$_SESSION['uprivilegiu'] = $row['u_privilegiu_id'];
									$_SESSION['uid'] = $row['u_id'];
		                            $_SESSION['uname'] = $row['u_username'];
									header("Location: index.php");
		                            exit();
								}
	                        }
						} else {
							$cod_verificare = $conn->real_escape_string(filter_var(trim($_POST["cod_verificare"]), FILTER_SANITIZE_STRING));
							$sql = "update utilizatori set u_verificat = 1 where u_verificare_token = ? and u_id = ? ;";
							if($stmtCod = $conn->prepare($sql)) {
								$stmtCod->bind_param("ss", $cod_verificare, $row["u_id"]);
								$stmtCod->execute();
								if($stmtCod->affected_rows == 1) {
									$stmtCod->close();
									if(!isset($_POST["rememberme"])) {
										$sqlStergeToken = "update utilizatori set u_verificare_token = null where u_id = " . $row["u_id"] . ';';
										if(!($result = $conn->query($sqlStergeToken))) {
											error_log("ErrorT: " . $conn->error . PHP_EOL, 3, "../../logsMDS/errorLog.txt");
										}
										$_SESSION['uprivilegiu'] = $row['u_privilegiu_id'];
										$_SESSION['uid'] = $row['u_id'];
				                        $_SESSION['uname'] = $row['u_username'];
										header("Location: index.php");
				                        exit();
			                        } else {
										$token = $row['u_id'] . random_bytes(128);
										$time = time();
										$cookie = hash_hmac('sha256', $time, $token);
										$sql = "update utilizatori set u_rememberme = " . $cookie . " where u_id = " . $row['u_id'] . ';';
										$conn->query($sql);
										setcookie('rememberme', $cookie, time() + 60*60*24*7*4*12*3);
										$_SESSION['uprivilegiu'] = $row['u_privilegiu_id'];
										$_SESSION['uid'] = $row['u_id'];
			                            $_SESSION['uname'] = $row['u_username'];
										header("Location: index.php");
			                            exit();
			                        }
								} else {
									if($_GET["cv"] == 1) {
										header("Location: login.php?cv=1&login=error");
							            exit();
									} else {
										header("Location: login.php?login=error");
							            exit();
									}
								}
							} else {
								if($_GET["cv"] == 1) {
									header("Location: login.php?cv=1&login=error");
						            exit();
								} else {
									header("Location: login.php?login=error");
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
        .loginbox{
           width: 550px;
           height: 520px;
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
        .loginbox input:not(.no-style){
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
        .loginbox a, .loginbox #buton-parola-uitata{
        text-decoration:none;
        font-size: 1.5rem;
        color: var(--light);
        margin-left: 10%;
        font-weight: bold;
        float: left;
        }
        #buton-parola-uitata {
            background-color: transparent;
            border: 0;
            cursor: pointer;
        }
        #buton-login-form {
            margin-top: 2%;
        }
		#login-error {
			margin-bottom: 0%;
		}
        #form-parola-uitata {
            margin-top: 2%;
        }
        body{
          background-image: url('img-site/login1.jpeg');

          background-repeat: no-repeat;

        }
    </style>
	<script>
		$(document).ready(function () {

			$.urlParam = function(name){
                var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }

			$("#buton-parola-uitata").on('click', function() {
				$("#form-parola-uitata").toggle();
                if($("#form-parola-uitata").css("display") == 'none') {
                    $(".loginbox").css("height", '520');
                } else {
                    $(".loginbox").css("height", '650');
                }
			});

			if($.urlParam("login") == 'error' || $.urlParam("login") == 'error' || $.urlParam("error") != 0 || $.urlParam("et") != 0) {
				$(".loginbox").css("height", '565');
			}
		})
	</script>
</head>
<body>
        <div class="loginbox">
             <img src="img-site/logo.png" class="avatar">
			 <h1>Login</h1>
			 <?php
				if(isset($_GET["cv"])) {
					if($_GET["cv"] == 1) {
						echo '<form class="" action="login.php?cv=1" method="post">';
					} else {
						echo '<form class="" action="login.php" method="post">';
					}
				} else {
					echo '<form class="" action="login.php" method="post">';
				}
			 ?>
                 <p>Username</p>
                 <input type="text" name="username-email" placeholder="Username/email">
                 <p>Parola</p>
                 <input type="password" name="password"  placeholder="Parola">
                 <p>Remember me
                 <input class="no-style" type="checkbox" name="rememberme" value=""></p>
				 <?php
				 	if(isset($_GET["cv"])) {
						if($_GET["cv"] == 1) {
							echo '<p>Cod verificare</p><input type="text" name="cod_verificare" placeholder="Cod Verificare">';
						}
					}
				 ?>
                 <input id="buton-login-form" type="submit" name="login-submit" value="Inainte">
                 </form>
                 <div style="overflow: auto;">
                     <a href="register.php">Nu am cont</a>
                     <button id="buton-parola-uitata" type="button" name="button">Am uitat parola</button>
                 </div>
    				<form id="form-parola-uitata" action="parola-uitata.php" method="post" style="display: none;">
    					<p style="font-size: 1.2rem;">Email pentru a trimite confirmarea resetarii parolei</p><input type="text" name="email-parola-uitata" value="" placeholder="Email">
    					<input type="submit" name="buton-email-pu" value="Trimite mail de confirmare">
    				</form>
			 <?php
			 	if(isset($_GET["et"]) && !isset($_GET['error=invalid'])) {
					echo '<h4>Mailul pentru confirmarea resetarii parolei a fost trimis</h4>';
				} else if(isset($_GET['error']) && $_GET['error'] == "invalid" && !isset($_GET['et'])) {
					echo '<h4>Emailul este invalid sau nu este inregistrat.</h4>';
				}
			 ?>
			 <div id="login-error">
	 		   <?php
	 			   if(isset($_GET['login']) && ($_GET['login'] == 'error' || $_GET['login'] == 'empty')) {
	 				   echo '<h4>Eroare la incercarea de a intra in cont, verificati daca ati introdus corect campurile.</h4>';
	 			   } else if(isset($_GET['login']) && $_GET['login'] == 'error-ban') {
	 				   echo '<h4>Contul este blocat pana in data: ' . $_GET['ban'] . '.</h4>';
	 			   }
	 		   ?>
	 	   </div>
        </div>
  <script type="text/javascript" src="main.js"></script>
</body>
</html>
