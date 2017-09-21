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
        $bookmarks = app('db')->select(
            'select * from bookmark'
        );

        foreach ($bookmarks as $bookmark) {
            $tags = app('db')->select('select tag.id, tag.title from bookmark_tag inner join tag on bookmark_tag.tag_id = tag.id where bookmark_id = ?', [$bookmark->id]);
            $bookmark->tags = $tags;
        }

        return response()->json($bookmarks);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data['tags'] as $tag) {
            $tagId = app('db')->select('select id from tag where title = ?', [$tag]);

            if (count($tagId) === 0) {
                app('db')->insert(
                    'insert into tag (title, created_at) values (?, ?)',
                    [$tag, new \DateTime()]
                );
            }
        }

        $tags = "'" . join("','", $data['tags']) . "'";

        $tagIds = app('db')->select(
            "select id from tag where title IN ($tags)"
        );

        app('db')->insert(
            'insert into bookmark (title, url, created_at) values (?, ?, ?)',
            [$data['title'], $data['url'], new \DateTime()]
        );

        $id = app('db')->select('SELECT LAST_INSERT_ID() as id');

        foreach ($tagIds as $tagId) {
            app('db')->insert(
                'insert into bookmark_tag (bookmark_id, tag_id) values (?, ?)',
                [$id[0]->id, $tagId->id]
            );
        }

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
            [$data['title'], $data['url'], new \DateTime(), $id]
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
