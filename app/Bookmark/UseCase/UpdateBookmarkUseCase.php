<?php

namespace App\Bookmark\UseCase;

use App\Http\Requests\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class UpdateBookmarkUseCase
{
    // private LinkPreviewInterface $linkPreview;

    // public function __construct(LinkPreviewInterface $linkPreview)
    // {
    //     $this->linkPreview = $linkPreview;
    // }
    /**
     * Undocumented function
     *
     * @param UpdateBookmarkRequest $request
     * @param integer $id
     * @return void
     */
    public function handle(int $id, string $comment, int $category)
    {
        // dd($id);

        $model = Bookmark::query()->findOrFail($id);

        if ($model->can_not_delete_or_edit) {
            throw ValidationException::withMessages([
                'can_edit' => 'ブックマーク後24時間経過したものは編集できません'
            ]);
        }

        if ($model->user_id !== Auth::id()) {
            abort(403);
        }
        $model->comment = $comment;
        $model->category_id = $category;
        $model->save();
    }
}
