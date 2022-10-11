<!-- only managers and authors have access to this page -->

<!-- to show the current tab in sidebar by activating the link -->
<script type="text/javascript">
    document.querySelector("#admin-new-post").classList.add('active');
</script>


<section class="col-10">
    <?php

    $messages = [];
    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // if user clicked on add_post_submit button
        if (isset($_POST['add_post_submit'])) {
            $post_title = $_POST['post_title'];
            $post_category = $_POST['post_category'];
            $post_author_id = $current_member['id'];
            $post_description = $_POST['post_description'];
            $post_reading_time = calculateReadingTime($post_description);
            $post_image_file = $_FILES['image'];
            $post_image_address = "assets/post_images/" . $_FILES['image']['name'];

            // if inserting member was successful
            if ($post->insertNewPost($post_title, $post_description, $post_author_id, $post_category, $post_image_address, $post_reading_time)) {

                // if image was uploaded save the image
                $img_path = saveImageToDirectory($post_image_file, "./assets/post_images/");

                // redirect to admin-new-post
                header('Location: admin-new-post.php');
            }

        }
    }


    ?>

    <div class="container p-3">

        <!-- create new post form -->
        <h2 class="mb-3">افزودن پست جدید</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <!-- title ------------------------------------------------------>
            <div class="form-group my-4">
                <label for="post-title">موضوع:</label>
                <input type="text" name="post_title" id="post-title" class="form-control mt-3" required/>
            </div>

            <!-- category  -------------------------------------------------->
            <div class="form-group my-4">
                <label for="post-category">دسته بندی:</label>
                <input type="text" name="post_category" required
                       id="post-category" class="form-control mt-3"/>
            </div>

            <!-- image ------------------------------------------------------>
            <div class="form-group my-3">
                <label for="post-image">عکس:</label><br>
                <input type="file" name="post_image" id="post-image" class="form-control" required/>
            </div>

            <!-- description ------------------------------------------------>
            <div class="form-group my-4">
                <label for="post-description">متن پست:</label>
                <textarea rows="6" name="post_description" id="post-description" class="form-control mt-3"
                          required></textarea>
            </div>

            <!-- submit button ---------------------------------------------->
            <div class="form-group">
                <button type="submit" name="add_post_submit" class="btn btn-success text-light">افزودن پست</button>
            </div>
        </form>

    </div>
</section>