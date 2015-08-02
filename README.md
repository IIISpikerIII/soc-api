# soc-api
API social resources

example

////// VK //////

// if auth false
$vk = new Vk();
$data = $vk->run('photos.search',array('q' => 'cats', 'count' => 5));

// if auth true
$vk = new Vk(
    array(
        'app_id'        => 'XXXXXX',
        'secret_key'    => 'XXXXXXXXXXXXXXXXX',
    )
);
$data = $vk->run('audio.getLyrics',array('lyrics_id' => '2428970'), true);


////// GOOGLE //////

$google = new Google();

//web, images
$data = $google->run('web',array('q' => 'Пингвин'));

