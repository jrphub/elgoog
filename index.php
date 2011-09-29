<?php
require_once('get_web_page_curl.php');
require_once('extract_html_urls.php');
require_once('file_get_contents_curl.php');
require_once('strip_html_tags.php');
require_once('strip_punctuation.php');
require_once('strip_symbols.php');
require_once('strip_numbers.php');
require_once('PorterStemmer.php');

$url="";
$content_type="";
$mime="";
$charset="";
$text="";
$raw_text="";
$utf8_text="";
$i="";

if (isset($_POST['btnGetDetails']))
    {
	$url  = $_REQUEST['txtURL'];
	$result = get_web_page( $url );
	if ( $result['errno'] != 0 )
    	echo " error: bad url, timeout, redirect loop ";
	if ( $result['http_code'] != 200 )
    	echo "error: no page, no permissions, no service";
	$page = $result['content'];
	$content_type=$result['content_type'];
	$mime=$result['mime'];
	$charset=$result['charset'];
	echo "This site is encoded with"." "."<b>".$charset."</b>"." "."format"."<br>";
	$utf8_text=$result['utf8_text'];
	$text = strip_html_tags( $page);
	$utf8_text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );
	$utf8_text = strip_punctuation( $utf8_text );
	$utf8_text = strip_symbols( $utf8_text );
	$utf8_text = strip_numbers( $utf8_text );
	mb_regex_encoding( "utf-8" );
	$words = mb_split( ' +', $utf8_text );
	foreach ( $words as $key => $word )
    	$words[$key] = PorterStemmer::Stem( $word, true );
	$stopWords = mb_split( '[ \n]+', mb_strtolower( $words[$key], 'utf-8' ) );
	foreach ( $stopWords as $key => $word )
    	$stopWords[$key] = PorterStemmer::Stem( $word, true );
	$words = array_diff( $words, $stopWords );
	$keywordCounts = array_count_values( $words );
	arsort( $keywordCounts, SORT_NUMERIC );
	$uniqueKeywords = array_keys( $keywordCounts );
	echo "The keywords are"."<br>";
	foreach($uniqueKeywords as $value) {
	    echo "-". $value;
	    echo "<br>";
	}
}
?>

<body>
	<div id="content">
	    <form action="" method="POST">
	        <div  style="width:1500px;">
	         	<table style="line-height:1em;padding-left:300px;">
	                <tr>
	                    <td width="120px">
	                         Paste A URL Here
	                    </td>
	                    <td>
	                        <input type="text" id="txtURL" name="txtURL" style="width:350px" />
	                        <input type="submit" value="Get Details" id="btnGetDetails" name="btnGetDetails" >
	                        <br/>
	                        <span style="padding:0px;color:gray;font-size:9px;font-weight:normal;">Eg: http://www.wordpress.com</span>
	                    </td>
	                </tr>
	            </table>
	        </div>
		</form>
	</div>
</body>