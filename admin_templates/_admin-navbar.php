<?php

// if member is not logged in show inaccessible message
if (!isset($_SESSION['user_id'])) {
    showInaccessibleWarning();
    die();
}

// if member is logged in
if (isset($_SESSION['user_id'])) {
    $current_member = $member->getMemberById($_SESSION['user_id']);

    // if logged in member is reader
    if ($permission->getPermissionTypeById($current_member['member_type_id']) == 'reader') {
        echo '<div class="w-75 mx-auto my-5">
            <h2 class="mb-3">این صفحه برای مدیران و نویسندگان سایت است</h2>
            <div>(شما به این صفحه دسترسی ندارید)</div>
        <a href="index.php" class="btn btn-info my-4 text-light">صفحه ی اصلی</a>
        </div>';
        die();
    }
}
?>

<section class="w-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light" style="justify-content: space-between;">
        <div>
            <!-- show tiny profile image -->
            <img src="<?php echo $current_member['image']; ?>"
                 alt="" width="32" height="32" class="rounded-circle me-2">
            <a class="navbar-brand text-light font-lalezar font-size-22"
               href="admin-welcome.php">داشبورد <?php
                echo $current_member['first_name'] . " ".$current_member['last_name'] . " ";
                if ($permission->getPermissionTypeById($current_member['member_type_id']) == 'manager') {
                    echo "(مدیر)";
                } else {
                    echo "(نویسنده)";
                } ?> </a>
        </div>

        <!-- link to index.php -->
        <div class="my-2 mx-5 my-lg-0">
            <a href="index.php" class="navbar-brand my-2 my-sm-0 font-poppins">
                Dornica
            </a>
        </div>

    </nav>
</section>

