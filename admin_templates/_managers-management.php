<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on add_author_submit button
        if (isset($_POST['add_manager_submit'])) {
            if (isset($_POST['authors'])) {
                $id = $_POST['authors'];
                if ($member->convertMemberToManager($id)) {

                    // adding a new log
                    $log->insertNewLog(
                        $operation = 'update',
                        $date = miladiToJalali(date("Y/m/d")),
                        $member_username = $current_member['username'],
                        $description = "Manager converted an author member to manager");

                    // refresh page
                    ob_clean();
                    header('Refresh:0');
                }
            } else { // if authors select was not set in case user clicked add to managers button
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">باید نویسنده را انتخاب کنید</div>';
            }
        }

        // if user clicked on manager_to_author_submit button
        if (isset($_POST['manager_to_author_submit'])) {
            $id = $_POST['member_id_input'];
            if ($member->convertMemberToAuthor($id)) {

                // adding a new log
                $log->insertNewLog(
                    $operation = 'update',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Manager converted a manager member to author");

                // refresh page
                ob_clean();
                header('Refresh:0');
            } else { // if an error occurs in deleting author
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">تبدیل به نویسنده موفقیت آمیز نبود</div>';
            }
        }

        // if user clicked on manager_delete_submit button
        if (isset($_POST['manager_delete_submit'])) {
            $id = $_POST['member_id_input'];
            if ($member->deleteMemberById($id)) {

                // adding a new log
                $log->insertNewLog(
                    $operation = 'delete',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "'Manager deleted a manager member");

                // refresh page
                ob_clean();
                header('Refresh:0');
            } else { // if an error occurs in deleting author
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">حذف موفقیت آمیز نبود</div>';
            }
        }

    }

    $all_authors = $member->getAllMembers('2'); // get all authors
    $all_managers = $member->getAllMembers('1'); // get all managers

    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-managers").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">

            <!-- add new manager : choosing from members table in db -->
            <h2>تعریف مدیر جدید</h2>
            <form action="" method="post">
                <!-- choose member ------------------------------------------ -->
                <div class="form-group mb-3">
                    <label for="authors" class="my-2">تمام کاربران نویسنده:</label>
                    <select name="authors" id="authors" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        <option value="0" disabled selected>لطفا مدیر جدید را انتخاب نمایید</option>
                        <?php // get all authors to show in select if manager wants to add them as manager
                        foreach ($all_authors as $author) {
                            $author_id = $author['id'];
                            $author_username = $author['username'];
                            echo "<option value='$author_id'>$author_username</option>";
                        } ?>
                    </select>
                </div>
                <!-- submit button ------------------------------------------ -->
                <div class="form-group">
                    <button type="submit" name="add_manager_submit" class="btn btn-info text-light">افزودن به مدیر ها
                    </button>
                </div>
            </form>
            <br>
            <hr> <!--some space-->
            <br>

            <!-- show all managers -->
            <h2>تمامی مدیر ها</h2>
            <table class="table table-bordered">
                <!-- header of table -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">نام کاربری</th>
                    <th scope="col">کد ملی</th>
                    <th scope="col">ایمیل</th>
                    <th scope="col" colspan="2">مدیریت مدیر</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all members to show the row of their informations -->
                <tbody>
                <?php
                $number = 1;
                foreach ($all_managers as $manager) {
                    $manager_id = $manager['id'];
                    $manager_username = $manager['username'];
                    $manager_nation_code = $manager['nation_code'];
                    $manager_email = $manager['email'];
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $manager_username; ?></td>
                        <td><?php echo $manager_nation_code; ?></td>
                        <td><?php echo $manager_email; ?></td>
                        <!-- delete and convert manager form -->
                        <form action="" method="post">
                            <td>
                                <!-- convert author to reader -->
                                <input type="hidden" name="member_id_input" value="<?php echo $manager_id; ?>">
                                <button type="submit" name="manager_to_author_submit"
                                        class="btn text-info p-0 m-0 font-size-14">تبدیل به نویسنده
                                </button>
                            </td>
                            <td>
                                <!-- delete author -->
                                <input type="hidden" name="member_id_input" value="<?php echo $manager_id; ?>">
                                <button type="submit" name="manager_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </td>
                        </form>
                    </tr>
                    <?php $number++;
                } ?>
                </tbody>
            </table>


        </div>
    </section>

    <?php
}
?>