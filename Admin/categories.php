<?php 
include "includes/admin_header.php"; 
include "functions.php";
?>

<div id="wrapper">
        <!-- Navigation -->
    <?php include "includes/admin_navigation.php";?>
    
    <div id="page-wrapper">
        <div class="container-fluid">
 <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header">
                        Welcome to admin page, 
                        <big>Admin</big>
                    </h2>
                    
                    <div class="col-xs-6">
                       <?php
                       insertCategories();
                        ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="cat-title">Add Category</label>
                                <input type="text" class="form-control" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" name="submit" value="Add Category">
                            </div>
                        </form>
                        <?php 
                        if(isset($_GET['edit'])){ //update and include query
                            $catId = $_GET['edit'];
                            include "includes/update_categories.php";
                        }
                        
                        ?>
                        
                    </div> <!--Add Category form-->
                    
                    <div class="col-xs-6">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <?php
                                    findAllCategories();
                                    deleteCategory();
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<?php include "includes/admin_footer.php"; ?>