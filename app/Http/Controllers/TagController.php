<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $tags = app('db')->select('select * from tag');

        return response()->json($tags);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        app('db')->insert(
            'insert into tag (title, created_at) values (?, ?)',
            [$data['title'], new \DateTime()]
        );

        return response()->json([], 201);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $tag = app('db')->select(
            'select * from tag where id = ? LIMIT 1',
            [$id]
        );

        return response()->json($tag);
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
            'update tag (title, updated_at) values (?, ?) where id = ?',
            [$data['title'], new \DateTime(), $id]
        );

        return response()->json([], 204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        app('db')->delete('delete from tag where id = ?', [$id]);

        return response()->json([], 204);
    }
}
