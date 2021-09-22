<?php

namespace Tests\Feature\Bookmarks;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteBookmarkTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
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
