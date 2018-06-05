<form action="" method="post">
    <div class="form-group">
        <?php
        if(isset($_GET['edit'])){
        $editCatId = $_GET['edit'];  

        $query = "SELECT * FROM categorii_articole WHERE c_id = $editCatId ";
        $editCategory = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($editCategory)){
            $cat_id = $row['c_id'];
            $cat_tittle = $row['c_nume'];
        ?>
        <label for="cat-title">Update category name</label>
        <input value="<?php if(isset($cat_tittle)){echo $cat_tittle;}?>" type="text" class="form-control" name="c_nume">
        <div class="form-group">
        <input type="submit" class="btn btn-primary" name="editCategory" value="Edit Category">
        </div>
        <?php
            }}
        ?>
        <?php 
        if(isset($_POST['editCategory'])){
            $editCatTitle = $_POST['c_nume'];
            $query = "UPDATE categorii_articole SET c_nume = '{$editCatTitle}' ";
            $query .= "WHERE c_id = {$editCatId}";
            $editQuery = mysqli_query($connection, $query);
            header("Location: categories.php");
            if(!$editQuery){
                die("QUERY FAILED" . mysqli_error($connection));
            }
            else{
                echo "Numele categoriei a fost schimbat cu sccues!";
            }
        }
        ?>
            </div>
</form>