<!-- only managers and authors have access to this page -->
<?php

// if user is author
if ($permission->getPermissionTypeById($current_member['member_type_id']) == 'author') {
    // show posts those belong to current author
    $all_posts = $post->getAuthorPostsById($current_member['id']);
} // if user is manager
else if ($permission->getPermissionTypeById($current_member['member_type_id']) == 'manager') {
    // show all posts for manager
    $all_posts = $post->getAllPosts();
}

// if request method was post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// if user clicked on post_delete_submit button to delete that post
    if (isset($_POST['post_delete_submit'])) {
        $id = $_POST['post_id_input'];
        if ($post->deletePostById($id)) {

            // refresh page
            header('Refresh:0');
        }
    }
}

?>


<!-- to show the current tab in sidebar by activating the link -->
<script type="text/javascript">
    document.querySelector("#admin-posts").classList.add('active');
</script>

<section class="col-10">
    <div class="container p-3">

        <h2 class="mb-3">لیست پست ها</h2>
        <!-- show all authors -->
        <table class="table table-bordered font-size-14">
            <!-- header of table columns -->
            <thead>
            <tr class="text-center">
                <th scope="col">*</th>
                <th scope="col">موضوع</th>
                <th scope="col">نویسنده</th>
                <th scope="col">دسته بندی</th>
                <th scope="col">زمان خواندن</th>
                <th scope="col">متن پست</th>
                <th scope="col" colspan="3">مدیریت پست</th>
            </tr>
            </thead>

            <!-- body of table: foreach to all posts to show the row of their information -->
            <tbody>
            <?php
            $number = 1;
            if ($all_posts) {
                foreach ($all_posts as $post) {
                    $post_id = $post['id'];
                    $post_title = $post['title'];
                    $post_author_id = $post['author_id'];
                    $post_category = $post['category'];
                    $post_reading_time = $post['reading_time'];
                    $post_description = getNumberOfFirstWords($post['description'], 20);
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $post_title; ?></td>
                        <td><?php echo $post_author_id; ?></td>
                        <td><?php echo $post_category; ?></td>
                        <td><?php echo $post_reading_time; ?></td>
                        <td><?php echo $post_description; ?></td>
                        <!-- show post link -->
                        <td><a href="show-post.php?post_id=<?php echo $post_id; ?>" class="text-warning">نمایش</a>
                            <!-- edit post link -->
                        <td><a href="admin-edit-post.php?post_id=<?php echo $post_id; ?>" class="text-info">ویرایش</a>
                        </td>
                        <td>
                            <!-- delete post button -->
                            <!-- form for delete post button -->
                            <form action="" method="post">
                                <input type="hidden" name="post_id_input" value="<?php echo $post_id; ?>">
                                <button type="submit" name="post_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php $number++;
                }
            } else echo '<h5 class="text-danger text-center">پستی وجود ندارد</h5>';
            ?>
            </tbody>
        </table>

    </div>
</section>