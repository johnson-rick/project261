<?php

namespace PostgreSQL;

class Connection {
    private static $conn;

    public  function get_db() {

    $pdo = null;

        try {

            // default Heroku Postgres configuration URL
            $dbUrl = getenv('DATABASE_URL');

            if (!isset($dbUrl) || empty($dbUrl)) {

                $params = parse_ini_file('dbconfig.ini');
                if ($params === false) {
                    throw new \Exception("Error reading database configuration file");
                }
                // connect to the postgresql database
                $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                        $params['host'],
                        $params['port'],
                        $params['database'],
                        $params['user'],
                        $params['password']);

                $pdo = new \PDO($conStr);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                return $pdo;
            }
            else
            {
                // Get the various parts of the DB Connection from the URL

                $dbopts = parse_url($dbUrl);
                $dbHost = $dbopts["host"];
                $dbPort = $dbopts["port"];
                $dbUser = $dbopts["user"];
                $dbPassword = $dbopts["pass"];
                $dbName = ltrim($dbopts["path"],'/');

                // Create the PDO connection
                $this->$pdo = new \PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

                // this line makes PDO give us an exception when there are problems, and can be very helpful in debugging!
                $this->$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

            }
        }

        catch (PDOException $ex) {
            // If this were in production, you would not want to echo
            // the details of the exception.
            echo "Error connecting to DB. Details: $ex";
            die();
        }

        return $this->$pdo;

    }

    public static function get() {
        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() {

    }

    private function __clone() {

    }

    private function __wakeup() {

    }

}
?>