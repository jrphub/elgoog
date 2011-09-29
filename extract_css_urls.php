<?php/**
 * Extract URLs from CSS text
 */
?>
 <?php
function extract_css_urls( $text )
{
    $urls = array( );

    $url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
    $urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
    $pattern         = '/(' .
         '(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
        '|(@import\s*'      . $urlfunc_pattern . ')'      .
        '|('                . $urlfunc_pattern . ')'      .  ')/iu';
    if ( !preg_match_all( $pattern, $text, $matches ) )
        return $urls;

    // @import '...'
    // @import "..."
    foreach ( $matches[3] as $match )
        if ( !empty($match) )
            $urls['import'][] =
                preg_replace( '/\\\\(.)/u', '\\1', $match );

    // @import url(...)
    // @import url('...')
    // @import url("...")
    foreach ( $matches[7] as $match )
        if ( !empty($match) )
            $urls['import'][] =
                preg_replace( '/\\\\(.)/u', '\\1', $match );

    // url(...)
    // url('...')
    // url("...")
    foreach ( $matches[11] as $match )
        if ( !empty($match) )
            $urls['property'][] =
                preg_replace( '/\\\\(.)/u', '\\1', $match );

    return $urls;
}
?>