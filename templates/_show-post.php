<?php

if (isset($_GET['post_id'])) {
    // get post id from get request
    $current_post = $post->getPostById($_GET['post_id']);
} // if post id was not set in url (get request)
else {
    echo '<section>
    <div class="container mt-5">
    <h3 class="text-danger">نمایش پست موفقیت آمیز نبود</h3>
    <a class="btn btn-outline-warning my-3" href="index.php">همه پست ها</a>
    </div>
    </section>';
    die();
}

?>

<section>
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-4"><?php echo $current_post['title']; ?></h1>
                        <!-- Post author -->
                        <div class="text-muted fst-italic my-2">نویسنده:
                            <?php $post_author = $member->getMemberById($current_post['author_id']);
                            echo $post_author['first_name']." ".$post_author['last_name'];
                            ?>
                        </div>
                        <!-- Post reading time -->
                        <div class="text-muted fst-italic mb-4">زمان تقریبی مطالعه:
                            <?php echo $current_post['reading_time']; ?>
                            دقیقه
                        </div>
                        <!-- Post category-->
                        <span class="badge  bg-secondary text-decoration-none link-light p-2 font-size-14 font-vazir">
                            <?php echo $current_post['category']; ?>
                        </span>
                    </header>
                    <!-- Post Image -->
                    <figure class="mb-4">
                        <img class="img-fluid rounded"
                             src="<?php echo $current_post['image']; ?>" alt="PostImage"/>
                    </figure>
                    <!-- Post description-->
                    <section class="mb-5">
                        <p class="fs-5 mb-4">
                            <?php echo $current_post['description']; ?>
                        </p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</section>