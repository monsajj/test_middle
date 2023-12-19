<?php
require_once 'Db.php';
require_once 'Models\Page.php';

class PageTest Extends \PHPUnit\Framework\TestCase
{
    public function testCreate() {
        $connection = \Db::getInstance();
        $page = new Page('phpunit title', true, 'phpunit description');
        $result = pg_query($connection, "INSERT INTO public.pages (friendly, title, description) VALUES ('$page->friendly', '$page->title', '$page->description') RETURNING *");
        $createdPage = pg_fetch_assoc($result) ?: null;
        $this->assertNotNull($createdPage['id']);
    }

    public function testFind() {
        $id = 1;
        $connection = \Db::getInstance();
        $page = pg_query($connection, "SELECT * FROM public.pages WHERE id = " . $id);
        $pageData = pg_fetch_assoc($page) ?: null;
        $this->assertEquals($id, $pageData['id']);
    }

    public function testUpdate() {
        $id = 1;
        $connection = \Db::getInstance();
        $updatedPage = [
            'title' => 'title',
            'friendly' => 't',
            'description' => 'description',
        ];
        //сначала получаем текущие данные, 100% меняем их и обновленные данные записываем в таблицу, в конце сверям с теми данными на которые меняли
        $actualPage = pg_query($connection, "SELECT * FROM public.pages WHERE id = " . $id);
        $actualPageData = pg_fetch_assoc($actualPage) ?: null;
        if ($actualPageData['title'] == $updatedPage['title'])
            $updatedPage['title'] = $updatedPage['title'] . '+1';
        if ($actualPageData['friendly'] === $updatedPage['friendly'])
            $updatedPage['friendly'] = $actualPageData['friendly'] === 't' ? 'f' : 't';
        if ($actualPageData['description'] == $updatedPage['description'])
            $updatedPage['description'] = $updatedPage['description'] . '+1';
        $page = pg_query($connection, "UPDATE public.pages SET friendly = '$updatedPage[friendly]', title = '$updatedPage[title]', description = '$updatedPage[description]' WHERE id = " . $id . "  RETURNING *");
        $newPageData = pg_fetch_assoc($page) ?: null;
        $this->assertEquals($id, $newPageData['id']);
        $this->assertEquals($updatedPage['title'], $newPageData['title']);
        $this->assertEquals($updatedPage['friendly'], $newPageData['friendly']);
        $this->assertEquals($updatedPage['description'], $newPageData['description']);
    }

    public function testDelete() {
        $connection = \Db::getInstance();
        $page = pg_query($connection, "SELECT * FROM public.pages ORDER BY id DESC LIMIT 1");
        $pageData = pg_fetch_assoc($page) ?: null;
        $id = $pageData['id'];
        pg_query($connection, "DELETE FROM public.pages WHERE id = " . $id);
        $deletedPage = pg_query($connection, "SELECT * FROM public.pages WHERE id = " . $id);
        $deletedPageData = pg_fetch_assoc($deletedPage) ?: null;
        $this->assertNull($deletedPageData);
    }
}