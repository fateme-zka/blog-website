<main id="main-site">
    <!-- Blogs -->
    <section id="blogs">
        <div class="container py-4">
            <h1 class="font-rubik font-size-28">پست ها:</h1>
            <hr/>


            <div class="container">
                <div class="col-md-12 col-lg-12">

                    <!-- foreach through all posts in database to show them -->
                    <?php $all_posts = $post->getAllPosts();
                    if ($all_posts) {
                        foreach ($all_posts as $post) { ?>
                            <article class="post vt-post">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                                        <div class="post-type post-img">
                                            <a href="show-post.php?post_id=<?php echo $post['id']; ?>"
                                            ><img src="<?php echo $post['image']; ?>"
                                                  class="img-responsive image-fluid card-img-top my-2 rounded"
                                                  alt="image post"></a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8">
                                        <div class="caption">
                                            <h3 class="md-heading">
                                                <a href="show-post.php?post_id=<?php echo $post['id']; ?>"
                                                   class="text-decoration-none text-primary">
                                                    <?php echo $post['title']; ?>
                                                </a></h3>
                                            <span class="small text-muted">نویسنده:</span>
                                            <span class="small text-muted">
                                                <?php $post_author = $member->getMemberById($post['author_id']);
                                                echo $post_author['first_name'] . " " . $post_author['last_name'];
                                                ?></span>
                                            <p class="text-justify">
                                                <?php echo getNumberOfFirstWords($post['description'], 100); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <hr/>
                        <?php }
                    } else { // in case not post exists in database ?>
                        <h3 class="text-center">پستی برای نمایش وجود ندارد</h3>
                    <?php } ?>

                </div>
    </section>
    <!-- !Blogs -->
</main>
<!-- End MAIN -->