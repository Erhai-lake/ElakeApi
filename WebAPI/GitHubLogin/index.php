<?php
// GitHub登录
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate(true)) {
    // 临时代码
    $GitHubCode = (string)$Auth->StringParameters('code');
    // 随机字符串
    $GitHubState = (string)$Auth->StringParameters('state');
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        if ($Redis !== null) {
            if (!$Redis->exists($GitHubState)) {
                $Redis->set($GitHubState, time());
                $Redis->expire($GitHubState, 1 * 24 * 60 * 60);
                // 获取GitHub的Token
                $Parameters = [
                    'client_id' => $_ENV['GitHubClientId'],
                    'client_secret' => $_ENV['GitHubClientSecret'],
                    'code' => $GitHubCode
                ];
                $Header = [
                    'Accept: application/json'
                ];
                $Token = json_decode($Auth->CurlPOST('https://github.com/login/oauth/access_token', $Parameters, $Header), true)['access_token'];
                if (!empty($Token)) {
                    // 获取GitHub用户信息
                    $Header = [
                        'Accept: application/json',
                        'User-Agent: ElakeAPI',
                        'Authorization: token ' . $Token
                    ];
                    $Json = json_decode($Auth->Curl('https://api.github.com/user', [], $Header), true);
                    $GitHubID = $Json['id'];
                    $GitHubName = $Json['login'];
                    $IP = $_SERVER['REMOTE_ADDR'];
                    $SQL = 'SELECT * FROM Users WHERE GitHubID = ?';
                    $STMT = $MySQL->prepare($SQL);
                    $STMT->bind_param('s', $GitHubID);
                    $STMT->execute();
                    $Result = $STMT->get_result();
                    $STMT->close();
                    if ($Result->num_rows > 0) {
                        $SQL = 'UPDATE Users SET LoginIP = ? WHERE GitHubID = ?';
                        $STMT = $MySQL->prepare($SQL);
                        $STMT->bind_param('ss', $IP, $GitHubID);
                        $STMT->execute();
                        $Response['Data'] = $GitHubID;
                        $STMT->close();
                    } else {
                        $SQL = 'INSERT INTO Users (GitHubID, UserName, LoginIP, LimitAPP, Banned) VALUES (?, ?, ?, 3, 0)';
                        $STMT = $MySQL->prepare($SQL);
                        $STMT->bind_param('sss', $GitHubID, $GitHubName, $IP);
                        $STMT->execute();
                        $UserID = $MySQL->insert_id;
                        if ($STMT->affected_rows > 0) {
                            $SecretID = NewSecretID();
                            $SecretKey = NewSecretKey($SecretID);
                            $STMT->close();
                            $SQL = 'INSERT INTO APPs (UserID, SecretID, SecretKey, AccessControl, Switch) VALUES (?, ?, ?, 0, 0)';
                            $STMT = $MySQL->prepare($SQL);
                            $STMT->bind_param('sss', $UserID, $SecretID, $SecretKey);
                            $STMT->execute();
                            $Response['Data'] = [
                                'SecretID' => $SecretID,
                                'SecretKey' => $SecretKey
                            ];
                            $STMT->close();
                        } else {
                            $Auth->Custom('注册失败');
                        }
                    }
                } else {
                    $Auth->Custom('认证错误');
                }
            } else {
                $CodeArray = $Code[1];
                $Response['Code'] = $CodeArray['Code'];
                $Response['Message'] = $CodeArray['Message'];
                $ValidRequest = $CodeArray['ValidRequest'];
                http_response_code($CodeArray['Code']);
            }
        }
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function NewSecretID(): string
{
    $UUID = Uuid::uuid4();
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}

function NewSecretKey(String $Value): string
{
    $UUID = Uuid::uuid5($Value, NewSecretID());
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}
