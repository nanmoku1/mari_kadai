<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{ConfigUtil::read("base_url")}/css/jquery-ui.css">
<script type="text/javascript" src="{ConfigUtil::read("base_url")}/js/jquery-min.js"></script>
<script type="text/javascript" src="{ConfigUtil::read("base_url")}/js/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
{literal}
    <style>
    .table_comment{
        table-layout:fixed;
    }
    .table_comment th {
        background-color: #666666;
        color: white;
    }

    .table_comment thead tr th {
        position:sticky;
        top:0;
        z-index:1;
    }
    </style>
{/literal}
{literal}
    <script>
    $(function(){
        $("[name='ol_date']").datepicker({ dateFormat:'yy/mm/dd' });
        $("[name='bf_date']").datepicker({ dateFormat:'yy/mm/dd' });
    });
    </script>
{/literal}
    <h2>{$allCnt}件</h2>
    <div>
        <form class="form-inline" action="{ConfigUtil::read("base_url")}"  method="GET">
            <label class="col-form-label">日付</label>
            <input class="form-control" name="ol_date" type="text" style="width:130px" value="{if !empty($revPara["ol_date"])}{$revPara["ol_date"]}{/if}" />
            <label class="col-form-label">～</label>
            <input class="form-control" name="bf_date" type="text" style="width:130px" value="{if !empty($revPara["bf_date"])}{$revPara["bf_date"]}{/if}" />
            &nbsp;
            <label class="col-form-label">URL</label>
            <input class="form-control" name="link_url" type="text" style="width:180px" value="{if !empty($revPara["link_url"])}{$revPara["link_url"]}{/if}" />
            &nbsp;
            <label class="col-form-label">タイトル</label>
            <input class="form-control" name="title" type="text" style="width:180px" value="{if !empty($revPara["title"])}{$revPara["title"]}{/if}" />
            <input name="page" value="1" type="hidden"/>
            <input name="in_para" value="1" type="hidden"/>
            <input class="btn btn-primary" type="submit" value="で検索"/>
        </form>
    </div>
    <div style="height:600px; width:1000px; overflow-y:scroll;">
        <table class="table table-striped table_comment" style="width:950px;">
            <thead>
                <tr scope="col">
                    <th style="width:150px;">日付</th><th style="width:350px;">URL</th><th style="width:200px;">タイトル</th><th style="width:250px;">説明</th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$nhrses item=nhre}
            <tr scope="row" style="font-size:x-small;">
                <td>{$nhre["nhrs_h_date"]}</td>
                <td style="overflow:hidden; white-space:nowrap;"><a href="{$nhre["nhrs_link"]}" target="_blank">{$nhre["nhrs_link"]}</a></td>
                <td style="overflow:hidden; white-space:nowrap;" title="{$nhre["nhrs_title"]}">{$nhre["nhrs_title"]}</td>
                <td style="overflow:hidden; white-space:nowrap;" title="{$nhre["nhrs_dsc"]}">{$nhre["nhrs_dsc"]}</td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div>
    {HelperUtil::makeCSMPageTag($cPage, 50, $allCnt, $urlPara, 5)}
    </div>
</body>
</html>