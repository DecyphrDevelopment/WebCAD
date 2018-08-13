<?php
$webhook = fopen('php://input' , 'rb');
while (!feof($webhook)) {
    $webhookContent .= fread($webhook, 4096);
}
fclose($webhook);
echo "I've received your request;";
$json = json_decode($webhookContent, true);

$action = $json['action'];
if ($action == "opened" || $action == "closed") {
	$id = $json['issue']['id'];
	$title = $json['issue']['title'];
	$user = $json['issue']['user']['login'];
	$msg = $action . "\n" . $id . "\n" . $title . "\n" . $user;
	$msg = wordwrap($msg,70);
	mail("joshduncan0529@gmail.com","Subject",$msg);
}
if ($action == "published") {
	$tag_version = $json['release']['tag_name'];
	$name = $json['release']['name'];
	if ($json['release']['prerelease'] == true) { $prerelease = "true" }
	if ($json['release']['prerelease'] == false) { $prerelease = "false" }
	$body = $json['release']['body'];
	$msg = $tag_version . "\n" . $name . "\n" . $prerelease . "\n" . $body;
	$msg = wordwrap($msg,70);
	mail("joshduncan0529@gmail.com","Subject",$msg);
}


http_response_code(200);
?>