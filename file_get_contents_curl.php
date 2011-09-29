<?php
function file_get_contents_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}