<?php
    require_once 'phpQuery/phpQuery/phpQuery.php';

    function vardump($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }



// html получить
    // $str = '<div id="elem">Текст</div><div>Еще тег</div>';
    // $pq = phpQuery::newDocument($str);

    // $elem = $pq->find('#elem');
    // $text = $elem->html();
    // vardump($text);

// 1 аттрибут
    // $pq = phpQuery::newDocument($str);
    // $str = '<a id="elem" href="http://google.com">Ссылка</a>';
	// $html = phpQuery::newDocument($str);
	// $pq = pq($html);

	// $elem = $pq->find('#elem');
	// $href = $elem->attr('href');
    // var_dump($href);

// несколько атрибутов !!!!!
    // $str = '<a href="1.html">1</a>
    //     <a href="2.html">2 </a>  
    //     <a href="3.html">3</a>';
	// $pq = phpQuery::newDocument($str);

    // $links = $pq->find('a');
    // foreach ($links as $link) {
	// 	$pqLink = pq($link); //pq делает объект phpQuery

	// 	$text[] = $pqLink->html();
	// 	$href[] = $pqLink->attr('href');
	// }

    // vardump($text);
	// vardump($href);



    
// wrap inner
    // $str = '<a id="elem">Ссылка</a><div>Еще тег</div>';
	// $pq = phpQuery::newDocument($str);

	// $elem = $pq->find('#elem')->wrapInner('<b>');
	// $text = $pq->html();
    // var_dump($text);
    
    // Получаем код
$html = file_get_contents("https://www.ros-net.ru/news.html");
// Получаем объект dom
$dom = phpQuery::newDocument($html);
// var_dump($dom);
// echo $html ; 
// id контейнера новостей elm_FoOAtIotT6WAkgw7ObOezA   div - ul - li - span - a

$links = pq("#elm_FoOAtIotT6WAkgw7ObOezA")->find("a");
$tmp = array();
foreach($links as $link){

    $link = pq($link);

    $tmp[] = array(
        "name" => $link->text(),
        "url"  => $link->attr("href")
);
}
// vardump($tmp)  ;


foreach($tmp as $page){

    $url = $page['url'];
    $url = str_replace('/', 'https://www.ros-net.ru/', $url);
    // $page['url'] = str_replace('/', 'https://www.ros-net.ru/', $page['url'] );

    // vardump($page); die();
    
    // до этого момента форич сохраняет список новостей (название и url)
    $test = file_get_contents($url);
    $dom_test = phpQuery::newDocument($test);
    $one = pq("#page-container");
    foreach ($one as $o) {
        $o = pq($o);
    
        $page['title'] = $o->find("h1")->text();
        $page['html'] = $o->find(".zpelement-wrapper.imagetext")->html();
        vardump($page); die();
    }
    var_dump($page);
}

// ниже рабочий пример с рабочей ссылкой одной
$tutarr = [];
$test = file_get_contents("https://www.ros-net.ru/alternativnye-puti-razvitiya.html");
$dom_test = phpQuery::newDocument($test);
$one = pq("#page-container");
foreach ($one as $o) {
    $o = pq($o);

    $tutarr[] = array(
        "title" => $o->find("h1")->text(),
        "text" => $o->find(".zpelement-wrapper.imagetext")->html()
    );
}
var_dump($tutarr);



phpQuery::unloadDocuments();


?>