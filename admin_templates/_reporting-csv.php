<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on filter_submit button to filter
        if (isset($_POST['filter_submit'])) {
            $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
            $province_value = isset($_POST['state']) ? $_POST['state'] : null;
            $city_value = isset($_POST['city']) ? $_POST['city'] : null;
            $all_members = $member->getAllMembersByFiltering($gender, $province_value, $city_value);
            $all_members_csv = $member->getAllMembersByFilteringCSV($gender, $province_value, $city_value);

            // create or replace csv file in csvfile/reportingResults.csv
            // and put new filtered member in there
            arrayToCsvAndSave($all_members_csv);
        }


    } else { // in case request method was not post (was get)
        $all_members = $member->getAllMembers();
        // if method was not post save all members in csv file
        $all_members_csv = $member->getAllMembersByFilteringCSV();

        // create or replace csv file in csvfile/reportingResults.csv
        // and put all table member in there
        arrayToCsvAndSave($all_members_csv);
    }


    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-reporting").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">

            <!-- searching in members form -->
            <h2 class="mb-3">فیلتر بر اساس:</h2>
            <form action="" method="post">

                <!-- gender --------------------------------------------->
                <div class="form-group my-3">
                    <label>جنسیت:</label>
                    <br/>
                    <!--male-->
                    <div id="male-div">
                        <input
                                type="radio"
                                id="male"
                                name="gender"
                                value="male"
                        />
                        <label for="male">مرد</label>
                    </div>
                    <!--female-->
                    <div id="female-div" style="width: 37px;">
                        <input type="radio" id="female" name="gender" value="female"/>
                        <label for="female">زن</label>
                    </div>
                </div>

                <!-- province ------------------------------------------->
                <div class="form-group mb-3">
                    <label for="province">استان:</label>
                    <select name="state"
                            onChange="loadCitiesByProvinceId(this.value);"
                            class="form-select form-select-sm"
                            aria-label=".form-select-sm example" id="province">

                    </select>
                </div>

                <!-- city ----------------------------------------------->
                <div class="form-group mb-3">
                    <label for="city">شهر:</label>
                    <select name="city" id="city" class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        <option value="0" disabled selected>لطفا استان را انتخاب نمایید</option>
                    </select>
                </div>

                <!-- filter button -------------------------------------->
                <div class="form-group">
                    <button type="submit" name="filter_submit" class="btn btn-info text-light">فیلتر</button>
                </div>
            </form>

            <hr>
            <br>

            <h2 class="mb-3">نتایج گزارش</h2>
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
                    <th scope="col">استان</th>
                    <th scope="col">شهر</th>
                    <th scope="col">شماره تلفن</th>
                    <th scope="col">ایمیل</th>
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
                    $member_province = $member['province'] ? $province->getProvinceNameById($member['province']) : '-';
                    $member_city = $member['city'] ? $city->getCityNameById($member['city']) : '-';
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
                        <td><?php echo $member_province; ?></td>
                        <td><?php echo $member_city; ?></td>
                        <td><?php echo $member_phone_number; ?></td>
                        <td><?php echo $member_email; ?></td>
                    </tr>
                    <?php
                    $number++;
                } // all_members foreach ends ?>
                </tbody>
            </table>
            <div class="w-100 text-center">
                <!-- result csv file download button -->
                <a href="csvfile/reportingResults.csv" download class="btn btn-warning">دانلود اکسل نتایج</a>
            </div>
            <?php
            } else { // if $all_members array was empty
                echo '<div class="text-danger text-center my-2" role="alert"">نتیجه ای یافت نشد</div>';
            } ?>
        </div>
    </section>

    <?php
}
?>