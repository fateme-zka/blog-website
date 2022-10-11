<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {
    ?>

    <!-- to show the current tab in sidebar by activating the link -->
    <script type="text/javascript">
        document.querySelector("#admin-logs").classList.add('active');
    </script>

    <section class="col-10">
        <div class="container p-3">

            <h2 class="mb-3">لیست تمام Log ها</h2>
            <!-- show all authors -->
            <table class="table table-bordered font-size-14">
                <!-- header of table columns -->
                <thead>
                <tr class="text-center">
                    <th scope="col">*</th>
                    <th scope="col">عملیات</th>
                    <th scope="col">تاریخ</th>
                    <th scope="col">نام کاربری</th>
                    <th scope="col">توضیحات</th>
                </tr>
                </thead>

                <!-- body of table: foreach to all members to show the row of their information -->
                <tbody>
                <?php $all_logs = $log->getAllLogs();
                $number = 1;
                foreach ($all_logs as $log) {
                    $log_title = $log['operation'];
                    $log_date = $log['date'];
                    $log_member_id = $log['member_username'];
                    $log_description = $log['description'];
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $number; ?></th>
                        <td><?php echo $log_title; ?></td>
                        <td><?php echo $log_date; ?></td>
                        <td><?php echo $log_member_id; ?></td>
                        <td><?php echo $log_description; ?></td>
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