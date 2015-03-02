<?php

/**
 * Part of the Collections package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Collections
 * @version    1.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Collections\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Collections\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function a_collection_can_be_instantiated()
    {
        $collection = new Collection;

        $collection = Collection::make();
    }

    /** @test */
    public function it_can_get_all_the_items_from_the_collection()
    {
        $collection = new Collection;
        $this->assertEmpty($collection->all());

        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertEquals([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ], $collection->all());
    }

    /** @test */
    public function it_can_get_the_total_items_from_the_collection()
    {
        $collection = new Collection;
        $this->assertCount(0, $collection);

        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertCount(2, $collection);
    }

    /** @test */
    public function it_can_check_if_the_collection_has_an_item()
    {
        $collection = new Collection;
        $this->assertFalse($collection->has('foo'));

        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertTrue($collection->has('foo'));
    }

    /** @test */
    public function it_can_find_an_item_from_the_collection()
    {
        $collection = new Collection;
        $this->assertNull($collection->foo);

        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertEquals('Foo', $collection->foo);
    }

    /** @test */
    public function it_can_return_the_first_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $this->assertEquals('Foo', $collection->first());
    }

    /** @test */
    public function it_can_remove_an_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertCount(2, $collection);
        $collection->forget('bar');
        $this->assertCount(1, $collection);
    }

    /** @test */
    public function it_can_check_that_a_collection_is_empty()
    {
        $collection = new Collection;

        $this->assertTrue($collection->isEmpty());
    }

    /** @test */
    public function it_can_check_that_a_collection_is_not_empty()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $this->assertFalse($collection->isEmpty());
    }

    /** @test */
    public function it_can_return_the_last_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $this->assertEquals('Bar', $collection->last());
    }

    /** @test */
    public function it_can_remove_the_last_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz',
        ]);
        $this->assertCount(3, $collection);
        $this->assertEquals('Baz', $collection->last());
        $collection->pop();
        $this->assertCount(2, $collection);
        $this->assertEquals('Bar', $collection->last());
    }

    /** @test */
    public function it_can_push_an_item_to_the_end_of_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);
        $this->assertCount(2, $collection);
        $this->assertEquals('Bar', $collection->last());
        $collection->push('Baz');
        $this->assertCount(3, $collection);
        $this->assertEquals('Baz', $collection->last());
    }

    /** @test */
    public function it_can_get_and_remove_the_first_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz',
            'bat' => 'Bat',
        ]);

        $this->assertCount(4, $collection);

        $value = $collection->shift();
        $this->assertEquals('Foo', $value);

        $this->assertCount(3, $collection);
    }

    /** @test */
    public function it_can_retrieve_the_collection_items_as_an_array()
    {
        $collection = new Collection([
            'foo' => 'Foo',
        ]);
        $this->assertEquals(['foo' => 'Foo'], $collection->toArray());
    }

    /** @test */
    public function it_can_pull_an_item_from_the_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz',
            'bat' => 'Bat',
        ]);

        $this->assertCount(4, $collection);

        $collection->pull('bar');

        $this->assertCount(3, $collection);
    }

    /** @test */
    public function it_can_test_the_offset_methods()
    {
        $collection = new Collection;
        $collection['name'] = 'Foo';
        $this->assertTrue(isset($collection['name']));
        $this->assertEquals('Foo', $collection['name']);
        unset($collection['name']);
        $this->assertFalse(isset($collection['name']));


        $collection = new Collection;
        $collection->name = 'Foo';
        $this->assertTrue(isset($collection->name));
        unset($collection->name);
        $this->assertFalse(isset($collection->name));
    }

    /** @test */
    public function it_can_sort_the_collection_items()
    {
        $collection = new Collection;
        $collection->put('foo', ['name' => 'Foo']);
        $collection->put('bar', ['name' => 'Bar']);
        $collection->put('baz', ['name' => 'Baz']);

        $collection->sort(function ($item) {
            return $item;
        });

        $this->assertEquals([
            'foo' => [
                'name' => 'Foo',
            ],
            'bar' => [
                'name' => 'Bar',
            ],
            'baz' => [
                'name' => 'Baz',
            ],
        ], $collection->all());

        $this->assertEquals([
            'bar' => [
                'name' => 'Bar',
            ],
            'baz' => [
                'name' => 'Baz',
            ],
            'foo' => [
                'name' => 'Foo',
            ],
        ], $collection->sortBy('name')->all());

        $expected = [
            'foo' => [
                'name' => 'Foo',
            ],
            'baz' => [
                'name' => 'Baz',
            ],
            'bar' => [
                'name' => 'Bar',
            ],
        ];

        $output = $collection->sortByDesc('id')->all();

        $this->assertTrue($expected === $output);
    }

    /** @test */
    public function it_can_serialize_a_collection()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $this->assertEquals('{"foo":"Foo","bar":"Bar"}', json_encode($collection));
    }

    /** @test */
    public function it_can_get_the_items_as_a_json_object()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $this->assertEquals('{"foo":"Foo","bar":"Bar"}', $collection->toJson());
    }

    /** @test */
    public function it_can_be_iterable()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz',
            'bat' => 'Bat',
        ]);

        foreach ($collection as $item) {
        };
    }

    /** @test */
    public function it_can_sum_a_collection()
    {
        $collection = new Collection([
            2,
            3,
        ]);

        $this->assertEquals(5, $collection->sum());
    }

    /** @test */
    public function it_can_sum_a_collection_by_method_calls()
    {
        $item1 = m::mock('stdClass');
        $item2 = m::mock('stdClass');

        $item1->shouldReceive('getValue')
            ->once()
            ->andReturn(2);

        $item2->shouldReceive('getValue')
            ->once()
            ->andReturn(3);

        $collection = new Collection([
            $item1,
            $item2,
        ]);

        $this->assertEquals(5, $collection->sum('getValue'));
    }

    /** @test */
    public function it_can_list_items_from_the_collection_using_a_key()
    {
        $collection = new Collection([
            [ 'id' => 'foo' ],
            [ 'id' => 'bar' ],
        ]);

        $this->assertEquals([ 'foo', 'bar' ], $collection->lists('id'));
    }
}
