<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {

    // if request method was post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // if user clicked on province_submit_delete button to delete that province
        if (isset($_POST['province_delete_submit'])) {
            $id = $_POST['province_id_input'];
            if ($province->deleteProvinceById($id)) {
                header('Refresh:0');
            }
        }

        // if user clicked on add_province_submit button to insert a new province
        if (isset($_POST['add_province_submit'])) {
            $province_name = $_POST['province_name'];
            if (validatePersianName($province_name)) {
                $province->insertNewProvince($province_name);

                // refresh page
                header('Refresh:0');
            } else
                echo '<div class="alert alert-danger text-center mb-1" role="alert" onclick="this.remove();">نام استان باید فارسی باشد</div>';
        }

    }
    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-provinces").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">

            <h2 class="mb-3">اضافه کردن استان</h2>
            <!-- add new province form -->
            <form action="" method="post">
                <div class="form-group my-4">
                    <label for="province-name">نام استان:</label>
                    <input type="text" name="province_name"
                           id="province-name" class="form-control mt-3" required/>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_province_submit" class="btn btn-success text-light">افزودن استان
                    </button>
                </div>
            </form>

            <br>
            <hr>

            <h2 class="mb-3">لیست تمام استان ها</h2>
            <!-- show all provinces table -->
            <table class="table table-bordered font-size-14">

                <!-- header of table columns -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">نام استان</th>
                    <th scope="col" colspan="2">مدیریت استان</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all provinces to show the row of their information -->
                <tbody>
                <?php $all_provinces = $province->getAllProvinces();
                $number = 1;
                foreach ($all_provinces as $province) {
                    $province_name = $province['name'];
                    $province_id = $province['id']; ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $province_name; ?></td>
                        <td><a href="admin-edit-province.php?province_id=<?php echo $province_id; ?>">ویرایش</a></td>
                        <td>
                            <!-- delete province button and input-->
                            <form action="" method="post">
                                <input type="hidden" name="province_id_input" value="<?php echo $province_id; ?>">
                                <button type="submit" name="province_delete_submit"
                                        class="btn text-danger p-0 m-0 font-size-14">حذف
                                </button>
                            </form>
                        </td>
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