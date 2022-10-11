<?php

// to show error messages
$messages = [];

// if method was POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])) {
        // ----------get data from each input:
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $nation_code = $_POST['nation_code'];
        $phone_number = $_POST['phone_number'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $cpassword = md5($_POST['cpassword']);
        $membership_date = miladiToJalali(date("Y/m/d"));
        $vkey = randomEmailCode();

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

        // password validation
        if ($password != $cpassword) $messages[] = "رمز عبور و تکرار رمز عبور باید یکسان باشند";
        // nation code validation
        if (!checkNationalCode($nation_code)) $messages[] = 'کد ملی وارد شده نادرست است';
        // existence of member validation
        if ($member->checkExistenceOfMember($nation_code, $username)) $messages[] = "کابری با این کد ملی و یا نام کاربری وجود دارد";
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
        if (!validatePersianName($first_name) || !validatePersianName($last_name)) $messages[] = "نام و نام خانوادگی باید فارسی وارد شد";
        // Image validations
        if ( // is image file is not set
            !isset($_FILES['image']) ||
            $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE
        ) {
            $image = "assets/images/default.jpg";
        } else { // if image is uploaded
            if (!validateImageSize($_FILES['image'])) $messages[] = "حجم عکس باید کمتر از 200 کیلو بایت باشد";
            if (!validateImageFormat($_FILES['image'])) $messages[] = "فرمت عکس باید jpg یا jpeg باشد";
            $image_file = $_FILES['image'];
            $image = "assets/images/" . $_FILES['image']['name'];
        }
        // captcha validation
        if ($_POST['captcha'] != $_SESSION['captcha']) $messages[] = "کد امنیتی نادرست است";


        // In case no error occurs: REDIRECT to confirm-email.php
        if (!$messages) {
            // if inserting member was successful
            if ($member->insertNewMember($first_name,
                $last_name,
                $nation_code,
                $phone_number,
                $birth_date,
                $gender,
                $military,
                $email,
                $image,
                $username,
                $password,
                $province,
                $city,
                $membership_date,
                $vkey)) {

                // send verification email to member
                sendEmail($username, $email, $vkey);

                // save and resize image in directory
                if ($image != "assets/images/default.jpg") {
                    // if image was uploaded save the image
                    $img_path = saveImageToDirectory($image_file, "./assets/images/");
                    // if image saved resize it first and replace it with resized image
                    if ($img_path) resizeImage($img_path);
                }

                // set nation code in session (temporary)
                $_SESSION['nation_code'] = $nation_code;
                $_SESSION['signup_completed'] = false;

                ob_clean();

                // adding a new log
                $log->insertNewLog(
                    $operation = 'insert',
                    $date = miladiToJalali(date("Y/m/d")),
                    $member_username = $username,
                    $description = "New member added to members"
                );

                // redirect to confirm-email page
                header('Location: confirm-email.php');
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

    <div class="container my-3 w-75">
        <h1 class="text-center">«ثبت نام عضو جدید»</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- first name ------------------------------------>
            <div class="form-group mb-3">
                <label for="first-name">نام:<span class="text-danger">*</span></label>
                <input
                        required
                        type="text"
                        name="first_name"
                        id="first-name"
                        class="form-control"
                />
            </div>

            <!-- last name ------------------------------------------>
            <div class="form-group mb-3">
                <label for="last-name"
                >نام خانوادگی:<span class="text-danger">*</span>
                </label>
                <input
                        required
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
                        type="text"
                        name="birth_date"
                        id="birth-date"
                        class="form-control"
                />
                <span id="birth-date-span"></span>
                <script type="text/javascript">
                    $("#birth-date").persianDatepicker({
                        // default value to show
                        selectedDate: "1401/1/1"
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
                            type="radio"
                            id="male"
                            name="gender"
                            value="male"
                            checked
                            required
                    />
                    <label for="male">مرد</label>
                </div>
                <!--female-->
                <div id="female-div" style="width: 37px;">
                    <input type="radio" id="female" name="gender" value="female"/>
                    <label for="female">زن</label>
                </div>
            </div>

            <!-- Military service ----------------------------------->
            <div class="form-group my-3" id="military-section">
                <label>نظام وظیفه:<span class="text-danger">*</span></label>
                <br/>
                <input
                        type="radio"
                        id="finished"
                        name="military"
                        value="finished"
                />
                <label for="finished">خدمت کرده</label>
                <input
                        type="radio"
                        id="permanent-exemption"
                        name="military"
                        value="permanent-exemption"
                />
                <label for="permanent-exemption">معافیت دائم</label>

                <input
                        type="radio"
                        id="temporary-exemption"
                        name="military"
                        value="temporary-exemption"
                />
                <label for="temporary-exemption">معافیت موقت</label>
                <input
                        type="radio"
                        id="medical-exemption"
                        name="military"
                        value="medical-exemption"
                />
                <label for="medical-exemption">معافیت پزشکی</label>
            </div>

            <!-- email ---------------------------------------------->
            <div class="form-group mb-3">
                <label for="email">ایمیل:<span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control"/>
            </div>

            <!-- image ---------------------------------------------->
            <div class="form-group my-3">
                <label for="image">عکس:</label>
                <input type="file" name="image" id="image" class="form-control"/><br/>
            </div>

            <!-- username ------------------------------------------->
            <div class="form-group mb-3">
                <label for="username">نام کاربری:<span class="text-danger">*</span></label>
                <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-control"
                />
            </div>

            <!-- password ------------------------------------------->
            <div class="form-group mb-3">
                <label for="password"
                >رمز عبور:<span class="text-danger">*</span></label
                >
                <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                />
            </div>

            <!-- confirm password ----------------------------------->
            <div class="form-group mb-3">
                <label for="cpassword">تکرار رمز عبور:</label>
                <input
                        required
                        type="password"
                        name="cpassword"
                        id="cpassword"
                        class="form-control"
                />
            </div>

            <!-- province -->
            <div class="form-group mb-3">
                <label for="province">استان:</label>
                <select name="state"
                        onChange="loadCitiesByProvinceId(this.value);"
                        class="form-select form-select-sm"
                        aria-label=".form-select-sm example" id="province">

                </select>
            </div>

            <!-- city -->
            <div class="form-group mb-3">
                <label for="city">شهر:</label>
                <select name="city" id="city" class="form-select form-select-sm" aria-label=".form-select-sm example">
                    <option value="0" disabled selected>لطفا استان را انتخاب نمایید</option>
                </select>
            </div>

            <!-- Captcha Code -->
            <div class="form-group mb-3 mt-5 w-50">
                <label for="captcha-code">کد امنیتی:<span class="text-danger">*</span></label>
                <input type="text" name="captcha" id="captcha-code" class="form-control mb-2" required>
                <img src="functions/captcha.php" alt="CAPTCHA" class="captcha-image">
            </div>

            <!-- submit form button -->
            <button type="submit" name="submit" class="btn btn-primary px-4 mt-1">ثبت</button>
        </form>
        <!-- close form -->
    </div>
    <!-- close container div -->

</section>