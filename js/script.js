//Female Gender Hiding -----------------------------------------------
// in case user checked female radio button
$('#female').change((function () {
    $("#military-section").css("display", "none");
}));
// in case user checked male radio button
$('#male').change((function () {
    $("#military-section").css("display", "block");
}));


// Project Location for ajax url ----------------------------------------------------
const loc = window.location.pathname;
const dir = loc.substring(0, loc.lastIndexOf("/"));
const cityAjaxUrl = 'http://localhost' + dir + '/templates/cities-ajax.php';
const provinceAjaxUrl = 'http://localhost' + dir + '/templates/provinces-ajax.php';

// Load Provinces ---------------------------------------------------------
function loadProvinces() {
    let provinceSelect = $('#province');
    // empty province select at first
    provinceSelect.empty();
    // append disable selected option to show at first
    provinceSelect.append($('<option>', {
        value: 0,
        text: 'لطفا استان را انتخاب نمایید',
        selected: true,
        disabled: true
    }));
    $.ajax({
        url: provinceAjaxUrl,
        method: 'POST',
        data: {get_provinces: true},
        success: function (data) {
            let provinces = JSON.parse(data);

            // append all provinces to province select tag as option tag
            provinces.forEach(function (province) {
                provinceSelect.append($('<option>', {
                    value: province['id'], text: province['name']
                }));
            })
        }

    })
}

loadProvinces();

// Get all cities base on province id-----------------------------------------
function loadCitiesByProvinceId(provinceId) {
    let citySelect = $('#city');
    // remove all previous option tags from city select
    citySelect.empty();

    citySelect.append($('<option>', {value: 0, text: 'لطفا شهر را انتخاب نمایید', disabled: true, selected: true}));

    $.ajax({
        url: cityAjaxUrl,
        method: 'POST',
        data: {province_id: provinceId},
        success: function (data) {

            let cities = JSON.parse(data);

            // append all cities of province ot city select tag as option tag
            cities.forEach(function (city) {
                citySelect.append($('<option>', {
                    value: city['id'], text: city['name']
                }));
            })
        }
    })
}


