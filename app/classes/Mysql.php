<?php


namespace Task;


class Mysql
{
    protected static $i;
    protected $host;
    /** @var string */
    protected $port;
    /** @var string */
    protected $db;
    /** @var string */
    protected $user;
    /** @var string */
    protected $pass;
    /** @var \PDO */
    protected $mysql_handler = null;

    /**
     * @param string $host
     * @param string $port
     * @param string $db
     * @param string $user
     * @param string $pass
     */
    protected function __construct($host, $port, $db, $user, $pass)
    {
        $this->host = $host;
        $this->port = $port ?: 3306;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * @param string $host
     * @param string $port
     * @param string $db
     * @param string $user
     * @param string $pass
     * @return static
     */
    public static function init($host, $port, $db, $user, $pass)
    {
        $ob = new static($host, $port, $db, $user, $pass);

        static::$i = $ob;

        return $ob;
    }

    /**
     * @return \Task\Mysql
     */
    public static function get()
    {
        if (empty(static::$i)) {
            throw new \RuntimeException("db connection not initialized");
        }
        return static::$i;
    }


    public function connect()
    {
        try {
            $this->mysql_handler = new \PDO(
                "mysql:dbname={$this->db};host={$this->host}",
                $this->user,
                $this->pass
            );

        } catch (\PDOException $e) {

            die("DB connection error");

        }
    }

    public function up()
    {
        $this->query(
            MysqlQueries::initDump()
        );
    }

    public function exists($q, $params)
    {
        return count($this->query($q, $params)) > 0;

    }

    /**
     * @param string $q
     * @param array $params
     * @return array
     */
    public function query($q, $params = [])
    {
        /** @var \PDOStatement $mysqli_stmt */
        $mysqli_stmt = $this->mysql_handler->prepare($q);

        $res = $mysqli_stmt->execute($params);

        if (false === $res) {
            return [];
        }

        $rows = $mysqli_stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($mysqli_stmt->rowCount() === 0) {
            return [];
        }
        if ($mysqli_stmt->rowCount() === 1) {
            return $rows;
        }

        return $rows;
    }
}