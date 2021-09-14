<?php

namespace Tests\Feature\Bookmarks;

use App\Bookmark\UseCase\CreateBookmarkUseCase;
use App\Lib\LinkPreview\LinkPreview;
use App\Lib\LinkPreview\LinkPreviewInterface;
use App\Lib\LinkPreview\MockLinkPreview;
use App\Models\BookmarkCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CreateBookmarkUseCaseTest extends TestCase
{
    private CreateBookmarkUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = new CreateBookmarkUseCase(new LinkPreview());
    }

    public function testSaveCorrectData()
    {
        $url = 'https://notfound.example.com/';
        $category = BookmarkCategory::query()->first()->id;
        $comment = 'テスト用コメント';

        // 強制ログイン
        $testUser = User::query()->first();
        Auth::loginUsingId($testUser->id);

        $this->useCase->handle($url, $category, $comment);

        Auth::logout();

        $this->assertDatabaseHas('bookmarks', [
            'url' => $url,
            'category_id' => $category,
            'user_id' => $testUser->id,
            'comment' => $comment,
            'page_title' => 'モックのタイトル',
            'page_description' => 'モックのdescription',
            'page_thumbnail_url' => 'https://i.gyazo.com/634f77ea66b5e522e7afb9f1d1dd75cb.png',
        ]);
    }
}
