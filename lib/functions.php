<?php
function makeCSMPageTag($tPage //現在のページ番号
	,$tLimit //1ページに表示する件数
	,$tCount //条件に合う全てのデータの件数
	,$tUrl //ページリンクを出力させるページのURL
	,$tBFCount //ページャーを何個表示するか(自分のページ含む)
){

	$tBFCountLeft = 2; //左はいくつまで表示する？ 2つ

	$tBFCountRight = 2; //右はいくつまで表示する？ 2つ

	//左右の個数調整
	$pl = $tBFCount - ($tBFCountLeft + $tBFCountRight + 1);
	if($tBFCount > ($tBFCountLeft + $tBFCountRight + 1)){
		$tBFCountLeft += $pl;
	}else if($tBFCount < ($tBFCountLeft + $tBFCountRight + 1)){
		$tBFCountLeft += $pl;
	}

	$maxPageCount = ceil($tCount / $tLimit); //max置換 //必要
	$startPage = $tPage - $tBFCountLeft;

	if($startPage <= 0){
		$tBFCountRight = $tBFCountLeft - $startPage;
		$startPage = 1;
	}
	else if($tPage == $maxPageCount){    
		$startPage = $startPage - $tBFCountRight;
		if($startPage <= 0) $startPage = 1;
	}

	if(strpos($tUrl, '?') !== false){
		strpos($tUrl, '=') !== false ? $tUrl = $tUrl."&page=" : $tUrl = $tUrl."page=";
	}else{
		$tUrl = $tUrl."?page=";
	}

	echo '<ul class="pagination">';
	$isForOneOut = false; //1ページ目へのリンクをforの前に出力したか否か

	//if($tPage > 1) echo '<li class="prev long"><a href="'.$tUrl.($tPage - 1).'">Prev</a></li>';

	if($tPage - $tBFCountLeft > 1){
		if($startPage - 1 <= 1){
			echo '<li class="page-item"><a class="page-link" href="'.$tUrl.(1).'">'.(1).'</a></li>';
		}
		else{
			echo '<li class="page-item"><a class="page-link" href="'.$tUrl.(1).'">'.(1).'</a></li>';
			echo '<li class="page-item">...</li>';
		}

		$isForOneOut = true;
	}

	$leftPageCount = 0;
	$RightPageCount = 0;
	$pageForMode = "left";
	$outCount = 0;
	$forPageOffset = $startPage;
	for($forPageOffset; $forPageOffset <= $maxPageCount; $forPageOffset++){
		$outCount++;
		if($forPageOffset == $tPage){
			echo '<li class="page-item active"><a class="page-link" href="'.$tUrl.$forPageOffset.'">'.$forPageOffset.'</a></li>';
			$pageForMode = "right";
		}else{
			$pageForMode == "left" ? $leftPageCount++ : $RightPageCount++;
			if($isForOneOut && $forPageOffset == 1){ //1ページ目へのリンクの重複出力阻止

			}else{
				echo '<li class="page-item"><a class="page-link" href="'.$tUrl.$forPageOffset.'">'.$forPageOffset.'</a></li>';
			}
		}

		if($tBFCount == $outCount){
			break;
		}
	}

	if($tPage < $maxPageCount){
		if(($forPageOffset + 1) < $maxPageCount){
			echo '<li class="page-item">...</li>';
		}
		if($forPageOffset < $maxPageCount){
			echo '<li class="page-item"><a class="page-link" href="'.$tUrl.$maxPageCount.'">'.$maxPageCount.'</a></li>';
		}
		
		//echo '<li class="next long"><a href="'.$tUrl.($tPage + 1).'">Next</a></li>';
	}

	echo '</ul>';
}
