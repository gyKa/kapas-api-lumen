<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Http\Redirector;

class BookmarkController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $bookmarks = DB::table('bookmark')->get();

        foreach ($bookmarks as $bookmark) {
            $bookmark->tags = DB::table('bookmark_tag')
                ->join('tag', 'bookmark_tag.tag_id', '=', 'tag.id')
                ->select('tag.id', 'tag.title')
                ->where('bookmark_tag.bookmark_id', $bookmark->id)
                ->get();

            $bookmark->url = route('track_url', ['id' => $bookmark->id]);
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
            $tagId = DB::table('tag')->where('title', $tag)->value('id');

            if (is_null($tagId)) {
                DB::table('tag')->insert(['title' => $tag]);
            }
        }

        $tags = DB::table('tag')->whereIn('title', $data['tags'])->get();

        $id = DB::table('bookmark')->insertGetId(
            ['title' => $data['title'], 'url' => $data['url']]
        );

        foreach ($tags as $tag) {
            DB::table('bookmark_tag')->insert(
                ['bookmark_id' => $id, 'tag_id' => $tag->id]
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
        $bookmark = DB::table('bookmark')->where('id', $id)->first();

        $tags = DB::table('bookmark_tag')
                    ->join('tag', 'bookmark_tag.tag_id', '=', 'tag.id')
                    ->select('tag.id', 'tag.title')
                    ->where('bookmark_tag.bookmark_id', $bookmark->id)
                    ->get();

        $bookmark->tags = $tags;
        $bookmark->url = route('track_url', ['id' => $bookmark->id]);

        return response()->json($bookmark);
    }

    /**
     * @param int $id
     * @return Redirector
     */
    public function track(int $id)
    {
        $url = DB::table('bookmark')->where('id', $id)->value('url');

        DB::table('visit')->insert(['bookmark_id' => $id]);

        return redirect($url);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $data = json_decode($request->getContent(), true);

        DB::table('bookmark')
            ->where('id', $id)
            ->update(['title' => $data['title'], 'url' => $data['url'], 'updated_at' => new \DateTime()]);

        return response()->json([], 204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        DB::table('bookmark')->where('id', '=', $id)->delete();

        return response()->json([], 204);
    }
}
