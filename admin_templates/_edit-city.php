<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {
    ?>

    <section class="col-10 mx-auto">
        <div class="container p-3">

            <?php
            // if city id is set in url onload page
            if (isset($_GET['city_id'])) {
                $city_id = $_GET['city_id'];
                $current_city = $city->getCityById($city_id);

                // if request method was post
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    // if user clicked on city_submit button
                    if (isset($_POST['city_submit'])) {
                        $city_name = $_POST['city_name'];
                        $city_province = $_POST['state'];
                        if (validatePersianName($city_name)) {
                            $city->updateCity($city_id, $city_name,$city_province);

                            // redirect to admin-cities page
                            header('Location: admin-cities.php');
                        } else
                            echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">نام شهر باید فارسی باشد</div>';
                    }
                }

                ?>


                <h2 class="mb-3">ویرایش شهر</h2>
                <form action="" method="post">

                    <!-- city name ------------------------------------------->
                    <div class="form-group my-4">
                        <label for="city-name">نام شهر:</label>
                        <input type="text" name="city_name"
                               value="<?php echo $current_city['name']; ?>"
                               id="city-name" class="form-control mt-3" required/>
                    </div>

                    <!-- city province --------------------------------------->
                    <div class="form-group mb-3">
                        <label for="province">استان:</label>
                        <select name="state"
                                class="form-select form-select-sm"
                                aria-label=".form-select-sm example" id="province-select">
                            <option value="" disabled>استان را انتخاب کنید</option>
                            <?php
                            $city_province_id = $current_city['province_id'];
                            $all_provinces = $province->getAllProvinces();
                            // show all province and make current city province selected
                            foreach ($all_provinces as $province) {
                                $province_id = $province['id'];
                                $province_name = $province['name'];
                                // if province column was set this province
                                if ($province_id == $city_province_id) { ?>
                                    <option selected value="<?php echo $province_id; ?>">
                                        <?php echo $province_name; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $province_id; ?>">
                                        <?php echo $province_name; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>

                    <!-- city update submit button ------------------------------------>
                    <div class="form-group">
                        <button type="submit" name="city_submit" class="btn btn-success text-light">تغییر</button>
                    <a href="admin-cities.php" class="btn btn-outline-secondary">لغو</a>
                    </div>
                </form>

            <?php } else echo 'else'; ?>
        </div>
    </section>

    <?php
}
?>