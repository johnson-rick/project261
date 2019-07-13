<?php
namespace SQLData;

use PostgreSQL\Connection as Connection;

class Common
{
    function add_data($data, $obj)
    {
        $success = false;
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->get_db();
            $repoDB = new RepoDB($pdo);
            $success = $repoDB->$data($pdo, $obj);
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return $success;
    }

    function get_data($data)
    {
        $arr = [];
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->get_db();
            //
            $repoDB = new RepoDB($pdo);
            $arr = $repoDB->$data($pdo);
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return $arr;
    }

    function get_data_by_id($data, $id)
    {
        $arr = [];
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->get_db();
            //
            $repoDB = new RepoDB($pdo);
            $arr = $repoDB->$data($pdo, $id);
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return $arr;
    }

}
?>