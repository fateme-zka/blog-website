<?php

class Member
{
    // Database connection properties
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $db_name = "dornica";

    // connection property
    public $connection = null;

    // call constructor to connect to database (dornica)
    public function __construct()
    {
        $this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
        if ($this->connection->connect_error) {
            echo 'Failed to connect to database: ' . $this->connection->connect_error;
        }
    }

    // check if any member with this nation code or username exists or not
    public function checkExistenceOfMember($nation_code, $username)
    {
        $query = "SELECT * FROM `members` WHERE nation_code = '$nation_code' OR username = '$username'";
        $result = $this->connection->query($query);
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    // check if any member with this username and password exists to let him logins or not
    public function checkExistenceOfMemberByUsernamePassword($username, $password)
    {
        $query = "SELECT * FROM `members` WHERE `username` = '$username' AND `password` = '$password'";
        $result = $this->connection->query($query);
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    // check if member is verified to let him/her logins
    public function isMemberVerified($nation_code)
    {
        $query = "SELECT * FROM `members` WHERE `nation_code` = '$nation_code' AND `verified` = '1'";
        $result = $this->connection->query($query);
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    // check if """another""" member with this nation code or username exists or not
    public function checkExistenceOfAnotherMember($nation_code, $username)
    {
        $query = "SELECT * FROM `members` WHERE nation_code = '$nation_code' OR username = '$username'";
        $result = $this->connection->query($query);
        return mysqli_num_rows($result) > 1;
    }

    // insert new member to members table
    public function insertNewMember($first_name,
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
                                    $vkey
    )
    {
        $query = "INSERT INTO `members`(`first_name`, `last_name`, `nation_code`, `phone_number`, `birth_date`, `gender`, `military`, `email`, `image`, `username`, `password`, `province`, `city`, `membership_date`, `vkey`, `member_type_id`) VALUES ('$first_name','$last_name','$nation_code','$phone_number','$birth_date','$gender','$military','$email','$image','$username','$password','$province','$city','$membership_date','$vkey','3')";
        if ($this->connection->query($query)) return true;
        return false;
    }


    // update member info by id
    public function updateMemberByNationCode($first_name,
                                             $last_name,
                                             $nation_code,
                                             $phone_number,
                                             $birth_date,
                                             $gender,
                                             $military,
                                             $email,
                                             $image,
                                             $username,
                                             $province,
                                             $city
    )
    {
        $query = "UPDATE `members` SET `first_name`='$first_name',`last_name`='$last_name',`nation_code`='$nation_code',`phone_number`='$phone_number',`birth_date`='$birth_date',`gender`='$gender',`military`='$military',`email`='$email',`image`='$image',`username`='$username',`province`='$province',`city`='$city' WHERE `nation_code`='$nation_code'";
        return ($this->connection->query($query));
    }


    // get the member by nation_code
    public function getMemberByNationCode($nation_code)
    {
        $query = "SELECT * FROM `members` WHERE nation_code = '$nation_code'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result;
    }

    // get the member by id
    public function getMemberById($id)
    {
        $query = "SELECT  * FROM `members` WHERE `id` = '$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result;
    }


    // verified the member after email verification
    public function verifyMemberByNationCode($nation_code)
    {
        $query = "UPDATE `members` SET `verified`= 1 WHERE `nation_code` = '$nation_code'";
        $this->connection->query($query);
    }

    // update member vkey after clicking resend button
    public function updateVkeyByNationCode($nation_code, $new_vkey)
    {
        $query = "UPDATE `members` SET `vkey`= '$new_vkey' WHERE `nation_code` = '$nation_code'";
        return $this->connection->query($query);
    }


    // change password of member by nation code
    public function changePassByNationCode($nation_code, $new_pass)
    {
        $query = "UPDATE `members` SET `password`='$new_pass' WHERE `nation_code`='$nation_code'";
        if ($this->connection->query($query)) return true;
        return false;
    }

    // convert member to author
    public function convertMemberToAuthor($id)
    {
        $query = "UPDATE `members` SET `member_type_id`='2' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // convert member to reader
    public function convertMemberToReader($id)
    {
        $query = "UPDATE `members` SET `member_type_id`='3' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // convert member to manager
    public function convertMemberToManager($id)
    {
        $query = "UPDATE `members` SET `member_type_id`='1' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // check if password is match to member by nation code
    public function checkPasswordIsCorrect($nation_code, $pass)
    {
        $query = " SELECT * FROM `members` WHERE `nation_code`='$nation_code' AND `password`='$pass'";
        $result = $this->connection->query($query);
        return mysqli_num_rows($result) == 1;
    }

    // get all members by member type or not
    public function getAllMembers($member_type_id = null)
    {
        if (!$member_type_id) {
            $query = "SELECT * FROM `members` WHERE 1";
        } else {
            $query = "SELECT * FROM `members` WHERE `member_type_id`='$member_type_id'";
        }
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;
    }


    // get all members base on field and searching value
    public function getAllMembersBySearching($field, $search_value)
    {
        $query = "SELECT * FROM `members` WHERE `$field` LIKE '%{$search_value}%'";

        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;
    }

    // get all members base on field and filtering value
    public function getAllMembersByFiltering($gender, $province, $city)
    {
        if ($gender && $province && $city) {   // if gender & province & city are set
            $query = "SELECT * FROM `members` WHERE `gender`='$gender' AND `province`='$province' AND `city`='$city'";
        } else if ($gender && $province) {   // if gender & province are set
            $query = "SELECT * FROM `members` WHERE `gender`='$gender' AND `province`='$province'";
        } else if ($gender) {   // if only gender was set
            $query = "SELECT * FROM `members` WHERE `gender`='$gender'";
        } else if ($province && $city) {   // if state & city are set
            $query = "SELECT * FROM `members` WHERE `province`='$province' AND `city`='$city'";
        } else if ($province) {    // if only state was set
            $query = "SELECT * FROM `members` WHERE `province`='$province'";
        } else {    // if any of them aren't set
            $query = "SELECT * FROM `members` WHERE 1";
        }


        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;
    }

    // get all members base on field and searching value for converting to csv
    public function getAllMembersByFilteringCSV($gender = null, $province = null, $city = null)
    {
        $columns = "`first_name`, `last_name`, `username`, `gender`, `military`, `birth_date`, `nation_code`, `province`, `city`, `phone_number`, `email`";
        $order_by_id = "ORDER BY `id` ASC";
        if ($gender && $province && $city) {   // if gender & province & city are set
            $query = "SELECT " . $columns . " FROM `members` WHERE `gender`='$gender' AND `province`='$province' AND `city`='$city' " . $order_by_id;
        } else if ($gender && $province) {   // if gender & province are set
            $query = "SELECT " . $columns . " FROM `members` WHERE `gender`='$gender' AND `province`='$province' " . $order_by_id;
        } else if ($gender) {   // if only gender was set
            $query = "SELECT " . $columns . " FROM `members` WHERE `gender`='$gender' " . $order_by_id;
        } else if ($province && $city) {   // if state & city are set
            $query = "SELECT " . $columns . " FROM `members` WHERE `province`='$province' AND `city`='$city' " . $order_by_id;
        } else if ($province) {    // if only state was set
            $query = "SELECT " . $columns . " FROM `members` WHERE `province`='$province' " . $order_by_id;
        } else {    // if any of them aren't set
            $query = "SELECT " . $columns . " FROM `members` WHERE 1 " . $order_by_id;
        }


        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;
    }

    // delete member by id
    public function deleteMemberById($id)
    {
        $query = "DELETE FROM `members` WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // get member count of each province
    public function getCountOfMembersOfProvince($province_id)
    {
        $query = "SELECT * FROM `members` WHERE `province`='$province_id'";
        $result = $this->connection->query($query);
        // return the count of members of this province:
        return mysqli_num_rows($result);
    }


    // close connection as the object of this class destroyed
    public function __destruct()
    {
        $this->closeConnection();
    }

    // for closing connection
    protected function closeConnection()
    {
        if ($this->connection != null) {
            $this->connection->close();
            $this->connection = null;
        }
    }


}

