<?php

// if user was logged in or this has been redirected from manager panel
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
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $nation_code = $_POST['nation_code'];
            $phone_number = $_POST['phone_number'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $username = $_POST['username'];

            // check if province and city are set or not
            if (isset($_POST['state'])) {
                $province = $_POST['state'];
            } else {
                $province = null;
            }
            if (isset($_POST['city'])) {
                $city = $_POST['city'];
            } else {
                $city = null;
            }

            // ----------CONDITIONS for each input:

            // nation code validation
            if (!checkNationalCode($nation_code)) $messages[] = 'کد ملی وارد شده نادرست است';

            // existence of another member validation
            if ($member->checkExistenceOfAnotherMember($nation_code, $username)) $messages[] = "کابر دیگری با این کد ملی و نام کاربری وجود دارد";

            // phone number validation
            if (!validatePhoneNumber($phone_number)) $messages[] = "فرمت تلفن همراه نادرست است";

            // birth date validation
            if (!isset($_POST['birth_date']) || $_POST['birth_date'] == "") {
                $birth_date = null;
            } else {
                $birth_date = $_POST['birth_date'];
                if (!validateAge($birth_date)) $messages[] = "سن شما کمتر از 10 سال است!";
            }

            // military validation:
            if ($gender == 'female') $military = null;
            if ($gender == 'male' && !isset($_POST['military'])) $messages[] = "وضعیت نظام وظیفه را مشخص کنید";
            else if ($gender == 'male' && isset($_POST['military'])) {
                $military = $_POST['military'];
            }

            // username validation
            if (!validateUsername($username)) $messages[] = "نام کاربری باید با حروف لاتین باشد";

            // first name and last name validation
            if (!validatePersianName($first_name) || !validatePersianName($last_name)) $messages[] = "نام و نام خانوادگی باید فارسی وارد شود";

            // Image validations
            if ( // if image file is not set and it was default before in database:
                (!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) &&
                $current_member['image'] == "assets/images/default.jpg"
            ) {
                $image = "assets/images/default.jpg";
            } else if ( // if image file is not set but member set it before:
                (!isset($_FILES['image']) ||
                    $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) &&
                $current_member['image'] != "assets/images/default.jpg"
            ) {
                $image = $current_member['image'];
            } // else image file is set:
            else {
                if (!validateImageSize($_FILES['image'])) $messages[] = "حجم عکس باید کمتر از 200 کیلو بایت باشد";
                if (!validateImageFormat($_FILES['image'])) $messages[] = "فرمت عکس باید jpg یا jpeg باشد";
                $image_file = $_FILES['image'];
                $image = "assets/images/" . $_FILES['image']['name'];
            }


            // In case no error occurs : REDIRECT to index.php
            if (!$messages) {
                // if updating member was successful
                if ($member->updateMemberByNationCode($first_name, $last_name, $nation_code, $phone_number, $birth_date, $gender, $military, $email, $image, $username, $province, $city)) {

                    // adding a new log
                    $log->insertNewLog(
                        $operation = 'update',
                        $date = miladiToJalali(date("Y/m/d")),
                        $member_username = $current_member['username'],
                        $description = "Member info was updated"
                    );

                    // save and resize image in directory
                    if ($image != "assets/images/default.jpg") {
                        // if image was uploaded save the image
                        $img_path = saveImageToDirectory($image_file, "./assets/images/");
                        // if image saved resize it first and replace it with resized image
                        if ($img_path) resizeImage($img_path);
                    }

                    // redirect to index.php
                    header('Location: index.php');
                }
            }
        } else if (isset($_POST['cancel_submit'])) {
            // in case cancel button clicked, redirect to index.php
            header('Location: index.php');
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
                «ویرایش پروفایل
                <?php echo $current_member['first_name']; ?>»
            </h1>
            <form action="" method="post" enctype="multipart/form-data" name="edit-profile">
                <!-- first name ------------------------------------>
                <div class="form-group mb-3">
                    <label for="first-name">نام:<span class="text-danger">*</span></label>
                    <input
                            required
                            value="<?php echo $current_member['first_name']; ?>"
                            type="text"
                            name="first_name"
                            id="first-name"
                            class="form-control"
                    />
                </div>

                <!-- last name ------------------------------------------>
                <div class="form-group mb-3">
                    <label for="last-name">نام خانوادگی:<span class="text-danger">*</span>
                    </label>
                    <input
                            required
                            value="<?php echo $current_member['last_name']; ?>"
                            type="text"
                            name="last_name"
                            id="last-name"
                            class="form-control"
                    />
                </div>

                <!-- nation code ---------------------------------------->
                <div class="form-group mb-3">
                    <label for="nation-code"
                    >کد ملی:<span class="text-danger">*</span>
                    </label>
                    <input
                            required
                            value="<?php echo $current_member['nation_code']; ?>"
                            type="text"
                            name="nation_code"
                            id="nation-code"
                            class="form-control"
                    />
                </div>

                <!-- phone number --------------------------------------->
                <div class="form-group mb-3">
                    <label for="phone-number"
                    >شماره همراه:<span class="text-danger">*</span></label
                    >
                    <input
                            required
                            value="<?php echo $current_member['phone_number']; ?>"
                            type="text"
                            name="phone_number"
                            id="phone-number"
                            class="form-control"
                    />
                </div>

                <!-- birth date ----------------------------------------->
                <div class="form-group mb-3">
                    <label for="birth-date">تاریخ تولد:</label>
                    <input
                            value="<?php echo $current_member['birth_date']; ?>"
                            type="text"
                            name="birth_date"
                            id="birth-date"
                            class="form-control"
                    />
                    <span id="birth-date-span"></span>
                    <script type="text/javascript">
                        $("#birth-date").persianDatepicker({
                            selectedDate: "1395/5/5"
                        });
                    </script>
                </div>

                <!-- gender --------------------------------------------->
                <div class="form-group my-3">
                    <label>جنسیت:<span class="text-danger">*</span></label>
                    <br/>
                    <!--male-->
                    <div id="male-div">
                        <input
                            <?php if ($current_member['gender'] == 'male') echo 'checked'; ?>
                                type="radio"
                                id="male"
                                name="gender"
                                value="male"
                                required
                        />
                        <label for="male">مرد</label>
                    </div>
                    <!--female-->
                    <div id="female-div" style="width: 37px;">
                        <input type="radio" id="female" name="gender" value="female"
                            <?php if ($current_member['gender'] == 'female') echo 'checked'; ?>/>

                        <label for="female">زن</label>
                    </div>
                </div>

                <!-- Military service ----------------------------------->
                <?php // if ($current_member['gender'] == 'female') { ?>
                <div class="form-group my-3" id="military-section">
                    <label>نظام وظیفه:<span class="text-danger">*</span></label>
                    <br/>
                    <input
                        <?php if ($current_member['military'] == 'finished') echo 'checked'; ?>
                            type="radio"
                            id="finished"
                            name="military"
                            value="finished"
                    />
                    <label for="finished">خدمت کرده</label>
                    <input
                        <?php if ($current_member['military'] == 'permanent-exemption') echo 'checked'; ?>
                            type="radio"
                            id="permanent-exemption"
                            name="military"
                            value="permanent-exemption"
                    />
                    <label for="permanent-exemption">معافیت دائم</label>
                    <input
                        <?php if ($current_member['military'] == 'temporary-exemption') echo 'checked'; ?>
                            type="radio"
                            id="temporary-exemption"
                            name="military"
                            value="temporary-exemption"
                    />
                    <label for="temporary-exemption">معافیت موقت</label>
                    <input
                        <?php if ($current_member['military'] == 'medical-exemption') echo 'checked'; ?>
                            type="radio"
                            id="medical-exemption"
                            name="military"
                            value="medical-exemption"
                    />
                    <label for="medical-exemption">معافیت پزشکی</label>
                </div>
                <?php //} ?>

                <!-- email ---------------------------------------------->
                <div class="form-group mb-3">
                    <label for="email">ایمیل:<span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="<?php echo $current_member['email']; ?>"/>
                </div>

                <!-- image ---------------------------------------------->
                <div class="form-group my-3">
                    <label for="image">عکس:</label><br>
                    <img src="<?php echo $current_member['image']; ?>" class="image-fluid card-img-top my-2"
                         style="width: 100px">
                    <input type="file" name="image" id="image" class="form-control"/>
                </div>

                <!-- username ------------------------------------------->
                <div class="form-group mb-3">
                    <label for="username">نام کاربری:<span class="text-danger">*</span></label>
                    <input
                            value="<?php echo $current_member['username']; ?>"
                            type="text"
                            name="username"
                            id="username"
                            class="form-control"
                    />
                </div>

                <!-- province ---------------------------------------------->
                <div class="form-group mb-3">
                    <label for="province">استان:</label>

                    <!-- check if province was set before -->
                    <?php
                    // if province was set
                    if ($current_member['province']) {
                        $province_code = $current_member['province'];
                    } // if province was not set
                    else {
                        $province_code = null;
                    } ?>

                    <select name="state"
                            onChange="loadCitiesByProvinceId(this.value);"
                            class="form-select form-select-sm"
                            aria-label=".form-select-sm example" id="province-select">
                        <option value="" disabled
                            <?php if (!$province_code) echo 'selected'; ?> >استان را انتخاب کنید
                        </option>

                        <?php
                        // show all provinces in state select
                        $all_provinces = $province->getAllProvinces();
                        foreach ($all_provinces as $province) {
                            $province_id = $province['id'];
                            $province_name = $province['name'];
                            // if province column was set by this province
                            if ($province_id == $province_code) { ?>
                                <option selected
                                        value="<?php echo $province_id; ?>">
                                    <?php echo $province_name; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $province_id; ?>">
                                    <?php echo $province_name; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>

                <!-- city ------------------------------------------------>
                <div class="form-group mb-3">
                    <label for="city">شهر:</label>

                    <!-- check if city was also set before -->
                    <?php
                    // if city is set
                    if ($current_member['city']) {
                        $city_code = $current_member['city'];
                    } // if city was not set
                    else {
                        $city_code = null;
                    } ?>

                    <select name="city" id="city"
                            class="form-select form-select-sm"
                            aria-label=".form-select-sm example">
                        <?php
                        // if province was set before, add cities to city select
                        if ($province_code) {
                            $all_province_cities = $city->getAllCitiesByProvinceId($province_code);
                            foreach ($all_province_cities as $city) {
                                $city_id = $city['id'];
                                $city_name = $city['name'];
                                if ($city_id == $city_code) { ?>
                                    <option selected
                                            value="<?php echo $city_id; ?>">
                                        <?php echo $city_name; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $city_id; ?>">
                                        <?php echo $city_name; ?></option>
                                <?php }
                            }
                        } // if province is not selected
                        else { ?>
                            <option value="" disabled selected>استان را انتخاب کنید</option>
                        <?php } ?>
                    </select>
                </div>

                <!-- submit form button -->
                <button type="submit" name="update_submit" class="btn btn-primary px-4 mt-1">ویرایش</button>
                <!-- cancel button -->
                <button type="submit" name="cancel_submit" class="btn btn-secondary px-4 mt-1">لغو</button>
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