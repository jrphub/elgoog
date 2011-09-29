<?php
 //Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 //array containing the HTTP server response header fields and content.
require_once('file_get_contents_curl.php');
function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );
	$charset="";
	$mime="";
    $ch = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    /* Get the content type from CURL */
	$content_type = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
	
	/* Get the MIME type and character set */
	preg_match( '@([\w/+]+)(;\s+charset=(\S+))?@i', $content_type, $matches );
	if ( isset( $matches[1] ) )
	    $mime = $matches[1];
	if ( isset( $matches[3] ) )
	    $charset = $matches[3];
	
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    $header['content_type']=$content_type;
    $header['mime']=$mime;
    
    if ($charset=="")
       {
			$header['charset']= mb_detect_encoding($url);
			/* Read an HTML file */
			$raw_text=$content;
			/* Get the file's character encoding from a <meta> tag */
			preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s+charset=([^\s"]+))?@i',
			$raw_text, $matches );
			$encoding = $matches[3];
			/* Convert to UTF-8 before doing anything else */
			$header['utf8_text'] = iconv( $encoding, "utf-8", $raw_text );
       }else {
            $header['charset']=$charset;
            $header['utf8_text']=$content;
       }
    return $header;
}