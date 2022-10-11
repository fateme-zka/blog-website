<?php

class Post
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

    // get all post of posts table
    public function getAllPosts()
    {
        $query = "SELECT * FROM `posts` WHERE 1 ORDER BY `id` DESC ";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;

    }

    // get all author's posts by author_id
    public function getAuthorPostsById($id)
    {
        $query = "SELECT * FROM `posts` WHERE `author_id`='$id'";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;
    }

    // insert a new post
    public function insertNewPost($title, $description, $author_id, $category, $image, $reading_time)
    {
        $query = "INSERT INTO `posts`(`title`, `description`, `author_id`, `category`, `image`, `reading_time`) VALUES ('$title', '$description', '$author_id', '$category', '$image', '$reading_time')";
        return $this->connection->query($query);
    }

    // get a post by id
    public function getPostById($id)
    {
        $query = "SELECT * FROM `posts` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result;
    }

    // update a post info by id
    public function updatePostById($id, $title, $category, $image, $description, $reading_time)
    {
        $query = "UPDATE `posts` SET `title`='$title',`category`='$category', `image`='$image',`description`='$description',`reading_time`='$reading_time' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // delete a post by id
    public function deletePostById($id)
    {
        $query = "DELETE FROM `posts` WHERE `id`='$id'";
        return $this->connection->query($query);
    }

}