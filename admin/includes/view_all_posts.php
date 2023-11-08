<?php
    if(isset($_POST['checkBoxArray'])){
    
        foreach($_POST['checkBoxArray'] as $postValueId){
            
            $bulk_options = $_POST['bulk_options'];
            
            switch($bulk_options){
                case 'published':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id= {$postValueId} ";
                    $update_publish_status = mysqli_query($connection, $query);
                    confirmQuery($update_publish_status);
                    break;
                
                case 'draft':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id= {$postValueId} ";
                    $update_draft_status = mysqli_query($connection, $query);
                    confirmQuery($update_draft_status);
                    break;
                
                case 'delete':
                    $query = "DELETE FROM posts WHERE post_id= {$postValueId} ";
                    $delete_post_query = mysqli_query($connection, $query);
                    confirmQuery($delete_post_query);
                    break;
                
                case 'clone':
                    $query = "SELECT * FROM posts WHERE post_id = {$postValueId}";
                    $select_post_query = mysqli_query($connection, $query);

                    while($row = mysqli_fetch_array($select_post_query)){
                        $post_title = $row['post_title'];
                        $post_category_id = $row['post_category_id'];
                        $post_author = $row['post_author'];
                        $post_status = $row['post_status'];
                        $post_image = $row['post_image'];
                        $post_tags = $row['post_tags'];
                        $post_content = $row['post_content'];

                    }

                    $query = "INSERT INTO posts(post_category_id, post_title, post_author, 
                        post_date, post_image, post_content, post_tags,  post_status) ";

                        $query .= "VALUES ('$post_category_id', '$post_title', '$post_author', now(),
                        '$post_image', '$post_content', '$post_tags', '$post_status')";

                        $copy_query = mysqli_query($connection, $query);

                        if(!$copy_query){
                            die("QUERY FAILED!".mysqli_error($connection));
                        }

                    break;
                
                default:
            }
        }
    }
?>


<form action="" method="post">

    <!-- Bulk Options for edit -->
    <table class="table table-bordered table-hover">
        <div id="bulkOptionsContainer" class="col-xs-4">
            <select name='bulk_options' class="form-control" >
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" value="Apply" class="btn btn-success">
            <a href="posts.php?source=add_post" class="btn btn-primary">Add New</a>
        </div>

        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>Id</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Views</th>
                <th>Date</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $query = "SELECT * FROM posts ORDER BY post_id DESC";
        $select_post = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_post)) {
            $post_id = $row['post_id'];
            $post_author = $row['post_author'];
            $post_title = $row['post_title'];
            $post_category_id = $row['post_category_id'];
            $post_status = $row['post_status'];
            $post_image = $row['post_image'];
            $post_tags = $row['post_tags'];
            $post_comment_count = $row['post_comment_count'];
            $post_date = $row['post_date'];
            $post_views_count = $row['post_views_count'];
            
            echo "<tr>";
            ?>
            <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]'
            value='<?php echo $post_id; ?>'></td>

            <?php
            echo "<td>{$post_id}</td>";
            echo "<td>{$post_author}</td>";
            echo "<td><a href='../post.php?p_id={$post_id}'>{$post_title}</a></td>";
            
            $query = "SELECT * FROM categories WHERE cat_id = $post_category_id";
            $select_categories_id = mysqli_query($connection, $query);
            while ($row = mysqli_fetch_assoc($select_categories_id)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<td>{$cat_title}</td>";
            }
            
            
            echo "<td>{$post_status}</td>";
            echo "<td><img width=100 src='../images/{$post_image}'></td>";
            echo "<td>{$post_tags}</td>";

            $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
            $send_comment_query = mysqli_query($connection,$query);

            $row = mysqli_fetch_assoc($send_comment_query);
            if($row != null){
                $comment_id = $row['comment_id'];
            
            }
            $count_comments = mysqli_num_rows($send_comment_query);

            echo "<td><a href='post_comments.php?id=$post_id'>{$count_comments}</a></td>";


            echo "<td>{$post_date}</td>";
            echo "<td><a href= 'posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
            echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
            echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this post?');\" href='posts.php?delete={$post_id}'>Delete</a></td>";
            echo "</tr>";
        }
        
        ?>
        

</tbody>
</table>
</form>

<?php
if(isset($_GET['delete'])){
    $post_id_del = $_GET['delete'];
    $query = "DELETE FROM posts WHERE post_id = {$post_id_del} ";
    $delete_query = mysqli_query($connection, $query);
    confirmQuery($delete_query);
    header("Location: posts.php");
}

if(isset($_GET['reset'])){
    $post_id_del = $_GET['reset'];
    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = ". mysqli_real_escape_string($connection,$_GET['reset']) ." ";
    $reset_query = mysqli_query($connection, $query);
    confirmQuery($reset_query);
    header("Location: posts.php");
}

?>