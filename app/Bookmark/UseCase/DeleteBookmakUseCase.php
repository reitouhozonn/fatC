<?php

namespace App\Bookmark\UseCase;

use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class DeleteBookmakUseCase
{
    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public function handle(int $id)
    {
        $model = Bookmark::query()->findOrFail($id);

        if ($model->can_not_delete_or_edit) {
            throw ValidationException::withMessages([
                'can_delete' => 'ブックマーク後24時間経過したものは削除できません'
            ]);
        }

        if ($model->user_id !== Auth::id()) {
            abort(403);
        }

        $model->delete();
    }
}
