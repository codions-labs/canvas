<?php

namespace Canvas\Tests\Listeners;

use Canvas\Listeners\CaptureView;
use Canvas\Models\Post;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaptureViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The listener instance.
     *
     * @var CaptureView
     */
    protected $instance;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new CaptureView();
    }

    /** @test */
    public function check_if_post_was_recently_viewed()
    {
        $post = factory(Post::class)->create();

        $this->invokeMethod($this->instance, 'storeInSession', [$post]);

        $response = $this->invokeMethod($this->instance, 'wasRecentlyViewed', [$post]);

        $this->assertTrue($response);
        $this->assertArrayHasKey($post->id, session()->get('viewed_posts'));

        session()->flush();

        $response = $this->invokeMethod($this->instance, 'wasRecentlyViewed', [$post]);

        $this->assertFalse($response);
    }

    /** @test */
    public function store_post_in_session()
    {
        $post = factory(Post::class)->create();

        $this->invokeMethod($this->instance, 'storeInSession', [$post]);

        $this->assertArrayHasKey($post->id, session()->get('viewed_posts'));
    }
}
