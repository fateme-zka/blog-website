<!-- only managers and authors have access to this page -->
<section class="col-10 mx-auto">
    <div class="container p-3">


        <?php
        // if post_id is set in url => ?post_id=[id]
        if (isset($_GET['post_id'])) {
            $id = $_GET['post_id'];
            $current_post = $post->getPostById($id);

            // if request method was post
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // if user clicked on post_submit button
                if (isset($_POST['post_submit'])) {
                    // initial values
                    $post_title = $_POST['post_title'];
                    $post_category = $_POST['post_category'];
                    $post_description = $_POST['post_description'];
                    // it will calculate by description:
                    $post_reading_time = calculateReadingTime($post_description);


                    // Image input
                    // if image file is not set
                    if (!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
                        $post_image_address = $current_post['image'];
                    } // if image is set
                    else {
                        $post_image_file = $_FILES['image'];
                        $post_image_address = "assets/post_images/" . $_FILES['image']['name'];
                    }

                    // if update post is successful
                    if ($post->updatePostById($id,
                        $post_title,
                        $post_category,
                        $post_image_address,
                        $post_description,
                        $post_reading_time)) {

                        // save image if it was uploaded
                        if (isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
                            saveImageToDirectory($post_image_file, "./assets/post_images/");
                        }

                        // redirect to admin-posts
                        header("Location: admin-posts.php");
                    }


                }
            }

            ?>


            <h2 class="mb-3">ویرایش پست</h2>
            <form action="" method="post" enctype="multipart/form-data">

                <!-- post title ---------------------------------------------->
                <div class="form-group my-4">
                    <label for="post-title">موضوع پست:</label>
                    <input type="text" name="post_title"
                           value="<?php echo $current_post['title']; ?>"
                           id="post-title" class="form-control mt-3"/>
                </div>

                <!-- post category ---------------------------------------------->
                <div class="form-group my-4">
                    <label for="post-category">دسته بندی:</label>
                    <input type="text" name="post_category"
                           value="<?php echo $current_post['category']; ?>"
                           id="post-category" class="form-control mt-3"/>
                </div>

                <!-- post image ---------------------------------------------->
                <div class="form-group my-3">
                    <label for="image">عکس:</label><br>
                    <img src="<?php echo $current_post['image']; ?>" class="image-fluid card-img-top my-2 rounded"
                         alt="post image" style="width: 200px">
                    <input type="file" name="image" id="image" class="form-control"/>
                </div>

                <!-- post description ---------------------------------------------->
                <div class="form-group my-4">
                    <label for="post-description">متن پست:</label>
                    <textarea name="post_description"
                              id="post-description" class="form-control mt-3"
                              rows="7"><?php echo $current_post['description']; ?></textarea>
                </div>

                <!-- submit edit post button ---------------------------------------------->
                <div class="form-group">
                    <button type="submit" name="post_submit" class="btn btn-success text-light">ویرایش پست</button>
                </div>
            </form>

        <?php } else { // if post_id is not set in get method
            echo '<h3>post id is not set!</h3>';
        }
        ?>
    </div>
</section>

