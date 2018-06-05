<?php
    require_once '../../../dbC.php';
    session_start();
    if(isset($_POST['submit'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png');
        if(in_array($fileActualExt, $allowed)) {
            if($fileError === 0) {
                if($fileSize < 300000) {
                    $code = getToken(26);
                    $fileNameNew = $code . $_SESSION['uid'] . '.' . $fileActualExt;
                    $fileDestination = 'img-profil-utilizatori/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $currentImg = getCurrentImg($conn);
                    if (file_exists('img-profil-utilizatori/' . $currentImg)) {
                        unlink('img-profil-utilizatori/' . $currentImg);
                      }
                    $sql = "update utilizatori set img_profil = '" . $fileNameNew . "' where u_id = " . $_SESSION['uid'] . ";";
                    $conn->query($sql);
                    header("Location: profil.php?username=" . $_SESSION['uname'] . "&ie=success");
                    exit();
                } else {
                    header("Location: profil.php?username=" . $_SESSION['uname'] . "&ie=error-size");
                    exit();
                }
            } else {
                header("Location: profil.php?username=" . $_SESSION['uname'] . "&ie=error");
                exit();
            }
        } else {
            header("Location: profil.php?username=" . $_SESSION['uname'] . "&ie=error-ext");
            exit();
        }
    }

    function getToken($length){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

    function getCurrentImg($conn) {
        $getImgName = "select img_profil from utilizatori where u_username = ? ;";
        if($stmt = $conn->prepare($getImgName)) {
            $stmt->bind_param("s", $_SESSION['uname']);
            $stmt->execute();
            $res = $stmt->get_result();
            $stmt->close();
            $row = $res->fetch_assoc();
            $imgName = $row["img_profil"];
            $res->close();
            return $imgName;
        } else {
            return "";
        }
    }
