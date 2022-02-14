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
use PHPUnit\Framework\TestCase;
use Cartalyst\Collections\Collection;
use Traversable;

class CollectionTest extends TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function a_collection_can_be_instantiated()
    {
        $collection = new Collection;

        $collection = Collection::make();

        $this->assertInstanceOf(Collection::class, $collection);
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
    public function it_can_return_only_select_keys_from_a_collection()
    {
        $collection = new Collection([
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false
        ]);

        $filtered = $collection->only(['product_id', 'name']);

        $this->assertEquals(['product_id' => 1, 'name' => 'Desk'], $filtered->all());
    }

    /** @test */
    public function it_can_all_select_keys_from_a_collection_with_exception()
    {
        $collection = new Collection([
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false
        ]);

        $filtered = $collection->except(['price', 'discount']);

        $this->assertEquals(['product_id' => 1, 'name' => 'Desk'], $filtered->all());
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

        $output = $collection->sortByDesc('name')->all();

        $this->assertEquals($expected, $output);
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

        $this->assertInstanceOf('Traversable', $collection);
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
    public function it_can_sum_by_key_on_a_collection()
    {
        $collection = new Collection([
            ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
            ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
        ]);

        $this->assertEquals(1272, $collection->sum('pages'));
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
    public function it_can_split_a_collection_into_chunks()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7]);

        $this->assertEquals([[1, 2, 3, 4], [5, 6, 7]], $collection->chunk(4)->toArray());
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

    /** @test */
    public function it_can_iterate_over_a_collection_using_a_function()
    {
        $collection = new Collection([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ]);

        $collection->each(function ($v, $k) {
            $this->assertNotEmpty($v);
            $this->assertNotEmpty($k);
        });
    }

    /** @test */
    public function it_can_iterate_over_every_other_item_in_a_collection()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f']);

        $output = $collection->every(4);

        $this->assertEquals(['a', 'e'], $output->all());

        $offset = $collection->every(4, 1);

        $this->assertEquals(['b', 'f'], $offset->all());
    }

    /** @test */
    public function it_can_flip_a_collection()
    {
        $collection = new Collection(['oranges', 'apples', 'pears']);

        $flipped = $collection->flip();

        $expected = [
            'oranges' => 0,
            'apples' => 1,
            'pears' => 2,
        ];

        $this->assertEquals($expected, $flipped->all());
    }

    /** @test */
    public function it_can_reduce_a_collection_using_a_function()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $sum = $collection->reduce(function ($carry, $item) {
            $carry += $item;
            return $carry;
        });

        $this->assertEquals(15, $sum);

        $product = $collection->reduce(function ($carry, $item) {
            $carry *= $item;
            return $carry;
        }, 10);

        $this->assertEquals(1200, $product);
    }

    /** @test */
    public function it_can_reverse_a_collection()
    {
        $collection = new Collection(['php', 4.0, ['green', 'red']]);

        $reversed = $collection->reverse();

        $expected = [
            0 => 'php',
            1 => 4.0,
            2 => [
                0 => 'green',
                1 => 'red',
            ],
        ];

        $this->assertEquals($expected, $reversed->all());

        $reversed2 = $collection->reverse(false);

        $expected = [
            0 => [
                0 => 'green',
                1 => 'red',
            ],
            1 => 4.0,
            2 => 'php',
        ];

        $this->assertEquals($expected, $reversed2->all());
    }

    /** @test */
    public function it_can_filter_a_collection_using_a_function()
    {
        $collection = new Collection([
            [ 'id' => 'foo' ],
            [ 'id' => 'bar' ],
        ]);

        $filtered = $collection->filter(function ($item) {
            return $item['id'] === 'foo';
        });

        $this->assertCount(1, $filtered);
    }

    /** @test */
    public function it_can_reject_filter_a_collection()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $filtered = $collection->reject(function ($value, $key) {
            return $value > 2;
        });

        $this->assertEquals([1, 2], $filtered->all());

        $filtered2 = $collection->reject(3);

        $expected = [
            0 => 1,
            1 => 2,
            3 => 4,
        ];

        $this->assertEquals($expected, $filtered2->all());
    }

    /** @test */
    public function it_can_map_a_collection_using_a_function()
    {
        $collection = new Collection([
            [ 'id' => 'foo' ],
            [ 'id' => 'bar' ],
        ]);

        $mapped = $collection->map(function ($item) {
            $item['id'] .= 'baz';
            return $item;
        });

        $expected = [
            [ 'id' => 'foobaz' ],
            [ 'id' => 'barbaz' ],
        ];

        $this->assertEquals($expected, $mapped->all());
    }

    /** @test */
    public function it_can_slice_a_collection()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e']);

        $sliced1 = $collection->slice(2);

        $this->assertEquals(['c', 'd', 'e'], $sliced1->all());

        $sliced2 = $collection->slice(-2, 1);

        $this->assertEquals(['d'], $sliced2->all());

        $sliced3 = $collection->slice(2, -1, true);

        $expected = [
            2 => 'c',
            3 => 'd'
        ];

        $this->assertEquals($expected, $sliced3->all());
    }
}
