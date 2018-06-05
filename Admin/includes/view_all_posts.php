<?php// include 'db.php';?>
<?php// include '../functions.php';?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Image</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>

        <?php

        $query = "SELECT * FROM articole";
        $selectPosts = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($selectPosts)){
            $postID = $row['a_id'];
            $postAuthor = $row['a_autor'];
            $postTitle = $row['a_titlu'];
            $postCategory = $row['a_categorie'];
            $postStatus = $row['a_status'];
            $postImage = $row['a_img_name'];
            $postDate = $row['a_data'];
            echo "<tr>";
            echo "<td>{$postID}</td>";
            echo "<td>{$postAuthor}</td>";
            echo "<td>{$postTitle}</td>";
            
            if($postCategory != 0){
            $query = "SELECT * FROM categorii_articole WHERE c_id = {$postCategory} ";
            $editCategory = mysqli_query($connection, $query);

            while($row = mysqli_fetch_assoc($editCategory)){
                $catId = $row['c_id'];
                $catTitle = $row['c_nume'];
            }
            echo "<td>{$catTitle}</td>";
            }
            else{
                echo "<td>Fara categorie</td>";
            }
            echo "<td>{$postStatus}</td>";
            echo "<td><img width='100px' src='../img-articole/$postImage' alt='image'></td>";
            echo "<td>{$postDate}</td>";
            echo "<td><a href='posts.php?source=edit_post&p_id={$postID}'>Edit</a></td>";
            echo "<td><a href='posts.php?delete={$postID}'>Delete</a></td>"; deletePost();
            echo "</tr>";
        }

        ?>
    </tbody>
</table>


