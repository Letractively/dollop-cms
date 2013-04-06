<?php


if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

global $language;
/**
md.search.name=search
;
md.search.destination=website/search/
;
md.search.index=/website/search/main
;
md.search.text=q
;
md.search.table=t
;
md.search.reference=r
;
md.search.language=language/
;
md.search.charset=chs
;
md.search.token=tkn
;
md.search.stand=stn
 */
$content = null;
$destination = propc("md.search.destination");
$query = propc("md.search.text");
$tableS = propc("md.search.table");
$reference = propc("md.search.reference");
$charset = propc("md.search.charset");
$token = propc("md.search.token");
$stand = propc("md.search.stand");

$title = $language['md.title'];
$var = mysql_real_escape_string($_REQUEST[$query]);
//
//echo "<pre>",var_dump($_POST);
// Setup the associative array for replacing the old string with new string
global $theme;
$theme->template_setup("SEARCH");
$my = new mysql_ai;
$my->Select("search_whitelist");
$options = array();
$arrayTables = array();$name_table=null;
foreach ($my->aArrayedResults as $row) {
    if (!in_array($row['tables'], $arrayTables)):
        $name = str_replace(array(PREFIX, "_"), array("", " "), $row['tables']);
        $arrayTables[] = $row['tables'];
        $options[] = $row['tables'];
    endif;
    ;
}
    $htmlopt = null;
    foreach($options as $option){
        $name = str_replace(array(PREFIX, "_"), array("", " "), $option);
        $name_table =  urlencode( md5crypt(  $option . HEX ));
        if($table == $option){
            $selected = 'selected="selected"';
            $htmlCheck = "&#10004;";
        }else{
            $selected = '';
            $htmlCheck=null;
        }

        $htmlopt .=<<<eol
        <option value="$name_table" $selected>$htmlCheck $name</option>
eol;
    }

//
// Get results
if (isset($_REQUEST[$tableS]) && isset($_REQUEST[$query])) {
    $my = new mysql_ai;
    $my->Select("search_whitelist");
    $checker = false;
    foreach ($my->aArrayedResults as $row) {
        //
        // Check real table cryoted
        if (md5crypt(urldecode(  $_REQUEST[$tableS] ),  $row['tables'] . HEX )):
            $checker = true;
            $table = $row['tables'];
            break;
        endif;
    }

    $tpl_tag["QUERY"]           = $query;
    $tpl_tag["TABLE"]           = $tableS;
    $tpl_tag["TABLE_CONTENT"]   = $htmlopt;
    $tpl_tag["SUBMIT"]          = $language['lan.submit'];
    $tpl_tag["SEARCH_TITLE"]    = $_REQUEST[$query];
    $content .= theme::custom_template("search", $tpl_tag);
    if ($checker === true) {
        $query = "SELECT title,body, ( MATCH(title,body) AGAINST ('{$var}%' IN BOOLEAN MODE) ) AS relevance FROM $table
       WHERE ( MATCH(title,body) AGAINST ('{$var}%' IN BOOLEAN MODE) ) HAVING relevance > 0  ORDER BY relevance DESC";
       $page = new mysql_lister($query, 30);
        $result = mysql_query($query . $page->limit()) or theme::content(array($language['md.title.error.srch'], $language['md.body.error.srch'].  mysql_error()));
        if ((bool) $result) {
            $i = 0;
            $tb = new html_table("","","0",2,4,array("width"=>"100%"));
            while ($row = mysql_fetch_array($result)):
                $id = uniqid("search");
                $tb->addRow();
                $tb->addCell("<a class=\"open-search\" rel=\"$id\"  href=\"#{$row['title']}\" ><h3>" . $row['title'] . '</h3></a>', "title");
                $tb->addRow();
                $tb->addCell("<div id=\"content-$i\"> " . truncate(strip_tags( $row['body']), 350,"<a class=\"open-search\" rel=\"$id\" href=\"#{$row['title']}\">{$language['md.readmore']} </a>",false) . "</div>");
                $tb->addRow("");
                $tb->addCell("&nbsp;","","",array("height"=>"35px"));
                $tb->addRow();

                $body = str_replace('src="','src="/',$row['body']);

                $subs .=<<<eol
                <div id="{$id}"  style="display:none" class="search content">
                <div class="close-button"></div>
                    <h2>{$row['title']}</h2>
                    <p>
                        {$body}
                    </p>
                </div>
eol;
                $i++;
            endwhile;
            $nav = $page->display_list();
            $content .= "<small>{$language['md.total.results']} $i | {$language['md.show.searchin']} {$name}</small> <br /><br /><p><div id=\"allsearch\">".$tb->display()."</div></p>".$subs;
            $content .= "<hr /><div align=\"center\" class=\"lister\">{$nav}</div>";
        }
    }
}


    foreach($options as $option){
        $name = str_replace(array(PREFIX, "_"), array("", " "), $option);
        $name_table =  urlencode( md5crypt(  $option . HEX ));
        if($table == $option){
            $selected = 'selected="selected"';
            $htmlCheck = "&#10004;";
        }else{
            $selected = '';
            $htmlCheck=null;
        }
        $htmlopt .=<<<eol
        <option value="$name_table" $selected>$htmlCheck $name</option>
eol;
    }

// Show if content is empty
if(is_null($content)){
    $tpl_tag["QUERY"]           = $query;
    $tpl_tag["TABLE"]           = $tableS;
    $tpl_tag["TABLE_CONTENT"]   = $htmlopt;
    $tpl_tag["SUBMIT"]          = $language['lan.submit'];
    $tpl_tag["SEARCH_TITLE"]    = $_REQUEST[$query];
    $content = theme::custom_template("search", $tpl_tag);
}



//$THEME = null;
if (!empty($content)):
    theme::content(array($title, $content));endif;