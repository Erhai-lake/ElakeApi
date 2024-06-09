<?php
// 获取GitHubOAuthURL
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate(true)) {
}

if ($ValidRequest) {
  $Response['Data'] = 'https://github.com/login/oauth/authorize?client_id=' . $_ENV['GitHubClientId'] . '&redirect_uri=' . $Auth->CurrentURL() . '/WebAPI/GitHubLogin&state=' . UUID();
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function UUID(): string
{
  $UUID4 = Uuid::uuid4();
  $UUID4String = strtoupper($UUID4->toString());
  $UUID5 = Uuid::uuid5('6ba7b810-9dad-11d1-80b4-00c04fd430c8', $UUID4String);
  $UUID5String = strtoupper($UUID5->toString());
  return $UUID5String;
}
