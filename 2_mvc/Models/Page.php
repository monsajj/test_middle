<?php

class Page
{
    CONST TABLE = 'pages';
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
        return DbOrm::all(Page::TABLE);
    }

    public static function find($id) {
        return DbOrm::find(Page::TABLE, $id);
    }

    public static function create($data) {
        return DbOrm::create(Page::TABLE, $data);
    }

    public static function update($id, $data) {
        return DbOrm::update(Page::TABLE, $id, $data);
    }

    public static function delete($id) {
        return DbOrm::delete(Page::TABLE, $id);
    }
}
