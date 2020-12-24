<?php
set_time_limit(0);
ini_set('memory_limit', '2048M');

    require_once 'phpQuery/phpQuery/phpQuery.php';

    function vardump($var) {
        echo '<pre>';
            var_dump($var);
        echo '</pre>';
    }

// Получаем код
$html = file_get_contents("https://www.ros-net.ru/news.html");
// Получаем объект dom
$dom = phpQuery::newDocument($html);

// id контейнера новостей elm_FoOAtIotT6WAkgw7ObOezA

$links = pq("#elm_FoOAtIotT6WAkgw7ObOezA")->find("a");
$tmp = array();
$fp = fopen('vardump.php', 'w');

foreach($links as $link){

    $link = pq($link);
    $url = str_replace('/', 'https://www.ros-net.ru/', $link->attr("href"));

    $test = file_get_contents($url);
    $dom_test = phpQuery::newDocument($test);
    $one = pq("#page-container");

    $tmp[] = array(
        "name" => $link->text(),
        "url"  =>  $url,
        "title" => $one->find("h1")->text(),
        "html" => $one->html(),
    );

    foreach($dom_test->find('#page-container')->find("img") as $k => $v)
    {
        $file = pq($v)->attr('src');
        $imgurl = 'https://www.ros-net.ru' . $file;
        $filename = str_replace('/', DIRECTORY_SEPARATOR, $file);
        $path =  __DIR__ . $filename;
        if (!is_file($path)){
            $res = copy($imgurl, $path);
            if (!$res){
                vardump($url . ' ERROR no image on server for ' . $imgurl);
            }
        }

    }

}
fwrite($fp, '<?php return ' . var_export($tmp, true) . '; ?>');
fclose($fp);

// рабочий пример с одной ссылкой
// $test = file_get_contents("https://www.ros-net.ru/usa-rost-proizvodstva-govyadiny.html");
// $dom_test = phpQuery::newDocument($test);

// foreach($dom_test->find('#page-container')->find("img") as $k => $v)
// {
//     vardump(pq($v)->attr('src'));
// }


phpQuery::unloadDocuments();


?>