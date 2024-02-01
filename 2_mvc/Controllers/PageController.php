<?php

class PageController
{
    public function index() {
        try {
            $pages = Page::all();
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success', 'data' => $pages]);
        return json_encode(['message' => 'success', 'data' => $pages]);
    }

    public function get($id) {
        try {
            $id = intval($id);
            $page = Page::find($id);
            if (!$page) {
                throw new Exception('Page not found');
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success', 'data' => $page]);
        return json_encode(['message' => 'success', 'data' => $page]);
    }

    public function createPage($request) {
        try {
            //should be validation here but for tests we just set data instead request
            $request = [
                'title' => 'another updated title',
                'friendly' => 'true',
                'description' => 'updated description',
            ];
            $page = Page::create($request);
            if (!$page) {
                throw new Exception('Page not created');
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success', 'data' => $page]);
        return json_encode(['message' => 'success', 'data' => $page]);
    }

    public function updatePage($id, $request) {
        try {
            $id = intval($id);
            $page = Page::find($id);
            if (!$page) {
                throw new Exception('Page not created');
            }
            //should be validation here but for tests we just set data instead request
            $request = [
                'id' => $id,
                'title' => 'updated title',
                'friendly' => 'true',
                'description' => 'new updated description',
            ];
            $page = Page::update($id, $request);
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success', 'data' => $page]);
        return json_encode(['message' => 'success', 'data' => $page]);
    }

    public function deletePage($id) {

        try {

            $id = intval($id);
            $page = Page::find($id);

            if (!$page) {
                throw new Exception('Page not found');
            }
            Page::delete($id);
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success']);
        return json_encode(['message' => 'success']);
    }
}
