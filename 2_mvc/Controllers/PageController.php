<?php

class PageController
{
    public function index() {
        $pages = Page::all();
        //for tests instead return data to view
        foreach ($pages as $page) {
            var_dump($page);
            echo '<br>';
        }
        return true;
    }

    public function getPage($id) {
        $id = intval($id);
        $page = Page::find($id);
        if ($page)
            var_dump($page);
        else
            echo "404 Page not found";
        return true;
    }

    public function createPage($request) {
        //should be validation here but for tests we just set data instead request
        $request = [
            'title' => 'another updated title',
            'friendly' => 'true',
            'description' => 'updated description',
        ];
        $page = Page::create($request);
        if ($page)
            var_dump($page);
        else
            echo "404 Page not found";
        return true;
    }

    public function updatePage($id, $request) {
        $id = intval($id);
        $page = Page::find($id);
        if ($page) {
            //should be validation here but for tests we just set data instead request
            $request = [
                'id' => $id,
                'title' => 'updated title',
                'friendly' => 'true',
                'description' => 'new updated description',
            ];
            $page = Page::update($id, $request);
            var_dump($page);
        } else
            echo "404 Page not found";
        return true;
    }

    public function deletePage($id) {
        $id = intval($id);
        $page = Page::find($id);
        if ($page)
            if (Page::delete($id))
                echo 'success';
            else
                echo 'error';
        else
            echo "404 Page not found";
        return true;
    }
}
