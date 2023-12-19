<?php

class Page
{
    public $id;
    public $title;
    public $friendly;
    public $description;

    public function __construct($title, $friendly, $description, $id = null) {
        $this->id = $id;
        $this->title = $title;
        $this->friendly = $friendly;
        $this->description = $description;
    }

    public static function all() {
        $list = [];
        $connection = Db::getInstance();
        $result = pg_query($connection, "SELECT * FROM public.pages");
        while ($page = pg_fetch_assoc($result)) {
            $list[] = [
                'id' => $page['id'],
                'title' => $page['title'],
                'friendly' => $page['friendly'],
                'description' => $page['description'],
            ];
        }

        return $list;
    }

    public static function find($id) {
        $connection = Db::getInstance();
        $page = pg_query($connection, "SELECT * FROM public.pages WHERE id = " . $id);
        return pg_fetch_assoc($page) ?: null;
//        return new Page($page['id'], $page['title'], $page['friendly'], $page['description']);
    }

    public static function create($newPage) {
        $connection = Db::getInstance();
        try {
            $page = pg_query($connection, "INSERT INTO public.pages (friendly, title, description) VALUES ('$newPage[friendly]', '$newPage[title]', '$newPage[description]') RETURNING *");
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return pg_fetch_assoc($page);
    }

    public static function update($id, $updatedPage) {
        $connection = Db::getInstance();
        try {
            $page = pg_query($connection, "UPDATE public.pages SET friendly = '$updatedPage[friendly]', title = '$updatedPage[title]', description = '$updatedPage[description]' WHERE id = " . $id . "  RETURNING *");
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return pg_fetch_assoc($page);
    }

    public static function delete($id) {
        $connection = Db::getInstance();
        try {
            pg_query($connection, "DELETE FROM public.pages WHERE id = " . $id);
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            return false;
        }
        return true;
    }
}
