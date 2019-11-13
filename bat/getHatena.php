<?php
require_once(dirname(dirname(__FILE__))."/config.php");
require_once(PJ_ROOT_PATH."/lib/dbCommon.php");

//処理1:新着チェック、登録----------------------------------------------------
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://b.hatena.ne.jp/entrylist.rss');
// curl_setopt($curl, CURLOPT_URL, 'https://logkari.xyz/');
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE); // オレオレ証明書対策。これを入れないとwindows環境で失敗する
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡
//このUAを入れないと403になる。何か悪意のある攻撃に対しての対策？
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13');

$rtnCnts = curl_exec($curl);
$rtnInfo = curl_getinfo($curl);
if($rtnInfo["http_code"] != 200){
    //失敗
    exit("失敗");
}

$hatenaXml = new SimpleXMLElement($rtnCnts);
// var_dump($hatenaXml);
//var_dump($mediaChild);

if(empty($hatenaXml->item)){
    //終了
    exit("新着なし");
}

//var_dump($hatenaXml);
// $tCnt = 0;
$isAddData = false; //1件以上追加した場合true
foreach($hatenaXml->item as $hatenaData){
    echo "-------------\n";
    // echo "{$hatenaData->title}\n";
    // echo "{$hatenaData->link}\n";
    // echo "{$hatenaData->description}\n";

    $dcData = $hatenaData->children("http://purl.org/dc/elements/1.1/");
    // echo "{$dcData->date}\n";
    $nowDt = date("Y-m-d H:i:s");
    // echo $nowDt."\n";

    if(empty($hatenaData->link)){
        echo "スキップ1\n";
        echo "{$hatenaData->title}\n";
        echo "{$hatenaData->link}\n";
        continue;
    }

    $catch = selectQueryExe($pdo
        ,"SELECT nhrs_id FROM new_hatena_rss WHERE nhrs_link = '".addslashes($hatenaData->link)."'"
    ); //nhrs_del_flgは抽出条件に入れない

    if($catch === false || $row = $catch->fetch(PDO::FETCH_ASSOC)){
        echo "スキップ2\n";
        echo "{$hatenaData->title}\n";
        echo "{$hatenaData->link}\n";
        var_dump($row);
        continue;
    }

    queryExe($pdo
        , "INSERT INTO new_hatena_rss(nhrs_title ,nhrs_link ,nhrs_dsc ,nhrs_h_date ,nhrs_add_date ,nhrs_up_date) VALUES ('".addslashes($hatenaData->title)."', '".addslashes($hatenaData->link)."', '".addslashes($hatenaData->description)."', '{$dcData->date}', '{$nowDt}', '{$nowDt}')"
    );

    $isAddData = true;
    // $tCnt++;
    // if($tCnt >= 3) break;
}

//処理2:古い新着記事を削除----------------------------------------------------
//本番:-3 day  テスト:-1 minutes
queryExe($pdo
    ,"UPDATE new_hatena_rss SET nhrs_del_flg = 1, nhrs_up_date = '".date("Y-m-d H:i:s")."' WHERE nhrs_add_date <= '".date("Y-m-d H:i:s", strtotime("-3 day"))."'"
);


//処理3:日付ソートカラム番号割り振り直し----------------------------------------------------
if($isAddData){
    queryExe($pdo
        ,"UPDATE new_hatena_rss SET nhrs_h_date_sort = 0 WHERE nhrs_del_flg = 0"
    );

    $catch = selectQueryExe($pdo
        ,"SELECT nhrs_id FROM new_hatena_rss WHERE nhrs_del_flg = 0 ORDER BY nhrs_h_date"
    );

    for($i = 1; $i <= 20000; $i++){
        if( !($row = $catch->fetch(PDO::FETCH_ASSOC)) ) break;
    
        queryExe($pdo
            ,"UPDATE new_hatena_rss SET nhrs_h_date_sort = {$i} WHERE nhrs_id = {$row["nhrs_id"]}"
            ,true
        );
    }
}


