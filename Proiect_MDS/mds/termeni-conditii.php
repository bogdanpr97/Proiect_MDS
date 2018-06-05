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
<?php include "includes/head.php";?>

 <style media="screen">
     h2{
        	font-size: 3rem;
        }
        h2,h3{
      
        	text-align:center;
        }
        p{
        	text-align: justify;
        }
     footer p{
        text-align: center;
    }
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
            "menu menu menu"
            "main-nav main-nav main-nav"
            "sidebar-contact wrapper-term-cond . "
            "footer footer footer";
        }
        .wrapper-term-cond {
            grid-area: wrapper-term-cond;
        }
          @media only screen and (max-width: 1000px){
	
	.li-sidebar-contact {
			width: 80%;
	}
	.sidebar-contact {
		border-right: 0px;
	}
	.li-sidebar-contact:hover {
			background-color: var(--dark);
			width: 90%;
            
		}
	
          .wrapper {
            grid-template-columns:  1fr 0.1fr;
            grid-template-areas:
            "menu"
            "main-nav"
            "sidebar-contact"
            "wrapper-term-cond"
            "wrapper-term-cond"
            "footer";
        } 
        }
    </style>
             <div class="sidebar-contact">
               <ul style="list-style-type: none;">
                   <a style="text-decoration: none;"href="cum-cumpar.php"><li class="li-sidebar-contact">Cum cumpar</li></a>
                   <a style="text-decoration: none;"href="confidentialitate.php"><li class="li-sidebar-contact">Confidentialitate</li></a>
                   <a style="text-decoration: none;"href="transport-retur.php"><li class="li-sidebar-contact">Transport si retur</li></a>
                   <a style="text-decoration: none;"href="termeni-conditii.php"><li class="li-sidebar-contact">Termeni si conditii</li></a>
                   <a style="text-decoration: none;"href="intrebari-frecvente.php"><li class="li-sidebar-contact">Intrebari frecvente</li></a>
                   <a style="text-decoration: none;"href="contact.php"><li class="li-sidebar-contact">Contact</li></a>
               </ul>
           </div>
         <div class="wrapper-term-cond">
              <div class="term-cond-container">
                  <section>
                      <h2>Termeni si conditii</h2>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  </section>
             </div>
         </div>
     </div>
<?php include "includes/footer.php";?>
