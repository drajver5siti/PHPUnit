<?php

use App\Article;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    private Article $a;

    protected function setUp(): void
    {
        $this->a = new Article;
    }

    public function testNewArticleHasBlankTitle()
    {
        $this->assertEmpty($this->a->title);
    }

    public static function titleProvider()
    {
        return [
            'Label1' => ["Hello World", "hello_world"],
            'Label2' => ["Hello  \n  World\n", "hello_world"],
            'Label3' => ["Hello!   World", "hello_world"],
            'Label4' => ["Hello! \n World", "hello_world"],
            'Label5' => ['', ''],
        ];
    }

    #[DataProvider('titleProvider')]
    public function testSlug(string $title, string $slug)
    {
        $this->a->title = $title;
        $this->assertEquals($slug, $this->a->getSlug());
    }
}