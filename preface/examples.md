### Examples

###### Instantiate a new Collection

```php
$collection = new Cartalyst\Collections\Collection([
	'item1' => 'Item 1',
	'item2' => 'Item 2',
]);
```

###### Get how many items are inside a Collection

```php
echo $collection->count();
// > 2
```

###### Retrieve an item inside of the Collection

```php
echo $collection->item1;
// Item 1
```
