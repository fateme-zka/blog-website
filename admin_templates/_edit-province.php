<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {
    ?>

    <section class="col-10 mx-auto">
        <div class="container p-3">

            <?php
            // if province id is set in url onload page
            if (isset($_GET['province_id'])) {
                $province_id = $_GET['province_id'];
                $current_province = $province->getProvinceById($province_id);

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    // if user clicked on province_submit button
                    if (isset($_POST['province_submit'])) {
                        $province_name = $_POST['province_name'];
                        if (validatePersianName($province_name)) {
                            $province->updateProvinceName($province_id, $province_name);

                            // redirect to admin-provinces page
                            header('Location: admin-provinces.php');
                        } else echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">نام استان باید فارسی باشد</div>';
                    }
                }

                ?>


                <h2 class="mb-3">ویرایش استان</h2>
                <form action="" method="post">
                    <!-- province input --------------------------------------->
                    <div class="form-group my-4">
                        <label for="province-name">نام استان:</label>
                        <input type="text" name="province_name"
                               value="<?php echo $current_province['name']; ?>"
                               id="province-name" class="form-control mt-3" required/>
                    </div>

                    <!-- submit button --------------------------------------->
                    <div class="form-group">
                        <button type="submit" name="province_submit" class="btn btn-success text-light">تغییر</button>
                    </div>
                </form>

            <?php } else echo 'else'; ?>
        </div>
    </section>

    <?php
}
?>