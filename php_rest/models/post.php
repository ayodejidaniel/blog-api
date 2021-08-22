<?php

class Post {
    //DB stuff
    private $conn;
    private $table = 'posts';

    //Post Properties 
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    //Constructor wit DB
    public function __construct($db) {
        $this->conn = $db;
    }

    //Get post
    public function read() {
        //Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                ' . $this->table . ' p
            LEFT JOIN
                categories c ON p.category_id = c.id
            ORDER BY
                p.created_at DESC ';
    // Prepare statment
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
    }

    //Get single Post
    public function read_single() {
        //Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                ' . $this->table . ' p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1';

    // Prepare statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindparam(1, $this->id);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Set properties
    $this->title = $row['title'];
    $this->body = $row['body'];
    $this->author = $row['author'];
    $this->category_id = $row['category_id'];
    $this->category_name = $row['category_name'];  
    }
  
    //Create post
    public function create() {
        //create query
        $query = 'INSERT INTO ' . 
                $this->table . '
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id';

        //prepare statement 
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->title = htmlspecialchars(strip_tags($this->title)); 
        $this->body = htmlspecialchars(strip_tags($this->body)); 
        $this->author = htmlspecialchars(strip_tags($this->author)); 
        $this->category_id = htmlspecialchars(strip_tags($this->category_id)); 

        //Bind data
        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':body', $this->body);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':category_id', $this->category_id);

        //Execute query
        if ($stmt->execute()) {
            return true;
        }

        //print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    //Update post
    public function update () {
        //create query
        $query = 'UPDATE ' . 
                $this->table . '
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE
                id = :id';

        //prepare statement 
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->title = htmlspecialchars(strip_tags($this->title)); 
        $this->body = htmlspecialchars(strip_tags($this->body)); 
        $this->author = htmlspecialchars(strip_tags($this->author)); 
        $this->category_id = htmlspecialchars(strip_tags($this->category_id)); 
        $this->id = htmlspecialchars(strip_tags($this->id)); 

        //Bind data
        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':body', $this->body);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':category_id', $this->category_id);
        $stmt->bindparam(':id', $this->id);

        //Execute query
        if ($stmt->execute()) {
            return true;
        }

        //print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    //Delete post
    public function delete() {
        //Crete query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id= :id ';

        //prepared statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->id = htmlspecialchars(strip_tags($this->id)); 
        
        //Bind data
        $stmt->bindparam(':id', $this->id);

        //Execute query
        if ($stmt->execute()) {
            return true;
        }

        //print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}