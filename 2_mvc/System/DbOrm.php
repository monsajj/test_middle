<?php

class DbOrm
{
    public static function all($table) {
        $connection = Db::getInstance();
        $result = pg_query($connection, "SELECT * FROM public.{$table}");
        $response = [];
        while ($data = pg_fetch_assoc($result)) {
            $response[] = $data;
        }
        return $response;
    }

    public static function find($table, $id) {
        $connection = Db::getInstance();
        $data = pg_query_params($connection, "SELECT * FROM public.{$table} WHERE id = $1", [$id]);
        return pg_fetch_assoc($data) ?: null;
    }

    public static function create($table, $data) {
        $connection = Db::getInstance();
        $params = '';
        $values = '';
        foreach (array_keys($data) as $key => $param) {
            $i = $key+1;
            $params .= $key == 0 ? $param : ", {$param}";
            $values .= $key == 0 ? "\${$i}" : ", \${$i}";
        }
        try {
            $page = pg_query_params($connection, "INSERT INTO public.{$table} ({$params}) VALUES ({$values}) RETURNING *", array_values($data));
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return pg_fetch_assoc($page);
    }

    public static function update($table, $id, $data) {
        $connection = Db::getInstance();
        $params = '';
        $newData = [$id];
        $i = 2;
        foreach (array_keys($data) as $param) {
            if ($param == 'id')
                continue;
            $params .= "{$param} = \${$i}, ";
            $newData[] = $data[$param];
            $i++;
        }
        $params = rtrim($params, ', ');
        try {
            $page = pg_query_params($connection, "UPDATE public.{$table} SET {$params} WHERE id = $1 RETURNING *", $newData);
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return pg_fetch_assoc($page);
    }

    public static function delete($table, $id) {
        $connection = Db::getInstance();
        try {
            pg_query_params($connection, "DELETE FROM public.{$table} WHERE id = $1", [$id]);
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return true;
    }
}