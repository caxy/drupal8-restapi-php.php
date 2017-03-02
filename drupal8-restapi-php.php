<?php

/*
 * A script to send content to a Drupal 8 site
 */

// Your Drupal user/password
$username = 'admin';
$password = 'pass123';

// The URL of your site + /entity/node?_format=hal_json
$api_url = 'http://127.0.0.1:9200/entity/node?_format=hal_json';

/*
 * Setting up some sameple data to use in the script
 */

$title = 'Sample title ' . time();

// Generate some fake content
$content = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum;

// Assumes you created some new fields in a content type. In this example, we added 2 fields to Article
$field_field_1 = 'Sample extra data 1';
$field_field_2 = 'Sample extra data 2';

$ch = curl_init($api_url);

// Getting the JSON set up for the POST to the Drupal REST API
$data_string = json_encode([
'title' => [['value' => $title]],
'body' => [['value' => $content]],
'field_field_1' => [['value' => $field_field_1]],
'field_field_2' => [['value' => $field_field_2]],
'type' => [['target_id' => 'article']],
'_links' => ['type' => [
'href' => $api_url
]],
]);

// For your debugging pleasure
echo $data_string . "\n\n";

curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/hal+json',
'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);
$result_data = json_decode($result);

print $result;
curl_close($ch);
