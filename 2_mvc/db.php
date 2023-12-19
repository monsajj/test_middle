<?php
const CONNECTION_STRING = 'postgres://zgiiukajgribvr:f4fd9d660b0090fc65f32e3e7426f6565d0d3230c5c6c14f4d9203db8092f879@ec2-18-202-8-133.eu-west-1.compute.amazonaws.com:5432/d4hkctuv0449vc';
class Db
{
    public static function getInstance() {
        $connection = pg_connect(CONNECTION_STRING);
        if (!$connection) {
            echo "<br> DB connection error occurred.<br>";
        }
        return $connection;
    }
}
