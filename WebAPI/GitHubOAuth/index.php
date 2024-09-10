<?php
// 获取GitHubOAuthURL
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate(true)) {
}

if ($ValidRequest) {
    $Response['Data'] = 'https://github.com/login/oauth/authorize?client_id=' . $_ENV['GitHubClientId'] . '&redirect_uri=' . $Auth->CurrentURL() . '/WebAPI/GitHubLogin&state=' . UUIDv4();
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function UUIDv4(): string
{
    $UUID = Uuid::uuid4();
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}
