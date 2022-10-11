<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on add_author_submit button
        if (isset($_POST['add_author_submit'])) {
            if (isset($_POST['members'])) {
                $member_id = $_POST['members'];
                // convert that reader member to author
                $member->convertMemberToAuthor($member_id);

                // adding a new log
                $log->insertNewLog(
                    $operation = 'update',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Manager converted a reader member to author");

                // refresh page
                header('Refresh:0');
            } else { // if member select was not set in case user clicked add to authors button
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">باید خواننده را انتخاب کنید</div>';
            }
        }

        // if user clicked on author_to_reader_submit button
        if (isset($_POST['author_to_reader_submit'])) {
            $id = $_POST['member_id_input'];
            // convert author member to reader
            if ($member->convertMemberToReader($id)) {

                // adding a new log
                $log->insertNewLog(
                    $operation = 'update',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Manager converted an author member to reader");

                // refresh page
                header('Refresh:0');
            } else { // if an error occurs in deleting author
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">تبدیل به خواننده موفقیت آمیز نبود</div>';
            }
        }

        // if user clicked on author_delete_submit button
        if (isset($_POST['author_delete_submit'])) {
            $id = $_POST['member_id_input'];
            // delete that author member
            if ($member->deleteMemberById($id)) {

                // adding a new log
                $log->insertNewLog(
                    $operation = 'delete',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Manager deleted an author member");

                // refresh page
                ob_clean();
                header('Refresh:0');
            } else { // if an error occurs in deleting author
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">حذف موفقیت آمیز نبود</div>';
            }
        }

    }

    $all_readers = $member->getAllMembers('3'); // get all readers
    $all_authors = $member->getAllMembers('2'); // get all authors

    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-authors").classList.add('active');
    </script>


    <section class="col-10">
        <div class="container p-3">

            <!-- add new author form : choosing from members table in db -->
            <h2>تعریف نویسنده جدید</h2>
            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="members" class="my-2">تمام کاربران خواننده:</label>
                    <select name="members" id="members" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        <option value="0" disabled selected>لطفا نویسنده جدید را انتخاب نمایید</option>
                        <?php // get all reader members to show in select tag if manager wants to add them to authors
                        foreach ($all_readers as $reader) {
                            $reader_id = $reader['id'];
                            $reader_username = $reader['username'];
                            echo "<option value='$reader_id'>$reader_username</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_author_submit" class="btn btn-info text-light">
                        اضافه کردن به نویسنده ها
                    </button>
                </div>
            </form>

            <br>
            <hr> <!--some space-------------------------------------------------------------->
            <br>

            <!-- show all authors table tag -->
            <h2>تمامی نویسنده ها</h2>
            <table class="table table-bordered">
                <!-- header of table -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">نام کاربری</th>
                    <th scope="col">کد ملی</th>
                    <th scope="col">ایمیل</th>
                    <th scope="col" colspan="2">مدیریت نویسنده</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all members to show the row of their information -->
                <tbody>
                <?php
                $number = 1;
                foreach ($all_authors as $author) {
                    $author_id = $author['id'];
                    $author_username = $author['username'];
                    $author_nation_code = $author['nation_code'];
                    $author_email = $author['email'];
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $author_username; ?></td>
                        <td><?php echo $author_nation_code; ?></td>
                        <td><?php echo $author_email; ?></td>
                        <!-- delete and convert author form -->
                        <form action="" method="post">
                            <td>
                                <!-- convert author to reader -->
                                <input type="hidden" name="member_id_input" value="<?php echo $author_id; ?>">
                                <button type="submit" name="author_to_reader_submit"
                                        class="btn text-info p-0 m-0 font-size-14">تبدیل به خواننده
                                </button>
                            </td>
                            <td>
                                <!-- delete author -->
                                <input type="hidden" name="member_id_input" value="<?php echo $author_id; ?>">
                                <button type="submit" name="author_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </td>
                        </form>
                    </tr>
                    <?php $number++; // to show the counter of table tag row
                } ?>
                </tbody>
            </table>


        </div>
    </section>

    <?php
}
?>