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

    <link rel="stylesheet" type="text/css" href="style.css">

	<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
    <style media="screen">
		.sidebar-contact {
			grid-area: sidebar-contact;
			
			border-right: 3px solid var(--primary);
		}
		.sidebar-contact > ul {
			padding:0px;
		}
		.li-sidebar-contact {
			width: 50%;
			background-color: var(--primary);
			color: var(--light);
			border: 2px solid var(--shadow);
			padding: 5%;
			margin-bottom: 2%;
			text-align: center;
			transition: 0.5s linear;
          
		}
		.li-sidebar-contact:hover {
			background-color: var(--dark);
			width: 60%;
            
		}
        .wrapper {
            grid-template-columns: 0.4fr 1fr 0.1fr;
            grid-template-areas:
            "account-box account-box account-box"
            "main-nav main-nav main-nav"
            "sidebar-contact wrapper-contact wrapper-contact "
            "footer footer footer";
        }
        .wrapper-contact {
            grid-area: wrapper-contact;
          
        }
        h2{
        	font-size: 3rem;
        }
        h2,h3{
      
        	text-align:center;
        }
        .contact-form {

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .contact-container {

            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
            "contact-telefon contact-email"
            "contact-posta contact-utile"
            "contact-form contact-form";
        }
        #box1{
        	font-size: 1.5rem;
        	margin: 2%;
        	background: var(--dark);
        	color: var(--light);
	text-align: justify;
	padding: 1.5rem;
	box-shadow: var(--shadow);
        }
        .contact-email {
            grid-area: contact-email;
           
	
        }
        .contact-telefon {
            grid-area: contact-telefon;
            
        }
        .contact-posta {
            grid-area: contact-posta;

        }
        .contact-form {
            grid-area: contact-form;
        }
		.label-contact-form {
			margin-right: 2%;
		}
		#result-contact-form {
			margin-top: 3%;
		}
		.contact-form{
			background-color: #618685;
		}
		.form-contact input[type="text"],textarea{
 border: none;
 border-bottom: 1px solid var(--light);
 color: var(--dark);
 background:transparent;
 outline: none;
 font-size: 1.5rem;
 margin: 1%;
}
::placeholder{
	color: darkgrey;
}

#buton-submit{
	margin: 2%;
	border :none;
	outline: none;
	position: relative;
	left: 30%;
	background: var(--dark);
	color: var(--light);
	font-size: 2rem;
	border-radius: 20px;
}

#buton-submit:hover{
	cursor: pointer;
    background-color: var(--primary);
    color: var(--dark);
}
    </style>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#buton-submit").click(function() {
			    var nume = $("input[name='nume']").val();
			    var email = $("input[name='email']").val();
			    var subiect = $("input[name='subiect']").val();
				var mesaj = $("textarea[name='mesaj']").val();
			    var flag = true;
			    if(nume == "") {
			        $("input[name='nume']").css('border-color','red');
			        flag = false;
			    }
			    if(email == "") {
			        $("input[name='email']").css('border-color','red');
			        flag = false;
			    }
			    if(subiect == "") {
			       $("input[name='subiect']").css('border-color','red');
			        flag = false;
			    }
				if(mesaj == "") {
					$("textarea[name='mesaj']").css('border-color','red');
 			        flag = false;
				}
			    if(flag)
			    {
			        $.ajax({
			            type: 'post',
			            url: "mesaj-contact-form.php",
			            dataType: 'json',
			            data: 'nume=' + nume + '&email=' + email + '&subiect=' + subiect + '&mesaj=' + mesaj,
			            beforeSend: function() {
			                $('#buton-submit').attr('disabled', true);
			                $('#buton-submit').after('<span class="wait">Va rugam sa asteptati pana procesam mesajul.</span>');
			            },
			            complete: function() {
			                $('#buton-submit').attr('disabled', false);
			                $('.wait').remove();
			            },
			            success: function(data)
			            {
			                if(data.type == 'error')
			                {
			                    output = '<div class="error">' + data.text + '</div>';
			                } else {
			                    output = '<div class="success">' + data.text + '</div>';
			                    $("input[type='text']").val('');
			                    $("textarea[name='mesaj']").val('');
			                }
			                $("#result-contact-form").hide().html(output).slideDown();
			           }
			        });
			    } else {
					$("#result-contact-form").hide().html("Completati toate campurile.").slideDown();
				}
			});

			$(".form-contact div input, .form-contact div textarea").keyup(function() {
			    $(".form-contact div input, .form-contact div textarea").css('border-color','');
				$("#result-contact-form").slideUp();
			});
		})
	</script>
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
			 <div class="sidebar-contact">
			   <ul style="list-style-type: none;">
				   <a style="text-decoration: none;"href="info/cum-cumpar.php"><li class="li-sidebar-contact">Cum cumpar</li></a>
				   <a style="text-decoration: none;"href="info/confidentialitate.php"><li class="li-sidebar-contact">Confidentialitate</li></a>
				   <a style="text-decoration: none;"href="info/transport-retur.php"><li class="li-sidebar-contact">Transport si retur</li></a>
				   <a style="text-decoration: none;"href="info/termeni-conditii.php"><li class="li-sidebar-contact">Termeni si conditii</li></a>
				   <a style="text-decoration: none;"href="info/intrebari-frecvente.php"><li class="li-sidebar-contact">Intrebari frecvente</li></a>
				   <a style="text-decoration: none;"href="contact.php"><li class="li-sidebar-contact">Contact</li></a>
			   </ul>
		   </div>
         <div class="wrapper-contact">
             <h2>Contacteaza-ne</h2>
          <div class="contact-container">
             <div class="contact-telefon" id="box1">
                 <h3>Telefon</h3>

<p>Order online or call our customer services team on +44 (0) 345 241 2866 between 8:30am and 5:00pm (GMT), Monday to Friday.</p>

<p>The customer services team are also happy to help with basic advice about which products to use, ordering and delivery enquiries.</p>
             </div>
             <div class="contact-email" id="box1">

<h3>Email</h3>

<p>For all MaxiNutrition Shop enquiries please e-mail the customer services team:  customerservice@kruger-uk.com</p>
             </div>
             <div class="contact-posta" id="box1">
                 <h3>Posta</h3>
                 <ul>
<li>MaxiNutrition</li>
<li>Boughey Distribution Limited</li>
<li>Nantwich Rd</li>
<li>Wardle</li>
<li>Nantwich</li>
<li>CW5 6RS</li>
</ul>
             </div>
             <div class="contact-utile" id="box1">

<h3>Contacte utile</h3>
    <ul>
    <li>For all Trade enquiries please e-mail the Trade Admin team:  customerservice@kruger-uk.com</li>
    <li>For all Marketing enquiries, please e-mail: customerservice@kruger-uk.com</li>
    <li>For Sponsorship enquiries, please contact: mark@true-legacy.com</li>
    <li>For PR enquiries please contact: customerservice@kruger-uk.com</li>
</ul>
             </div>
             <div class="contact-form">
				 <section>
					 <h2>Lasa-ne un mesaj!</h2>
	                 <form class="form-contact" action="" method="post">
	                     <div><input type="text" name="nume" maxlength="70" placeholder="Nume"></div>
	                     <div><input type="text" name="email" maxlength="70" placeholder="Email"></div>
	                     <div><input type="text" name="subiect" maxlength="70" placeholder="Subiect"></div>
	                     <div><textarea name="mesaj" rows="8" cols="60" placeholder="Mesaj" style="resize: none;"></textarea></div>
						 <div><button id="buton-submit" type="button" name="submit">Submit</button></div>
	                 </form>
					 <div id="result-contact-form"></div>
			 	</section>
             </div>
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
