<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

// if member clicked on logout button
    if (isset($_POST['logout_submit'])) {
        session_destroy();
        header("Location: index.php");
    }


// if member clicked on login button
    if (isset($_POST['login_submit'])) {
        if (isset($_POST['username']) && isset($_POST['pass'])) {
            $username = $_POST['username'];
            $pass = md5($_POST['pass']);
            $login_member = $member->checkExistenceOfMemberByUsernamePassword($username, $pass);
            if ($login_member && $member->isMemberVerified($login_member['nation_code'])) {
                $_SESSION['is_login'] = true;
                $_SESSION['user_id'] = $login_member['id'];
                header('Location: index.php');
            } else
                echo '<div class="alert alert-danger text-center mb-0" role="alert" onclick="this.remove();">نام کاربری یا رمز عبور اشتباه است</div>';
        }

    }
}

?>


<section class="w-100" xmlns="http://www.w3.org/1999/html">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
        <a class="navbar-brand font-lalezar font-size-22" href="index.php">درنیکا</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link font-lalezar font-size-18" href="index.php">صفحه اصلی</a>
                </li>
            </ul>
        </div>

        <!-- if member is logged in -->
        <?php if (isset($_SESSION['is_login']) && isset($_SESSION['user_id'])) {
            $current_member = $member->getMemberById($_SESSION['user_id']); ?>

            <!-- Logout Form -->
            <form action="" method="POST">
                <div class="my-2 mx-5 my-lg-0">

                    <?php
                    // get member type of current user
                    $member_type = $permission->getPermissionTypeById($current_member['member_type_id']);

                    // if current user was manager
                    if ($member_type == 'manager') { ?>
                        <a href="admin-welcome.php" class="btn btn-outline-light my-2 my-sm-0">
                            <?php echo 'داشبورد مدیریت'; ?>
                        </a>
                    <?php } // if current user was author
                    else if ($member_type == 'author') { ?>
                        <a href="admin-welcome.php" class="btn btn-outline-light my-2 my-sm-0">
                            <?php echo 'داشبورد نویسنده'; ?>
                        </a>
                    <?php } ?>


                    <a href="edit-profile.php"
                       class="btn btn-outline-info my-2 my-sm-0">ویرایش پروفایل
                    </a>
                    <a href="change-password.php"
                       class="btn btn-outline-warning my-2 my-sm-0">تغییر رمز</a>
                    <!--logout button-->
                    <button type="submit" name="logout_submit"
                            class="btn btn-outline-danger my-2 my-sm-0">
                        خروج
                    </button>
                    <!--show logged in member full name-->
                    <span class="btn-outline-warning my-2 my-sm-0 mx-3">
                        <?php echo $current_member['first_name'] . " " . $current_member['last_name']; ?>
                    </span>
                </div>
            </form>

            <!-- if member is not login -->
        <?php } else { ?>
            <div class="my-2 mx-5 my-lg-0">
                <!-- SignUp button -->
                <a href="membership-form.php" class="btn btn-outline-light my-2 my-sm-0">ثبت نام</a>
                <!-- login Modal button -->
                <?php if (!isset($_SESSION['user_id']) ||
                    (isset($_SESSION['user_id']) && $_SESSION['signup_completed'])) { ?>
                    <button type="button" id="login-modal-button"
                            class="btn btn-outline-info my-2 my-sm-0"
                            data-toggle="modal"
                            data-target="#loginModal"
                            data-whatever="@mdo">
                        وارد شوید
                    </button>
                <?php } ?>

            </div>
        <?php } ?>
    </nav>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="LoginModalLabel">فرم ورود عضو</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="username" class="col-form-label">نام کاربری:</label>
                            <input required type="text" name="username" class="form-control" id="username">
                        </div>
                        <div class="form-group">
                            <label for="pass" class="col-form-label">رمز عبور:</label>
                            <input required type="password" name="pass" class="form-control" id="pass">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" name="cancelBtn" id="cancelBtn" class="btn btn-secondary"
                                data-dismiss="modal">بستن
                        </button>
                        <button type="submit" name="login_submit" id="loginBtn" class="btn btn-primary">ورود
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Close Login Modal -->

</section>