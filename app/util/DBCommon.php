<?php
class DBCommon{
	//使用したSQL文を入れる変数。デバッグに使う
	//$usedSqls = [];
	protected static $_usedSqls = [];

	//DB接続確立func
	public static function dbConnect(){
		$pdo = null;

		try{
			$pdo = new PDO('mysql:dbname='.ConfigUtil::read("sql_db").';host='.ConfigUtil::read("sql_server").';charset=utf8mb4;', ConfigUtil::read("sql_user"), ConfigUtil::read("sql_pass"));
		}catch (PDOException $e){
			return false;
		}

		return $pdo;
	}

	//SELECT SQL実行、抽出ポインタreturn func。returnされたインスタンスを以下のように使い値を取り出す
	/*
	$catch = selectQueryExe($pdo
		,"SELECT nhrs_id, nhrs_title, nhrs_link FROM new_hatena_rss WHERE nhrs_link = 'http://localhost/'"
	);

	while($row = $catch->fetch(PDO::FETCH_ASSOC){
		echo $row["nhrs_id"]."::".$row["nhrs_title"]."::".$row["nhrs_link"];
	}
	*/
	public static function selectQueryExe($pdo, $sql, $isNotSaveSql = false){
		if(!$isNotSaveSql) self::$_usedSqls[] = $sql; //使用したSQLを配列に記録。第三引数にtrueが渡された場合記録しない
		$catch = $pdo->prepare($sql);
		$flag = $catch->execute();
		if (!$flag) return false;

		return $catch;
	}

	//SQL実行func
	public static function queryExe($pdo, $sql, $isNotSaveSql = false){
		if(!$isNotSaveSql) self::$_usedSqls[] = $sql; //使用したSQLを配列に記録。第三引数にtrueが渡された場合記録しない
		$catch = $pdo->prepare($sql);
		$flag = $catch->execute();
		if (!$flag) return false;

		return true;
	}

	public static function getUsedSqls(){
		return self::$_usedSqls;
	}
}

//db接続確立、DB接続済みインスタンスをグローバル変数$pdoへ代入
//$pdo = dbConnect();
