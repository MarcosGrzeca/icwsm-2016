<?php
ini_set('display_errors', 1);
require_once(dirname(__FILE__) . '/../twitter-api-php/TwitterAPIExchange.php');

function getSettings() {
    return array(
        'oauth_access_token' => "3437475154-b7dqH1B8IK6cnt0Rno19up0xbGESM3NqRonw3U5",
        'oauth_access_token_secret' => "RgYHHYN8pqslPdNsyCgBNPbYCgymvNIeDDZpjRG5kKmpm",
        'consumer_key' => "4erfQe1KH67cmSk8ohVusPaWN",
        'consumer_secret' => "w73tMbfBABA3Zty0S8mA4JIqX6YRu2oCSdEuZQcMgTO138LtZI"
    );
}

function getTweetById($id) {
    $url = 'https://api.twitter.com/1.1/statuses/show.json';
    $getfield = '?id=' . $id;
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange(getSettings());
    return $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();

}

function read_file($local_file) {
    $linhas = array();
    $file = fopen($local_file, "r");
    while(!feof($file)) {
        $linhas[] = fread($file, round($download_rate * 1024));
        flush();
        sleep(1);
    }
    fclose($file);
    return $linhas;
}

function query($sql) {
    try {
        $mys = Connection::get()->query($sql);
        if ($mys) {
            return $mys;
        } else {
            throw new Exception(Connection::get()->error, 1);
        }
    } catch (Exception $e) {
        debug("CATCH");
        debug($sql);
        die($e->getMessage());
    }
}

function getRows($result) {
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getNumRows($result) {
    return $result->num_rows;
}

function debug($param, $tipo = "") {
    if ($tipo == "I") {
        ChromePhp::info($param);
    } else {
        ChromePhp::log($param);
    }
}

function escape($palavra) {
    return mysqli_real_escape_string(Connection::get(), $palavra);
}