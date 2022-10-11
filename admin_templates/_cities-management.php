<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on city_delete_submit button to delete that city
        if (isset($_POST['city_delete_submit'])) {
            $id = $_POST['city_id_input'];
            if ($city->deleteCityById($id)) {
                // refresh page
                header('Refresh:0');
            }
        }

        // if user clicked on add_city_submit button to insert a new city
        if (isset($_POST['add_city_submit'])) {
            $city_name = $_POST['city_name'];

            // if entered name as city's name was not persian
            if (!validatePersianName($city_name)) {
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">نام شهر باید فارسی باشد</div>';
            } // if province select option was not set
            else if (!isset($_POST['state'])) {
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">استان را انتخاب کنید</div>';
            } // in case no error occurs
            else {
                $city_province_id = $_POST['state'];
                $city->insertNewCity($city_name, $city_province_id);

                // refresh page
                header('Refresh:0');
            }

        }

    }
    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-cities").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">


            <h2 class="mb-3">اضافه کردن شهر</h2>

            <!-- add new city form -->
            <form action="" method="post">

                <!-- city name ---------------------------------------->
                <div class="form-group mt-4">
                    <label for="city-name">نام شهر:</label>
                    <input type="text" name="city_name"
                           id="city-name" class="form-control mt-3" required/>
                </div>

                <!-- city province ------------------------------------>
                <div class="form-group mb-3">
                    <div class="form-group mb-3">
                        <label for="province">استان:</label>
                        <select name="state" required
                                onChange="loadCitiesByProvinceId(this.value);"
                                class="form-select form-select-sm"
                                aria-label=".form-select-sm example" id="province">
                        </select>
                    </div>
                </div>

                <!-- add button ---------------------------------------->
                <div class="form-group">
                    <button type="submit" name="add_city_submit" class="btn btn-success text-light">افزودن شهر</button>
                </div>
            </form>

            <br>
            <hr>


            <h2 class="mb-3">لیست تمام شهرستان ها</h2>
            <!-- show all cities table tag -->
            <table class="table table-bordered font-size-14">
                <!-- header of table columns -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">نام شهرستان</th>
                    <th scope="col">استان</th>
                    <th scope="col" colspan="2">مدیریت شهرستان</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all cities to show the row of their information -->
                <tbody>
                <?php $all_cities = $city->getAllCities();
                $number = 1;
                foreach ($all_cities as $city) {
                    $city_id = $city['id'];
                    $name = $city['name'];
                    $city_province = $province->getProvinceNameById($city['province_id']); ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $city_province; ?></td>
                        <!-- edit city button -->
                        <td><a href="admin-edit-city.php?city_id=<?php echo $city_id; ?>">ویرایش</a></td>
                        <td>
                            <!-- delete city button form -->
                            <form action="" method="post">
                                <input type="hidden" name="city_id_input" value="<?php echo $city_id; ?>">
                                <button type="submit" name="city_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php $number++; // to show the counter of table tag rows
                } ?>
                </tbody>
            </table>

        </div>
    </section>
    <?php
}
?>