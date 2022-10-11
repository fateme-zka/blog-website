<?php
// require this file to  convert date to jalali or vice versa
require("gregorian_jalali.php");

// require all database classes
require("database/Member.php");
require("database/City.php");
require("database/Province.php");
require("database/Log.php");
require("database/Post.php");
require("database/Permission.php");

// create new object of each database classes
$member = new Member();
$province = new Province();
$city = new City();
$log = new Log();
$post = new Post();
$permission = new Permission();


// check nation code: return false or true
function checkNationalCode($nation_code)
{
    if (!preg_match('/^[0-9]{10}$/', $nation_code))
        return false;
    for ($i = 0; $i < 10; $i++)
        if (preg_match('/^' . $i . '{10}$/', $nation_code))
            return false;
    for ($i = 0, $sum = 0; $i < 9; $i++)
        $sum += ((10 - $i) * intval(substr($nation_code, $i, 1)));
    $ret = $sum % 11;
    $parity = intval(substr($nation_code, 9, 1));
    if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity))
        return true;
    return false;
}


// check phone number: return false or true
function validatePhoneNumber($phone)
{
    $pattern = '/^09[0-9]{9}$/';
    preg_match($pattern, $phone, $matches);
    return ($matches) ? true : false;
}


// check firstName and lastName: return true if it's persian else false
function validatePersianName($name)
{
    if (preg_match('/^[^\x{600}-\x{6FF}]+$/u', str_replace("\\\\", "", $name))) {
        return false;
    }
    return true;
}


// check username: latin and not to start with number and no special character
function validateUsername($username)
{
    $pattern = "/^([^0-9])([a-zA-Z0-9]*)$/";
    $res = preg_match($pattern, $username, $matches);
//    print_r($matches);
    return $res;
}


// calculate age base on jalali birth date
function calculateAge($birth_date)
{
//    example birth-date
//    $birth_date = '1390/7/11';
    $date = str_replace(['-', '/'], '/', $birth_date);
    $date = explode('/', $date); // => ['year','month','day']
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];

    //convert jalali birth date to miladi
    $birthDate = jalali_to_gregorian($year, $month, $day, '/'); // yyyy/mm/dd
    //explode the date to get month, day and year
    $birthDate = explode("/", $birthDate);
    $year = $birthDate[0];
    $month = $birthDate[1];
    $day = $birthDate[2];
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $month, $day, $year))) > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));
    return $age;
}


// check birth-date: return true if age equal or more than 10 else false
function validateAge($birth_date)
{
    $age = calculateAge($birth_date);
    if ($age < 10) return false;
    return true;
}


// IMAGE Validation Functions:

// check image size (should be less than 200kb)
function validateImageSize($image)
{
    if ($image['size'] > 200000) return false;
    return true;
}

// check image format (should be jpg or jpeg)
function validateImageFormat($image)
{
//    $finfo = finfo_open(FILEINFO_MIME_TYPE);
//    $mime = finfo_file($finfo, $image['tmp_name']);
//    finfo_close($finfo);
//    if ($mime != 'image/jpg' && $mime != 'image/jpeg') return false;
//    return true;
    $filename = basename($image['name']);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (
        in_array($image['type'], ['image/jpg', 'image/jpeg']) &&
        in_array(strtolower($ext), ['jpg', 'jpeg'])
    ) {
        return true;
    }
    return false;
}

// you should save file before resize it
function saveImageToDirectory($image, $path)
{
//    set image [address + name]
//    $path = './assets/images/';
    $uploadfile = $path . basename($image['name']);

    // save it in assets/images directory
    if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
        return $uploadfile;
    }
    return false;
}

// resize image (400x400):
function resizeImage($fileSrc, $newSize = 400)
{
    // Get new sizes
    list($width, $height) = getimagesize($fileSrc);
    $newwidth = $newSize;
    $newheight = $newSize;

    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($fileSrc);

    // Resize
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // replace resized image with uploaded image
    imagejpeg($thumb, $fileSrc);
}


// convret miladi to jalali
function miladiToJalali($date)
{
    $date = explode('/', $date); // => ['year','month','day']
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];

    //convert miladi date to jalali
    return gregorian_to_jalali($year, $month, $day, '/'); // yyyy/mm/dd
}


// convert numbers to farsi numbers
function fa_number($number)
{
    if (!is_numeric($number) || empty($number))
        return '۰';
    $en = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $fa = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
    return str_replace($en, $fa, $number);
}


//Email Verification Functions----------------------
// create verification key
function randomEmailCode()
{
    $random_number = rand(1000, 9999);
    return strval($random_number);
}

// send email with vkey and username
function sendEmail($username, $email, $vkey)
{
    //subject
    $subject = "(Dornica Email Verification)";

    // the message
    $msg = "سلام {$username} \n
    به درنیکا خوش آمدی\n
    کد تایید ایمیل شما: {$vkey}";
    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg, 70);

    // header
    $headers = "From: fzkworks@gmail.com \r\n";

    // send email
    mail($email, $subject, $msg, $headers);
}

// show inaccessible warning message
function showInaccessibleWarning()
{
    echo '<div class="w-75 mx-auto my-5">
            <h2>شما به این صفحه دسترسی ندارید</h2>
            <div>(اول وارد حساب کاربری خود بشوید)</div>
            <a href="index.php" class="btn btn-info my-4 text-light">صفحه ی اصلی</a>
        </div>';
}


//convert sql array to csv file [address: csvfile/reportingResults.csv]
function arrayToCsvAndSave($results)
{
    // create a file pointer connected to the output stream
    $output = fopen('csvfile/reportingResults.csv', 'w');

    // output the column headings
    fputcsv($output, array('نام', 'نام خانوادگی', 'نام کاربری', 'جنسیت', 'نظام وظیفه', 'تاریخ تولد', 'کد ملی', 'کد استان', 'کد شهر', 'شماره تلفن', 'ایمیل'));

    // loop over the rows, outputting them
    foreach ($results as $row) {
        fputcsv($output, $row);
    }
    return true;
}


// calculate reading time of post
function calculateReadingTime($text)
{
    function cleanString($text)
    {
        $utf8 = array('/[!؟.،:;]/u' => "");
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    // remove all characters(!؟،:;) from string
    $new_text = cleanString($text);
    // convert all multiple spaces and \n and \t to single space => /\n\t\s+/
    $new_text = preg_replace('/\s+/', ' ', $new_text);
    // separate all string words by space
    $words = explode(' ', $new_text);
    return ceil(count($words) / 200);

}


// show message that only managers can access to this page
function showOnlyMangersAccess()
{
    echo '<div class="w-75 mx-auto my-5">
            <h2 class="mb-3">این صفحه برای مدیران سایت است</h2>
            <div>(شما به این صفحه دسترسی ندارید)</div>
        </div>';
}


// get some number of the first words of the text
// to show for example 40 first word of post description in index.php page
function getNumberOfFirstWords($text, $numberOf)
{
    return implode(' ', array_slice(explode(' ', $text), 0, $numberOf)) . ".....";
}

