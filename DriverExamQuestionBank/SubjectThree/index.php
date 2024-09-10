<?php
// 科目三题库
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Response['Data'] = [
        [
            'Title' => '上车准备',
            'Video' => 'https://sp.mnks.cn/km23/sczb.mp4'
        ],
        [
            'Title' => '起步',
            'Video' => 'https://sp.mnks.cn/km23/qb.mp4'
        ],
        [
            'Title' => '直线行驶',
            'Video' => 'https://sp.mnks.cn/km23/zxxs.mp4'
        ],
        [
            'Title' => '变更车道',
            'Video' => 'https://sp.mnks.cn/km23/bgcd.mp4'
        ],
        [
            'Title' => '靠边停车',
            'Video' => 'https://sp.mnks.cn/km23/kbtc.mp4'
        ],
        [
            'Title' => '路口左转弯',
            'Video' => 'https://sp.mnks.cn/km23/lkzzw.mp4'
        ],
        [
            'Title' => '路口右转弯',
            'Video' => 'https://sp.mnks.cn/km23/lkyzw.mp4'
        ],
        [
            'Title' => '通过人行横道',
            'Video' => 'https://sp.mnks.cn/km23/rxhd.mp4'
        ],
        [
            'Title' => '通过学校区域',
            'Video' => 'https://sp.mnks.cn/km23/tgxxqy.mp4'
        ],
        [
            'Title' => '通过公共汽车站',
            'Video' => 'https://sp.mnks.cn/km23/tgggqc.mp4'
        ],
        [
            'Title' => '会车',
            'Video' => 'https://sp.mnks.cn/km23/hc.mp4'
        ],
        [
            'Title' => '超车',
            'Video' => 'https://sp.mnks.cn/km23/cc.mp4'
        ],
        [
            'Title' => '掉头',
            'Video' => 'https://sp.mnks.cn/km23/dt.mp4'
        ],
        [
            'Title' => '加减挡位',
            'Video' => 'https://sp.mnks.cn/km23/jjdw.mp4'
        ],
        [
            'Title' => '模拟夜间场景灯光使用',
            'Video' => 'https://sp.mnks.cn/km23/yjxs.mp4'
        ],
        [
            'Title' => '通过路口',
            'Video' => 'https://sp.mnks.cn/km23/tglk.mp4'
        ]
    ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
