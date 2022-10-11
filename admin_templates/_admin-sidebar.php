<section class="col-2 p-0" style="position: relative;height: 600px">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white w-100" id="sidebar-admin">
        <ul class="nav nav-pills flex-column mb-auto p-0">
            <!-- show this tab links if login member is manager -->
            <?php // if user is not manager
            if ($permission->getPermissionTypeById($current_member['member_type_id']) == 'manager') {
                ?>
                <li class="border-bottom">
                    <a href="admin-managers.php"
                       id="admin-managers"
                       class="nav-link text-white font-size-14"
                       aria-current="page">
                        مدیریت مدیران
                    </a>
                </li>

                <li class="border-bottom">
                    <a href="admin-authors.php"
                       id="admin-authors"
                       class="nav-link text-white font-size-14"
                       aria-current="page">
                        مدیریت نویسندگان
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-provinces" href="admin-provinces.php" class="nav-link text-white font-size-14">
                        مدیریت استان ها
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-cities" href="admin-cities.php" class="nav-link text-white font-size-14">
                        مدیریت شهرستان ها
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-list-all-members" href="admin-list-all-members.php"
                       class="nav-link text-white font-size-14">
                        لیست تمام ثبت نامی ها
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-reporting" href="admin-reporting.php" class="nav-link text-white font-size-14">
                        گزارش گیری
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-logs" href="admin-logs.php" class="nav-link text-white font-size-14">
                        مدیریت log ها
                    </a>
                </li>
                <li class="border-bottom">
                    <a id="admin-province-graph" href="admin-province-graph.php"
                       class="nav-link text-white font-size-14">
                        نمودار میله استان ها
                    </a>
                </li>
            <?php } ?>
            <li class="border-bottom">
                <a id="admin-posts" href="admin-posts.php" class="nav-link text-white font-size-14">
                    مدیریت همه پست ها
                </a>
            </li>
            <li>
                <a id="admin-new-post" href="admin-new-post.php" class="nav-link text-white font-size-14">
                    تعریف پست جدید
                </a>
            </li>

        </ul>
        <hr>
        <div class="dropdown">
            <a href="index.php" class="d-flex align-items-center text-white text-decoration-none">
                خروج از داشبورد
            </a>
        </div>
    </div>
</section>



