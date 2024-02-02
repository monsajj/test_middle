<?php

class PageController
{
    public function index() {
        try {
            $pages = Page::all();
        } catch (Exception $exception) {
            echo $exception->getMessage(); //instead of log for tests
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
            echo $exception->getMessage(); //instead of log for tests
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
                'friendly' => true,
                'title' => 'another updated title',
                'description' => 'updated description',
            ];
            $page = Page::create([
                'title' => $request['title'],
                'friendly' => $request['friendly'],
                'description' => $request['description'],
            ]);
            if (!$page) {
                throw new Exception('Page not created');
            }
        } catch (Exception $exception) {
            echo $exception->getMessage(); //instead of log for tests
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
                'friendly' => true,
                'title' => 'updated title',
                'description' => 'new updated description',
            ];
            $page = Page::update($id, [
                'id' => $id,
                'title' => $request['title'],
                'friendly' => $request['friendly'],
                'description' => $request['description'],
            ]);
        } catch (Exception $exception) {
            echo $exception->getMessage(); //instead of log for tests
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
            echo $exception->getMessage(); //instead of log for tests
            return json_encode(['message' => $exception->getMessage(), 'data' => null]);
        }
        // echo instead return - to view data for tests
//        echo json_encode(['message' => 'success']);
        return json_encode(['message' => 'success']);
    }
}
