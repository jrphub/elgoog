<?php
/**
 * Strip numbers from text.
 */
function strip_numbers( $text )
{
    $urlchars      = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
    $notdelim      = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
    $predelim      = '((?<=[^' . $notdelim . '])|^)';
    $postdelim     = '((?=[^'  . $notdelim . '])|$)';

    $fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
    $comma         = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep       = '\x{066B}\x{066C}';
    $numseparators = $fullstop . $comma . $arabsep;
    $plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
    $minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
    $slash         = '[\/\x{2044}]';
    $colon         = ':\x{FE55}\x{FF1A}\x{2236}';
    $units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
    $units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
    $units        .= '\x{32CC}-\x{32CE}';
    $units        .= '\x{3300}-\x{3357}';
    $units        .= '\x{3371}-\x{33DF}';
    $units        .= '\x{33FF}';
    $percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
    $ampm          = '([aApP][mM])';

    $digits        = '[\p{N}' . $numseparators . ']+';
    $sign          = '[' . $plus . $minus . ']?';
    $exponent      = '([eE]' . $sign . $digits . ')?';
    $prenum        = $sign . '[\p{Sc}#]?' . $sign;
    $postnum       = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
    $number        = $prenum . $digits . $exponent . $postnum;
    $fraction      = $number . '(' . $slash . $number . ')?';
    $numpair       = $fraction . '([' . $minus . $colon . $fullstop . ']' .
        $fraction . ')*';

    return preg_replace(
        array(
        // Match delimited numbers
            '/' . $predelim . $numpair . $postdelim . '/u',
        // Match consecutive white space
            '/ +/u',
        ),
        ' ',
        $text );
}