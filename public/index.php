<?php
//require_once(dirname(dirname(__FILE__))."/config.php");
//composerライブラリ有効化
require_once(dirname(dirname(__FILE__))."/vendor/autoload.php");

use app\util\ConfigUtil;
use app\util\DBCommon;

ConfigUtil::init();

$pdo = DBCommon::dbConnect();

//入力検索条件を元に各種値の整理
$sqlBase = "FROM new_hatena_rss WHERE nhrs_del_flg = 0 ";

$isInPara = false; //検索条件の送信の有無

//送信かcookie復元による条件内容 
$inOlDate = ""; //記事公開日時 ○○から
$inBfDate = ""; //記事公開日時 ○○まで
$inLinkUrl = ""; //記事URL
$inTitle = ""; //記事タイトル

if(!empty($_GET["ol_date"])){
    $isInPara = true;
    $inOlDate = $_GET["ol_date"];
}
if(!empty($_GET["bf_date"])){
    $isInPara = true;
    $inBfDate = $_GET["bf_date"];
}
if(!empty($_GET["link_url"])){
    $isInPara = true;
    $inLinkUrl = $_GET["link_url"];
}
if(!empty($_GET["title"])){
    $isInPara = true;
    $inTitle = $_GET["title"];
}
if(!empty($_GET["in_para"])){ //検索フォームから送信した際に「in_para」パラメータを付与している
    $isInPara = true;
}

if(!$isInPara){
    //検索条件の送信でない場合は、cookie内に保存した検索条件を復元。その後、復元した検索条件で抽出する為の準備
    if(!empty($_COOKIE["hatena_rss_s_ol_date"])){
        $inOlDate = $_COOKIE["hatena_rss_s_ol_date"];
    }
    if(!empty($_COOKIE["hatena_rss_s_bf_date"])){
        $inBfDate = $_COOKIE["hatena_rss_s_bf_date"];
    }
    if(!empty($_COOKIE["hatena_rss_s_link_url"])){
        $inLinkUrl = $_COOKIE["hatena_rss_s_link_url"];
    }
    if(!empty($_COOKIE["hatena_rss_s_title"])){
        $inTitle = $_COOKIE["hatena_rss_s_title"];
    }
}else{
    //検索条件の送信があった場合、cookieへ検索条件の入力を保存
    setcookie('hatena_rss_s_ol_date',$inOlDate,time()+60*60*24*7);
    setcookie('hatena_rss_s_bf_date',$inBfDate,time()+60*60*24*7);
    setcookie('hatena_rss_s_link_url',$inLinkUrl,time()+60*60*24*7);
    setcookie('hatena_rss_s_title',$inTitle,time()+60*60*24*7);
}

//送信された検索条件かcookieから復元した検索条件を元にURLパラメータ生成
$urlPara = "?dummy=0";
if(!empty($inOlDate)){
    $sqlBase.= "AND nhrs_h_date >= '".addslashes($inOlDate)."' ";
    $urlPara.= "&ol_date={$inOlDate}";
}
if(!empty($inBfDate)){
    $sqlBase.= "AND nhrs_h_date <= '".addslashes($inBfDate)."' ";
    $urlPara.= "&bf_date={$inBfDate}";
}
if(!empty($inLinkUrl)){
    $sqlBase.= "AND nhrs_link LIKE '".addslashes($inLinkUrl)."%' ";
    $urlPara.= "&link_url=".urlencode($inLinkUrl);
}
if(!empty($inTitle)){
    $sqlBase.= "AND nhrs_title LIKE '%".addslashes($inTitle)."%' ";
    $urlPara.= "&title=".urlencode($inTitle);
}

//抽出条件に掛かった件数を抽出
$sql = "SELECT COUNT(nhrs_id) AS nhrs_cnt ".$sqlBase;
$catch = DBCommon::selectQueryExe($pdo
    ,$sql
);
$row = $catch->fetch(PDO::FETCH_ASSOC);
$allCnt = intval($row["nhrs_cnt"]);

//表示データ抽出
$sql = "SELECT nhrs_id, nhrs_h_date, nhrs_link, nhrs_title, nhrs_dsc ".$sqlBase."ORDER BY nhrs_h_date_sort DESC LIMIT ";
$cPage = 1;
if(!empty($_GET["page"])){
    $cPage = intval($_GET["page"]);
    $offset = 50 * ($cPage - 1);
    $sql.= "{$offset}, ";
}
$sql.= "50 ";
$catch = DBCommon::selectQueryExe($pdo
    ,$sql
);
$nhrses = $catch->fetchAll(PDO::FETCH_ASSOC);

//smarty準備
//$smarty = new Smarty();
$smarty = new SmartyBC();
//$smarty->allow_php_tag = true; これはダメだった。多くのリファレンスサイトに書かれていた方法なのだが…
$smarty->php_handling = Smarty::PHP_ALLOW;
$smarty->template_dir = ConfigUtil::read("smarty_work_templ_dir");
$smarty->compile_dir  = ConfigUtil::read("smarty_work_templ_c_dir");
$smarty->config_dir   = ConfigUtil::read("smarty_work_templ_conf_dir");
$smarty->cache_dir    = ConfigUtil::read("smarty_work_templ_cache_dir");

//$smarty->assign('baseUrl', ConfigUtil::read("base_url"));
//テンプレートへ受け渡し値
$smarty->assign('nhrses', $nhrses); //抽出データ
$smarty->assign('allCnt', $allCnt); //抽出条件に掛かった全件の件数
$smarty->assign('cPage', $cPage); //現在表示中のページ
$smarty->assign('urlPara', ConfigUtil::read("base_url").$urlPara); //URL用の抽出条件パラメータ
//入力中の検索条件値
$smarty->assign('revPara', ["ol_date"=>$inOlDate, "bf_date"=>$inBfDate, "link_url"=>$inLinkUrl, "title"=>$inTitle]);

$smarty->display('index.tpl');
