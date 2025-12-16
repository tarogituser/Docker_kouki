<?php
    $host = 'mysql';
    // mysql接続用のユーザー
    $username = 'data_user';
    $password = 'data';
    $database = 'data_master';

    #キャッシュ接続情報
    $memcachedHost = 'memcached';
    $memcachedPort = 11211;

    $cacheKey = 'all_students_data';
    #キャッシュ有効秒数
    $expireSeconds = 60;

    #Memcachedに接続
    $memcached = new Memcached();
    $memcached->addServer($memcachedHost, $memcachedPort);

    #Memcachedからデータ取得
    echo "<h1>MySQL Data Caching with Memcached</h1>";

    $cachedData = $memcached->get($cacheKey);

    if ($cachedData){
        echo "<p>Data retrieved from Memcached (cache hit!).</p>";
        echo "<pre>";
        print_r(json_decode($cachedData, true));
        echo "</pre>";
    }
    else{
        echo "<p>Data not found in Memcached (cache miss). Retrieving from MySQL...</p>";
    }

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sdmt = $pdo->query("SELECT * FROM students");
        $results = $sdmt->fetchAll(PDO::FETCH_ASSOC);

        //Memcachedに保存
        $memcached->set($cacheKey, json_encode($results), $expireSeconds);
        
        echo "<p>Data retrieved from MySQL and saved to Memcached.</p>";
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }
    catch(PDOException $e){
        echo "データベースエラー". $e->getMessage();
    }

    echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
    echo "<p><a href='?clear_cache=1'>Clear Cache (and reload to see cache miss)</a></p>";

    // キャッシュ削除機能
    if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == 1) {
        if ($memcached->delete($cacheKey)) {
            echo "<p>Cache for '$cacheKey' cleared!</p>";
        } else {
            echo "<p>Failed to clear cache for '$cacheKey'.</p>";
        }
    }
?>
