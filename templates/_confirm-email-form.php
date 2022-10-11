<?php

// if member redirected from membership page to this page
if (isset($_SESSION['nation_code']) &&
    !$member->isMemberVerified($_SESSION['nation_code'])) {

    $current_member = $member->getMemberByNationCode($_SESSION['nation_code']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if member clicked on vkey verification button
        if (isset($_POST['submitVkey'])) {
            if ($_POST['verification_key'] == $current_member["vkey"]) {
                // if member signing up is finished successfully
                $member->verifyMemberByNationCode($_SESSION['nation_code']);

                // adding a new log
                $log->insertNewLog(
                    $operation = 'update',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Member verified column changed to 1 (true)"
                );

                $_SESSION['signup_completed'] = true;
                // unset nation_code and set user_id to access member by id instead of nation code
                unset($_SESSION['nation_code']);
                $_SESSION['user_id'] = $current_member['id'];

                // redirect to index.php
                ob_clean();
                header('Location: index.php');

            } // if entered vkey was not correct:
            else {
                echo '<div class="alert alert-danger text-center mb-1" role="alert">کد وارد شده نادرست است</div>';
            }
        }

        // if member clicked on resend button
        if (isset($_POST['resend'])) {

            // create new vkey
            $new_vkey = randomEmailCode();

            // update member vkey by new vkey
            $member->updateVkeyByNationCode($_SESSION['nation_code'], $new_vkey);

            // adding a new log
            $log->insertNewLog(
                $operation = 'update',
                $date = miladiToJalali(date("Y/m/d")),
                $member_username = $current_member['username'],
                $description = "New vkey set for current member"
            );

            // calling get_member after updating vkey
            $current_member = $member->getMemberByNationCode($_SESSION['nation_code']);

            // mail the new vkey to member
            sendEmail($current_member["username"], $current_member["email"], $new_vkey);

            // refresh page
            header("Refresh:0");
        }
    }
    ?>


    <section>
        <div class="container my-3 w-75">
            <h1 class="text-center my-5">صفحه ی احراز هویت (تایید ایمیل)</h1>
            <form action="" method="POST">
                <div class="form-group my-4">
                    <label for="code">کد ارسال شده به ایمیل خود را وارد کنید:</label>
                    <input type="text" name="verification_key" id="code" class="form-control mt-3" required/>
                </div>

                <!-- submit vkey button -->
                <button type="submit" name="submitVkey" class="btn btn-success px-4 mt-1">تایید</button>
                <!-- resend vkey button -->
                <input type="submit" name="resend" class="btn btn-warning px-4 mt-1" value="ارسال دوباره کد">
            </form>
            <!-- Close form -->
        </div>
        <!-- Close container div -->

    </section>

    <?php
} else {
    // show the message that you don't have access to this page
    showInaccessibleWarning();
}
?>