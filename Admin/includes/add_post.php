<?php

if(isset($_POST['create_post'])){
    
    $query = "SELECT * FROM articole";
    $editCategory = mysqli_query($connection, $query);
    
    $postTitle = $_POST['title'];
    $postAuthor = $_POST['author'];
    $postCategory = $_POST['post_category_id'];
    $postStatus = $_POST['post_status'];
    
    $postImage = $_FILES['image']['name'];
    $postImageTemp = $_FILES['image']['tmp_name'];
    $target = '../img-articole/' . "-" . date('Y-m-d-h-i-s') . " - " . $postImage;
    
    $postDescription = mysqli_real_escape_string($connection, $_POST['post_desc']);
    $postContent =  mysqli_real_escape_string($connection, $_POST['post_content']);
    $postDate = date('d-m-y');
    
    if(checkImage($postImageTemp, $postImage) == 1){
            move_uploaded_file($postImageTemp, $target);
            $query = "INSERT INTO articole(a_categorie, a_titlu, a_autor, a_data, ";
            $query .= "a_img_name, a_text, a_descriere, a_status) ";
            $query .= "VALUES ({$postCategory}, '{$postTitle}', '{$postAuthor}', now(), '{$postImage}', ";
            $query .= "'{$postContent}', '{$postDescription}', '{$postStatus}') ";

            $createPost = mysqli_query($connection, $query);
            echo "<h4>Your post has been uploaded succesfuly!</h4>";
            confirmQuery($createPost);
        }
    }

?>
<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title">
    </div>
    <div class="form-group">
        <label for="postCategory">Post Category</label><br>
        <select name="post_category_id" id="">
            <?php
            
            $query = "SELECT * FROM categorii_articole";
            $selectCategories = mysqli_query($connection, $query);
            
            confirmQuery($selectCategories);
            
            while($row = mysqli_fetch_assoc($selectCategories)){
                $cat_id = $row['c_id'];
                $cat_tittle = $row['c_nume'];
                   echo "<option value='{$cat_id}'>{$cat_tittle}</option>"; 
            }
            
            
            
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="postAuthor">Post Author</label>
        <input type="text" class="form-control" name="author">
    </div>
    <div class="form-group">
        <label for="postStatus">Post Status</label>
        <input type="text" class="form-control" name="post_status">
    </div>
    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="postDescription">Post Description</label>
        <textarea type="text" class="form-control" name="post_desc" cols="30" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="postContent">Post Content</label>
        <textarea type="text" class="form-control" name="post_content" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_post" value="Publish Post">
    </div>
    
</form>