<?php

// if user is not manager has no accessibility to this page
if ($permission->getPermissionTypeById($current_member['member_type_id']) != 'manager') {
    showOnlyMangersAccess();
} else {
    // create an associative array of provinces name and count of members
    $all_provinces = $province->getAllProvinces();
    $provinces_chart = [];
    foreach ($all_provinces as $p) {
        $p_count = $member->getCountOfMembersOfProvince($p['id']);
        $p_name = $p['name'];
        $provinces_chart[] = array("y" => $p_count, "label" => $p_name);
    }

    // the array will be like this
//    $provinces_chart = array(
//        array("y" => countOfMember, "label" => provinceName ), ...
//    );

    ?>


    <!-- show the result as chart -->
    <div class="container">
        <h1 class="mt-3 mb-5 text-center">نمودار تعداد کاربران هر استان</h1>
        <div id="chartContainer" style="height: 600px; " class="col-11  mx-auto"></div>
    </div>

    <!-- chart canvas javascript setting -->
    <script>
        window.onload = function () {

            let chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: ""
                },
                axisY: {
                    title: "تعداد کاربران",
                    includeZero: true,
                    // prefix: "$",
                    // suffix: "k"
                },
                data: [{
                    type: "bar",
                    // yValueFormatString: "$#,##0K",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "black",
                    dataPoints: <?php echo json_encode($provinces_chart, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>

    <!-- canvas chart link -->
    <script src="js/canvasjs.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


    <?php
}
?>