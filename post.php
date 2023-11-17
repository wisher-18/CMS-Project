<?php include "./includes/db.php"; ?>
<?php include "./includes/header.php"; ?>

<!-- Navigation -->
<?php include "./includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if(isset($_GET['p_id'])){
                $get_post_id = $_GET['p_id'];

                //Incrementing Views Count when Visited a post
                $views_query = "UPDATE posts SET post_views_count = post_views_count + 1 ";
                $views_query .= "WHERE post_id = $get_post_id";
                $update_view_count = mysqli_query($connection, $views_query);
                
                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'){
                    //Selecting post to show
                    $query = "SELECT * FROM posts WHERE post_id = '$get_post_id'";
                }else{
                    //Selecting post to show
                    $query = "SELECT * FROM posts WHERE post_id = '$get_post_id' AND post_status= 'published' ";

                }

            
            $select_all_posts_query = mysqli_query($connection, $query);
            if(!$select_all_posts_query){
                die("QUERY FAILED".mysqli_error($connection));
            }

            if(mysqli_num_rows($select_all_posts_query) < 1){
                echo "<h1 class='text-center'>NO POSTS AVAILABLE</h1>";

            }else{ 

            while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                $post_id = $_GET['p_id'];
                $post_title = $row['post_title'];
                $post_user = $row['post_user'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_content = $row['post_content'];

            ?>
                <h1 class="page-header">
                    Post
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="post.php?p_id=<?php echo $post_id;?>"><?php echo $post_title ?></a>
                </h2>
                <p class="lead">
                    by <a href="author_posts.php?author=<?php echo $post_user ?>&p_id=<?php echo $post_id ?>"><?php echo $post_user ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date ?></p>
                <hr>
                <img class="img-responsive" src="/cms/images/<?php echo $post_image?>" alt="">
                <hr>
                <p><?php echo $post_content ?></p>
                
                <hr>



            <?php
            }
        
            ?>
        <?php
            //CREATING COMMENT QUERY
            if(isset($_POST['create_comment'])){
                
                $comment_post_id = $_GET['p_id'];
                $comment_author = $_POST['comment_author'];
                $comment_email = $_POST['comment_email'];
                $comment_content = $_POST['comment_content'];
                
                if(!empty($comment_author) && !empty($comment_email)
                && !empty($comment_content)){
                    $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                    $query .= "VALUES($comment_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'disapproved', now()) ";
    
                    $create_comment_query = mysqli_query($connection, $query);
                    if(!$create_comment_query){
                        die('QUERY FAILED'.mysqli_error($connection ));
                    }

                    
                    
                    //UPDATING COMMENT COUNT IN POSTS
                    // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                    // $query .= "WHERE post_id = $comment_post_id";
                    // $update_comment_count = mysqli_query($connection, $query);

                    
                    header("Location: /cms/post.php?p_id={$get_post_id}");
                    exit;

                }else{
                    echo "<script>alert('Fields cannot be empty!')</script>";
                }




                

            }
        ?>


            <!-- Comments Form -->
<div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <input type="text" name="comment_author" placeholder="Your Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="email" name="comment_email" placeholder="Your Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <textarea name="comment_content" class="form-control" placeholder="Enter Comment" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                <?php
                //COMMENT QUERY FOR DISPLAY
                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id ";
                $query .= "AND comment_status = 'approved' ";
                $query .= "ORDER BY comment_id DESC ";
                $select_comment_query = mysqli_query($connection, $query);
                if(!$select_comment_query){
                    die('QUERY FAILED'.mysqli_error($connection ));
                }
                while($row = mysqli_fetch_assoc($select_comment_query)){
                    $comment_date = $row['comment_date'];
                    $comment_author = $row['comment_author'];
                    $comment_content = $row['comment_content'];
                    ?>
                <!-- WHILE LOOP FOR COMMENTS -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>
                        <?php echo $comment_content; ?>
                    </div>
                </div>

                
                <?php
                }
            }
            }else{
                header("Location: index.php");
    
            }
                ?>
                <!-- Comment -->
                


        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include "./includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>
    <?php include "./includes/footer.php"; ?>