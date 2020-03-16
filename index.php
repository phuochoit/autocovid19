<?php
require_once('./simple_html_dom.php');
// $url = 'https://ncov.moh.gov.vn/';
$url = 'https://vi.wikipedia.org/wiki/%C4%90%E1%BA%A1i_d%E1%BB%8Bch_COVID-19_t%E1%BA%A1i_Vi%E1%BB%87t_Nam';
function get_web_page($url)
{
    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(

        CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
        CURLOPT_POST           => false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     => "cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      => "cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_SSL_VERIFYPEER    => false  // stop after 10 redirects
    );

    $ch      = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}
$result = get_web_page($url);
$root_class = '';
$html = str_get_html($result['content']);
$content = $html->find('#mw-content-text', 0);
$table = $content->find('table.wikitable.sortable.mw-collapsible tbody', 0);
$tr_yable = $table->find('tr');
$total =  count($tr_yable) - 1;
$results = array();

foreach ($table->find('tr') as $k => $result) {

    if ($k == 0 || $k == $total)
        continue;
    if($k == $total-1){
        $results[$k]['total_ten'] =  $result->find('th', 0)->plaintext;
        $results[$k]['totla_soca'] =  $result->find('th', 1)->plaintext;
        $results[$k]['tong_sochet'] =  $result->find('th', 2)->plaintext;
        $results[$k]['tong_hoiphuc'] =  $result->find('th', 3)->plaintext;
    }else{
        $results[$k]['ten'] =  $result->find('td a', 0)->plaintext;
        $results[$k]['soca'] =  $result->find('td', 1)->plaintext;
        $results[$k]['sochet'] =  $result->find('td', 2)->plaintext;
        $results[$k]['hoiphuc'] =  $result->find('td', 3)->plaintext;
    }
}

var_dump($results);
