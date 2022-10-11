<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on search_submit button to search
        if (isset($_POST['search_submit'])) {
            if (isset($_POST['field'])) {
                $search_field = $_POST['field'];
                $search_value = $_POST['search_value'];
                $all_members = $member->getAllMembersBySearching($search_field, $search_value);
            } else {
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">فیلد جستجو را انتخاب نمایید</div>';
            }
        }

        //if user click on one of the delete buttons in page
        if (isset($_POST['member_delete_submit'])) {
            $delete_member_id = $_POST['member_id_input'];

            if ($member->deleteMemberById($delete_member_id)) {

                // adding a new log
                $log->insertNewLog(
                    $operation = 'delete',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $current_member['username'],
                    $description = "Manager deleted a member");

                // refresh page
                ob_clean();
                header('Refresh:0');
            } else
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">حذف موفقیت آمیز نبود!</div>';
        }

    } else { // in case request method was not post (was get)
        $all_members = $member->getAllMembers();
    }

    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-list-all-members").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">

            <!-- searching in members form -->
            <h2 class="mb-3">جستجو</h2>
            <form action="" method="post">
                <!-- select field for search ------------------------------------------>
                <div class="form-group mb-3">
                    <label for="field" class="my-2">جستجو بر اساس:</label>
                    <select required="required" name="field" id="field" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        <option value="0" disabled selected>لطفا پارامتر جستجو را انتخاب نمایید</option>
                        <option value="first_name">نام</option>
                        <option value="last_name">نام خانوداگی</option>
                        <option value="username">نام کاربری</option>
                        <option value="email">ایمیل</option>
                        <option value="nation_code">کد ملی</option>
                        <option value="birth_date">تاریخ تولد</option>
                        <option value="phone_number">شماره تلفن</option>
                    </select>
                </div>
                <!-- enter word to search -------------------------------------------->
                <div class="form-group my-4">
                    <label for="search-value">متن جستجو:</label>
                    <input type="text" name="search_value" id="search-value" class="form-control mt-3" required/>
                </div>
                <!-- search button --------------------------------------------------->
                <div class="form-group">
                    <button type="submit" name="search_submit" class="btn btn-info text-light">جستجو</button>
                </div>
            </form>

            <hr>
            <br>

            <h2 class="mb-3">لیست تمامی ثبت نامی ها</h2>
            <!-- show all authors -->
            <table class="table table-bordered font-size-14">
                <!-- header of table columns -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">نام</th>
                    <th scope="col">نام خانوادگی</th>
                    <th scope="col">نام کاربری</th>
                    <th scope="col">جنسیت</th>
                    <th scope="col">نظام وظیفه</th>
                    <th scope="col">تاریخ تولد</th>
                    <th scope="col">کد ملی</th>
                    <th scope="col">شماره تلفن</th>
                    <th scope="col">ایمیل</th>
                    <th scope="col" colspan="3">مدیریت کاربر</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all members to show the row of their informations -->
                <tbody>
                <?php
                $number = 1;
                if ($all_members) {
                foreach ($all_members as $member) {
                    $member_id = $member['id'];
                    $member_first_name = $member['first_name'];
                    $member_last_name = $member['last_name'];
                    $member_username = $member['username'];
                    $member_gender = $member['gender'] == 'male' ? 'مرد' : 'زن';
                    $member_military = $member['military'];
                    if ($member_military) {
                        if ($member_military == 'finished') $member_military = 'خدمت کرده';
                        else if ($member_military == 'permanent-exemption') $member_military = 'معافیت دائم';
                        else if ($member_military == 'temporary-exemption') $member_military = 'معافیت موقت';
                        else if ($member_military == 'medical-exemption') $member_military = 'معافیت پزشکی';
                    } else $member_military = '-';
                    $member_birth_date = $member['birth_date'];
                    $member_nation_code = $member['nation_code'];
                    $member_phone_number = $member['phone_number'];
                    $member_email = $member['email'];
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $member_first_name; ?></td>
                        <td><?php echo $member_last_name; ?></td>
                        <td><?php echo $member_username; ?></td>
                        <td><?php echo $member_gender; ?></td>
                        <td><?php echo $member_military; ?></td>
                        <td><?php echo $member_birth_date; ?></td>
                        <td><?php echo $member_nation_code; ?></td>
                        <td><?php echo $member_phone_number; ?></td>
                        <td><?php echo $member_email; ?></td>
                        <!-- update member's profile link-->
                        <td><a href="edit-profile.php?member_id=<?php echo $member_id; ?>">ویرایش</a></td>
                        <!-- update member's password link-->
                        <td><a href="change-password.php?member_id=<?php echo $member_id; ?>" class="text-warning">تغییر
                                رمز</a></td>
                        <!-- form for delete member button and input-->
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="member_id_input" value="<?php echo $member_id; ?>">
                                <button type="submit" name="member_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                    $number++;
                } // all_members foreach ends ?>
                </tbody>
            </table>
            <?php
            } else { // if $all_members array was empty
                echo '<div class="text-danger text-center my-2" role="alert"">نتیجه ای یافت نشد</div>';
            } ?>

        </div>
    </section>

    <?php
}
?>