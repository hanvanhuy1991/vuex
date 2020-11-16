<?php

namespace App\Http\Resources;

use App\Enums\FavoriteEnum;
use App\Enums\UniEnum;
use Illuminate\Support\Facades\Auth;

class UniBookResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $limit = $request->limit ?? UniEnum::LIMIT;
        $bookmarks = $this->bookmarks()
            ->whereUserId(Auth::id())
            ->whereIsFavorited(FavoriteEnum::IS_FAVORITE)
            ->with('post')
            ->has('post')
            ->limit($limit)
            ->get();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'unis' => $this->bookmarks_count,
            'posts' => ImageUniBookResource::collection($bookmarks)
        ];
    }
}
