<?php

/**
 * Connect Database
 */
class Database
{
    /**
     * hostname
     * username
     * password
     * 
     * database
     *
     * @var string
     */
    private $hostname = '127.0.0.1'; //Change here
    private $username = 'root'; //Change here
    private $password = ''; //Change here
    private $database = 'trainingphp'; //Change here

    private $conn = null;
    private $result = null;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connect failed:" . $this->conn->connect_error);
        } else {
            mysqli_set_charset($this->conn, 'utf8');
        }
        return $this->conn;
    }
    /**
     * execute
     *
     * @param  mixed $sql
     * @return void
     */
    public function execute($sql)
    {
        $this->result = $this->conn->query($sql);
        return $this->result;
    }
    /**
     * num_rows
     *
     * @return void
     */
    public function num_rows()
    {
        if ($this->result) {
            $num = mysqli_num_rows($this->result);
        } else {
            $num = 0;
        }
        return $num;
    }
}
