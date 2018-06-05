<?php
    require_once '../../../dbC.php';
?>
    <?php
        if (!isset($_POST['search']) && !isset($_POST['categorie'])) {
            header("Location: index.php");
            exit();
        } else {
                if(!isset($_POST['search']) && isset($_POST['categorie'])) {
                    $sql = "select * from articole where a_titlu like ?
                    or a_descriere like ? or a_autor like ? or
                    a_data like ? order by a_data desc;";
                    if($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ssss", $search, $search, $search, $search);
                        $search = "%".$search."%";
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows == 0) {
                            echo '<p style="margin-top: 10px;">Fara rezultate!</p>';
                        } else {
                            if($result->num_rows > 1) {
                                echo "<p style='margin-top: 10px;'>$result->num_rows rezultate!</p>";
                            }
                            else {
                                echo "<p style='margin-top: 10px;'>$result->num_rows rezultat!</p>";
                            }
                            while($row = $result->fetch_assoc()) {
                                    $sql3 = "select c_nume from categorii_articole c join articole a on(c.c_id = a.a_categorie) where a.a_categorie = " . $row['a_categorie'] . ";";
   								    $res = $conn->query($sql3);
   								    $row2 = $res->fetch_assoc();
                                    echo '<div class="articole-box">
                                            <div style="float: left; margin-right: 1%;">
                                                <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
                                                <p> Data: ' . $row['a_data'] . '</p>
                                                <p> Autor: ' . $row['a_autor'] . '</p>
                                                <p> Categorie: ' . ucfirst($row2['c_nume']) . '</p>
                                                <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
                                                '&data='.$row['a_data'].'">Citeste tot articolul</a>
                                            </div>
                                            <div>
                                               <h3>' . $row['a_titlu'] . '</h3>
                                               <p>' . $row['a_descriere'] . '</p>
                                            </div>
                                        </div>';
                            }
                        }
                        $stmt->close();
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu. Ne cerem scuze!</h2>';
                    }
                } else if(isset($_POST['search']) && isset($_POST['categorie'])) {
                    $search = $conn->real_escape_string($_POST['search']);
                    $categorie = $conn->real_escape_string($_POST['categorie']);
                    $sql1 = "select * from articole a join categorii_articole c on(a.a_categorie = c.c_id) where c_nume = ? and (a_titlu like ?
                    or a_descriere like ? or a_autor like ? or
                    a_data like ?) order by a_data desc;";
                    if($stmt1 = $conn->prepare($sql1)) {
                        $stmt1->bind_param("sssss", $categorie, $search, $search, $search, $search);
                        $search = "%".$search."%";
                        $stmt1->execute();
                        $result1 = $stmt1->get_result();
                        if($result1->num_rows == 0) {
                            echo '<h2>Categorie: ' . ucfirst($categorie) . '</h2>';
                            echo '<hr/>';
                            echo '<p style="margin-top: 10px;">Fara rezultate!</p>';
                        } else {
                            echo '<h2>Categorie: ' . ucfirst($categorie) . '</h2>';
                            echo '<hr/>';
                            if($result1->num_rows > 1) {
                              echo "<p style='margin-top: 10px;'>$result1->num_rows rezultate!</p>";
                            }
                            else {
                                echo "<p style='margin-top: 10px;'>$result1->num_rows rezultat!</p>";
                            }
                            while($row = $result1->fetch_assoc()) {
                                    echo '<div class="articole-box">
                                            <div style="float: left; margin-right: 1%;">
                                                <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
                                                <p> Data: ' . $row['a_data'] . '</p>
                                                <p> Autor: ' . $row['a_autor'] . '</p>
                                                <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
                                                '&data='.$row['a_data'].'">Citeste tot articolul</a>
                                            </div>
                                            <div>
                                               <h3>' . $row['a_titlu'] . '</h3>
                                               <p>' . $row['a_descriere'] . '</p>
                                            </div>
                                        </div>';
                            }
                        }
                        $stmt1->close();
                    } else {
                        echo '<h2>A fost intampinata o problema, reveniti mai tarziu. Ne cerem scuze!</h2>';
                    }
            } else {
                $categorie = $_POST['categorie'];
                $sql1 = "select * from articole a join categorii_articole c on(a.a_categorie = c.c_id) where c_nume = ? order by a_data desc;";
                if($stmt1 = $conn->prepare($sql1)) {
                    $stmt1->bind_param("s", $categorie);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    if($result1->num_rows == 0) {
                        echo '<p style="margin-top: 10px;">Fara rezultate!</p>';
                    } else {
                        echo '<h2>Categorie: ' . ucfirst($categorie) . '</h2>';
                        echo '<hr/>';
                        while($row = $result1->fetch_assoc()) {
                                echo '<div class="articole-box">
                                        <div style="float: left; margin-right: 1%;">
                                            <img src="img-articole/' . $row['a_img_name']. '" style="margin-bottom: 10%;">
                                            <p> Data: ' . $row['a_data'] . '</p>
                                            <p> Autor: ' . $row['a_autor'] . '</p>
                                            <a class="link-articole" href="articol.php?titlu='.$row['a_titlu'].
                                            '&data='.$row['a_data'].'">Citeste tot articolul</a>
                                        </div>
                                        <div>
                                           <h3>' . $row['a_titlu'] . '</h3>
                                           <p>' . $row['a_descriere'] . '</p>
                                        </div>
                                    </div>';
                        }
                    }
                    $stmt1->close();
                } else {
                    echo '<h2>A fost intampinata o problema, reveniti mai tarziu. Ne cerem scuze!</h2>';
                }
        }
     }
    ?>
