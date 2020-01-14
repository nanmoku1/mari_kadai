<?php
// //ベースURL。末尾に「/」は付けない
// define("BASE_URL", "http://192.168.56.2");

// //プロジェクトルートパス
// define("PJ_ROOT_PATH", dirname(__FILE__));

// //smarty各種作業フォルダパス
// define("SMARTY_WORK_DIR", PJ_ROOT_PATH."/smarty");
// define("SMARTY_WORK_TEMPL_DIR", SMARTY_WORK_DIR."/templates");
// define("SMARTY_WORK_TEMPL_C_DIR", SMARTY_WORK_DIR."/templates_c");
// define("SMARTY_WORK_TEMPL_CONF_DIR", SMARTY_WORK_DIR."/configs");
// define("SMARTY_WORK_TEMPL_CACHE_DIR", SMARTY_WORK_DIR."/cache");

// //DB接続情報
// //define("SQL_SERVER", "192.168.56.2");
// define("SQL_SERVER", "localhost"); //接続先
// define("SQL_DB", "get_hatena_data"); //使用DB
// define("SQL_USER", "root"); //DBログインID
// define("SQL_PASS", "uHRl2bCG4TpI"); //DBログインPW

// //composerライブラリ有効化
// require_once(dirname(__FILE__)."/vendor/autoload.php");

return [
    "base_url"=>"http://192.168.56.2"
    ,"pj_root_path"=>dirname(__FILE__)
];