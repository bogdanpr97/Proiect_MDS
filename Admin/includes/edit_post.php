<?php

    if(isset($_GET['p_id'])){
        $thePostID = $_GET['p_id'];
    }

    $query = "SELECT * FROM articole WHERE a_id = $thePostID";
    $selectPosts = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($selectPosts)){
            $postAuthor = $row['a_autor'];
            $postTitle = $row['a_titlu'];
            $postCategory = $row['a_categorie'];
            $postStatus = $row['a_status'];
            $postImage = $row['a_img_name'];
            $postDate = $row['a_data'];
            $postContent = $row['a_text'];
    }

    if(isset($_POST['edit_post'])){
        $postAuthor = $_POST['a_autor'];
        $postTitle = mysqli_real_escape_string($connection, $_POST['a_titlu']);
        $postCategory = $_POST['a_categorie'];
        $postStatus = mysqli_real_escape_string($connection, $_POST['a_status']);
        $postImage = $_FILES['image']['name'];
        $postImageTemp = $_FILES['image']['tmp_name'];
        $postContent = mysqli_real_escape_string($connection, $_POST['a_text']);
        $target = '../img-articole/' . $thePostID . "-" . date('Y-m-d-h-i-s') . " - " . $postImage;
        if(empty($postImage)){
            $query = "SELECT * FROM articole WHERE a_id = $thePostID ";
            $selectImage = mysqli_query($connection, $query);
            while($row = mysqli_fetch_array($selectImage)){
                $postImage = $row['a_img_name'];
            }
        }
        move_uploaded_file($postImageTemp, $target);
        $query = "UPDATE articole SET ";
        $query .= "a_titlu = '{$postTitle}', ";
        $query .= "a_categorie = '{$postCategory}', ";
        $query .= "a_data = now(), ";
        $query .= "a_autor = '{$postAuthor}', ";
        $query .= "a_status = '{$postStatus}', ";
        $query .= "a_text = '{$postContent}', ";
        $query .= "a_img_name = '{$postImage}' ";
        $query .= "WHERE a_id = {$thePostID}";
        $editPost = mysqli_query($connection, $query);
        confirmQuery($editPost);
        echo "<h4>Postarea a fost editata cu succes!</h4>";
}

?>

<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" value="<?php echo $postTitle;?>" class="form-control" name="a_titlu">
    </div>
    <div class="form-group">
       <label>Post Category</label><br>
        <select name="a_categorie" id="">
            <?php
            
            $query = "SELECT * FROM categorii_articole";
            $selectCategories = mysqli_query($connection, $query);
            
            confirmQuery($selectCategories);
            
            while($row = mysqli_fetch_assoc($selectCategories)){
                $cat_id = $row['c_id'];
                $cat_tittle = $row['c_nume'];
                if($postCategory == $cat_id){
                    echo "<option selected='selected' value='{$cat_id}'>{$cat_tittle}</option>";
                }
                else{
                   echo "<option value='{$cat_id}'>{$cat_tittle}</option>"; 
                }
            }
            
            
            
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="postAuthor">Post Author</label>
        <input type="text" value="<?php echo $postAuthor;?>" class="form-control" name="a_autor">
    </div>
    <div class="form-group">
        <label for="postStatus">Post Status</label>
        <input type="text" value="<?php echo $postStatus;?>" class="form-control" name="a_status">
    </div>
    <div class="form-group">
        <img src="../img-articole/<?php echo $postImage;?>" alt="" width="200">
        <label for="a_img_name">Post Image</label>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="postContent">Post Content</label>
        <textarea class="form-control" name="a_text" cols="30" rows="10"><?php echo $postContent;?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_post" value="Make changes">
    </div>
    
</form>