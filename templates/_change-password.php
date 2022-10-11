<?php

if (isset($_SESSION['user_id']) || isset($_GET['member_id'])) {
    if (isset($_GET['member_id'])) {
        $current_member_id = $_GET['member_id'];
    } else if (isset($_SESSION['user_id'])) {
        $current_member_id = $_SESSION['user_id'];
    }
    $current_member = $member->getMemberById($current_member_id);

// if method was POST
    $messages = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['update_submit'])) {
            // ----------get data from each input:
            $old_password = md5($_POST['old_pass']);
            $new_password = md5($_POST['new_pass']);
            $cnew_password = md5($_POST['cnew_pass']);

            // validations
            if (!$member->checkPasswordIsCorrect($current_member['nation_code'], $old_password)) $messages[] = "رمز وارد شده اشتباه است";
            if ($old_password == $new_password) $messages[] = "رمز عبور قدیمی و جدید نباید یکسان باشند";
            if ($new_password != $cnew_password) $messages[] = "رمز عبور جدید و تکرار آن یکسان نیستند";

            // In case no error occurs : REDIRECT to index.php
            if (!$messages) {
                // if changing member's password was successful
                if ($member->changePassByNationCode($current_member['nation_code'], $new_password)) {

                    // adding a new log
                    $log->insertNewLog(
                        $operation = 'update',
                        $date = miladiToJalali(date("Y/m/d")),
                        $member_username = $current_member['username'],
                        $description = "Member password was changed"
                    );

                    //redirect to index.php
                    ob_clean();
                    header('Location: index.php');
                }
            }
        }
    }


// show messages:
    if ($messages) {
        foreach ($messages as $msg) {
            echo '<div class="alert alert-danger text-center mb-1" role="alert">' . $msg . '</div>';
        }
    }

    ?>


    <section class="w-50 mx-auto">

        <div class="container my-3 ">
            <h1 class="text-center mb-4">
                «تغییر رمز عبور
                <?php echo $current_member['first_name']; ?>»
            </h1>
            <form action="" method="POST" enctype="multipart/form-data">

                <!-- old password ------------------------------------------->
                <div class="form-group mb-3">
                    <label for="old-password">رمز عبور قبلی:<span class="text-danger">*</span></label>
                    <input
                            required
                            type="text"
                            name="old_pass"
                            id="old-password"
                            class="form-control"
                    />
                </div>

                <!-- new password ------------------------------------------->
                <div class="form-group mb-3">
                    <label for="new-password">رمز عبور جدید:<span class="text-danger">*</span></label>
                    <input
                            required
                            type="text"
                            name="new_pass"
                            id="new-password"
                            class="form-control"
                    />
                </div>

                <!-- confirm new password ----------------------------------->
                <div class="form-group mb-3">
                    <label for="cnew-password">تکرار رمز عبور جدید:<span class="text-danger">*</span></label>
                    <input
                            required
                            type="password"
                            name="cnew_pass"
                            id="cnew-password"
                            class="form-control"
                    />
                </div>

                <!-- submit form -->
                <button type="submit" name="update_submit" class="btn btn-primary px-4 mt-1">تغییر</button>
                <a href="index.php" class="btn btn-secondary px-4 mt-1">لغو</a>
            </form>
            <!-- close form -->

        </div>

    </section>

    <?php
} else {
    // show the message that you don't have access to this page
    showInaccessibleWarning();
}
?>