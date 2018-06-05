<?php

function confirmQuery($query){
    global $connection;
    if(!$query){
        die("QUERRY FAILED" . mysqli_error($connection));
    }
}

function insertCategories(){
    global $connection;
    if(isset($_POST['submit'])){
        $categoryTitle = $_POST['cat_title'];

        if($categoryTitle == "" || empty($categoryTitle)){

        }
        else{
            $query = "INSERT INTO categorii_articole(c_nume) ";
            $query .= "VALUE('{$categoryTitle}')";

            $createCategoryQuery = mysqli_query($connection, $query);
            if(!$createCategoryQuery){
                die("QUERY FAILED!" . mysqli_error($connection));
            }
        }
    }
}

function findAllCategories(){
    global $connection;
    $query = "SELECT * FROM categorii_articole";
    $selectCategories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($selectCategories)){
        $catId = $row['c_id'];
        $catTittle = $row['c_nume'];

        echo "<tr>";
        echo "<td>{$catId}</td>";
        echo "<td>{$catTittle}</td>";
        echo "<td><a onclick=\"javascript: return confirm('Are you sure you want to delete the category named {$catTittle}?');\" href='categories.php?delete={$catId}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$catId}'>Edit</a></td>";
        echo "<tr>";
    }
}

function deleteCategory(){
    global $connection;
    if(isset($_GET['delete'])){
        $deleteCatId = $_GET['delete'];
        echo $deleteCatId;
        $query = "DELETE FROM categorii_articole WHERE c_id = {$deleteCatId} ";
        $deleteQuery = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}

function deletePost(){
    global $connection;
    if(isset($_GET['delete'])){
    $thePostID = $_GET['delete'];
    $query = "DELETE from articole where a_id = {$thePostID}";
    mysqli_query($connection, $query);
    header("Location: posts.php");
    }
}

function checkImage($postImageTemp, $postImage){
    $allowedExtensions = ["gif", "jpeg", "jpg", "png"];
    $temp = explode(".", $_FILES["image"]['name']);
    $extension = end($temp);
    if ((($_FILES['image']['type'] == "image/gif") || 
         ($_FILES['image']['type'] == "image/jpeg") ||
         ($_FILES['image']['type'] == "image/jpg") || 
         ($_FILES['image']['type'] == "image/pjpeg") ||
         ($_FILES['image']['type'] == "image/x-png") ||
         ($_FILES['image']['type'] == "image/png")) &&
         ($_FILES['image']['size'] < 5000000) &&
         in_array($extension, $allowedExtensions)){
        if($_FILES['image']['error'] > 0){
            echo "Return code: " . $_FILES["image"]["error"] . "<br>";
        }
        else{
            return 1;
        }
    }
    else{
        echo "The file you have choosen is not supported or the image field is empty!";
    }
}
   
?>