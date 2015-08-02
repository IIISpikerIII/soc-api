# soc-api
API social resources

Used API: VK, GOOGLE, INSTAGRAM, FLICKR

VK
----

if auth false
```php
$vk = new Vk();
$data = $vk->run('photos.search',array('q' => 'cats', 'count' => 5));
```
if auth true
```php
$vk = new Vk(
    array(
        'redirect_url'  => 'XXXXXXXXXXXX',
        'app_id'        => 'XXXXXX',
        'secret_key'    => 'XXXXXXXXXXXXXXXXX',
    )
);
$data = $vk->run('audio.getLyrics',array('lyrics_id' => '2428970'), true);
```

GOOGLE
----

```php
$google = new Google();
```

web, images
```php
$data = $google->run('web',array('q' => 'Пингвин'));
```
