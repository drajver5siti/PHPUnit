<?php

use App\Queue;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    private Queue $q;

    protected function setUp(): void
    {
        $this->q = new Queue();
    }

    public static function setUpBeforeClass(): void
    {

    }

    public function testNewQueueIsEmpty()
    {
        $this->assertEquals(0, $this->q->getCount());
    }

    public function testItemAddedToQueue()
    {
        $this->q->push("test");
        $this->assertEquals(1, $this->q->getCount());
    }

    public function testItemRemovedFromQueue()
        {
        $this->q->push("hello_world");
        $item = $this->q->pop();

        $this->assertEquals(0, $this->q->getCount());
        $this->assertEquals("hello_world", $item);
    }

    public function testOrderOfRemoval()
    {
        $this->q->push("first");
        $this->q->push("second");

        $item = $this->q->pop();

        $this->assertEquals("first", $item);
    }

    public function testMaxItemsExceeded()
    {
        for ($i = 0; $i < Queue::MAX_ITEMS; $i++) {
            $this->q->push($i);
        }

        $this->assertEquals(Queue::MAX_ITEMS, $this->q->getCount());

        $this->expectExceptionObject(new Exception("MAX_ITEMS"));
        $this->q->push('more');
    }
}