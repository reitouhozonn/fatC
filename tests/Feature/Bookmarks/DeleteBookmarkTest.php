<?php

namespace Tests\Feature\Bookmarks;

use App\Bookmark\UseCase\CreateBookmarkUseCase;
use App\Http\Middleware\VerifyCsrfToken;
use App\Lib\LinkPreview\LinkPreviewInterface;
use App\Lib\LinkPreview\MockLinkPreview;
use App\Models\Bookmark;
use App\Models\User;
use App\Models\BookmarkCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteBookmarkTest extends TestCase
{
    private CreateBookmarkUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->app->bind(LinkPreviewInterface::class, MockLinkPreview::class);
        $this->useCase = $this->app->make(CreateBookmarkUseCase::class);
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


        $this->assertDatabaseHas('bookmarks', [
            'url' => $url,
            'category_id' => $category,
            'user_id' => $testUser->id,
            'comment' => $comment,
            'page_title' => 'モックのタイトル',
            'page_description' => 'モックのdescription',
            'page_thumbnail_url' => 'https://i.gyazo.com/634f77ea66b5e522e7afb9f1d1dd75cb.png',
        ]);

        $model = Bookmark::query()->orderBy('id', 'desc')->first();

        $model->delete();

        $this->assertDatabaseMissing('bookmarks', [
            'url' => $url,
            'category_id' => $category,
            'user_id' => $testUser->id,
            'comment' => $comment,
            'page_title' => 'モックのタイトル',
            'page_description' => 'モックのdescription',
            'page_thumbnail_url' => 'https://i.gyazo.com/634f77ea66b5e522e7afb9f1d1dd75cb.png',
        ]);

        Auth::logout();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testFailedWhenLogoutUser()
    {
        $this->put('/bookmarks/1', [
            'comment' => 'ブックマークのテスト用コメント',
            'category' => 1,
        ])->assertRedirect('/login');
    }
    /**
     * ログインはしているが他人による実行
     *
     * →ステータス403で失敗
     */
    public function testFailedWhenOtherUser()
    {
        $user = User::query()->find(2);

        $this->actingAs($user)->put('/bookmarks/1', [
            'comment' => 'ブックマークのテスト用コメント',
            'category' => 1,
        ])->assertForbidden();
    }
}
