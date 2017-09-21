<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $bookmarks = app('db')->select("SELECT * FROM bookmark");

        return response()->json($bookmarks);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        app('db')->insert(
            'insert into bookmark (title, url, created_at) values (?, ?, ?)',
            [$data['title'], $data['url'], new \DateTime()]
        );

        return response()->json([], 201);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $bookmark = app('db')->select(
            "SELECT * FROM bookmark WHERE id = ? LIMIT 1",
            [$id]
        );

        return response()->json($bookmark);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $data = json_decode($request->getContent(), true);

        app('db')->update(
            'update bookmark (title, url, updated_at) values (?, ?, ?) where id = ?',
            [$id, $data['title'], $data['url'], new \DateTime()]
        );

        return response()->json([], 204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        app('db')->delete('delete from bookmark where id = ?', [$id]);

        return response()->json([], 204);
    }
}
