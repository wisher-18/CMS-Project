<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to</th>
            <th>Date</th>
            <th>Approve</th>
            <th>Unapprove</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM comments";
        $select_comments = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_comments)) {
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['comment_post_id'];
            $comment_author = $row['comment_author'];
            $comment_email = $row['comment_email'];
            $comment_content = $row['comment_content'];
            $comment_status = $row['comment_status'];
            $comment_date = $row['comment_date'];
            
            echo "<tr>";
            echo "<td>{$comment_id}</td>";
            echo "<td>{$comment_author}</td>";
            echo "<td>{$comment_content}</td>";
            echo "<td>{$comment_email}</td>";
            echo "<td>{$comment_status}</td>";
            $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
            $select_post_id_query = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($select_post_id_query)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];

                echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";

            }
            echo "<td>{$comment_date}</td>";
            echo "<td><a href='comment.php?approve={$comment_id}'>Approve</a></td>";
            echo "<td><a href='comment.php?disapprove={$comment_id}'>Disapprove</a></td>";
            echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this comment?');\" href='comment.php?delete={$comment_id}'>Delete</a></td>";
            echo "</tr>";
        }


        ?>

    </tbody>
</table>

<?php
//APPROVING COMMENTS QUERY
if(isset($_GET['approve'])){
    $comment_id_dis = escape($_GET['approve']);
    $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = {$comment_id_dis} ";
    $approve_query = mysqli_query($connection, $query);
    confirmQuery($approve_query);
    header("Location: comment.php");
}


//DISAPPROVING COMMENTS QUERY
if(isset($_GET['disapprove'])){
    $comment_id_dis = escape($_GET['disapprove']);
    $query = "UPDATE comments SET comment_status = 'disapproved' WHERE comment_id = {$comment_id_dis} ";
    $disapprove_query = mysqli_query($connection, $query);
    confirmQuery($disapprove_query);
    header("Location: comment.php");
}



//DELETING COMMENTS QUERY
if(isset($_GET['delete'])){
    $comment_id_del = escape($_GET['delete']);
    $query = "DELETE FROM comments WHERE comment_id = {$comment_id_del} ";
    $delete_query = mysqli_query($connection, $query);
    confirmQuery($delete_query);
    header("Location: comment.php");
}

?>