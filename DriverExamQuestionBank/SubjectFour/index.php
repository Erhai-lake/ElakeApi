<?php
// 科目四题库
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 题号
  $ID = (int)$Auth->IntParameters('ID') - 1;
}

if ($ValidRequest) {
  $ExamCodes = explode(',', '2c15b,8ac1a,e97d7,10e03,0202c,b109d,ebf95,0f7f1,0bca9,36b59,9eefd,eb795,68418,b2a2b,8e24f,e76a1,e3970,c36f5,5a407,ee9d9,aeb78,0a570,bdc91,f8f59,c08f0,bb45b,515d9,b347d,28b17,b3dab,bbbbd,f1e50,09587,c7890,7a9b3,354b9,e2803,78c8c,256e7,9a31a,43137,3ba9a,b7641,f3192,5a496,f9acd,1c1fa,c1cb6,7e43d,43b6e,a50ed,fa908,87ad2,9ea88,c788b,64fdd,95c60,d7ddf,ca436,007c7,a7057,cab0a,5afed,7a961,aa5c5,157f8,fc304,12c14,179bc,82e35,d5275,303f7,e5221,d4bf5,c78bc,97940,3cfb2,7134a,b85c7,abf33,daf75,f0e5e,8ff78,b2efe,79e6b,dd188,969c7,a856f,2ea7b,c8f91,bb731,45056,658d9,859de,ba732,ebf44,5e748,bc73e,b7749,a1a23,08fb6,52c84,2ae95,322cf,1eea3,40245,6b3b1,a215a,82381,b6313,d7a96,2578c,27457,ca78c,6ce2a,140ef,49452,67aac,be529,e7b8a,9fa10,c158f,3e964,1e2bb,212b0,fc9c7,c096c,5cfe9,ac2bf,54456,8bfb9,5f324,ac70b,90575,817db,60ea8,8502e,d6e51,5c057,51231,e1353,97cd8,50512,56ecd,ed246,c9b5c,cd599,6d806,48695,961e8,dec7c,02b1d,73790,b6b28,ef876,60ef6,48d07,c007a,837e2,b260a,21389,fa07d,906cb,be0ba,14ef3,debea,436f7,6e7e9,60428,efbe5,2e063,6aae7,614f2,a4e14,ab335,b2b36,ca538,35b1c,6641b,b279a,a14a9,05f62,44840,e79b5,91a1e,bf47f,80796,3cf91,779b3,52c5e,44de9,dcd2f,ceee6,993c9,3a24b,0766b,4d6fa,bda35,3cb4e,eeef6,3cd13,9103a,c6424,76bcc,8022f,5fef8,53998,abfa9,93c85,f2b94,a34ce,c831f,14088,81cff,c2719,23fe1,e0774,e2912,3cbdc,05320,e7e31,78e16,b61d6,a8bec,cce92,f68ba,a0252,d058b,b733e,b812d,aea74,e417f,a0c66,c87c0,f0175,e996e,fffcc,b20f2,e3834,cb709,d5399,c218a,a740f,d324d,fa535,bcbed,e7755,cc3f0,aaef0,b6c1d,a3f35,ebed2,f8175,bebd6,cdf38,bf561,c0f8d,d9ac6,ea6fa,da81e,faa0e,addb6,ec2cf,a4d17,eff90,d51b1,bdfee,e1b62,cc671,fc9cf,df350,df916,a9d8d,b83cd,ef7bf,b0c07,b558e,fab1d,b391c,d85eb,b0fd9,be67e,ebcdd,bc404,de984,f5b15,d4007,adca2,e4f14,a1353,bc044,e9a6f,a068e,ffe99,dde2f,bb658,53c76,ddd2d,7f5d0,b1f00,a32a5,c64f1,da504,f6e06,a7877,b1f75,e3e03,ec6e4,f5ead,ebf69,af7ed,e407d,e0050,e5ca9,e577e,cc73c,c039e,d715c,a94e7,473e8,d3a77,f5739,f5d4f,e3ccc,aa596,eccb2,ead01,d0967,cb7ec,fb9c7,c7ac0,b9039,d8779,a23fc,ca098,cd223,d2131,c084c,b6188,c3e97,cb6f6,d2739,e1675,e89d7,bb46f,aa51e,ffadf,bac4c,a5314,b4942,b071a,c15f5,b23cc,e2e99,c116f,c4f35,b743a,c4998,dedd1,d8f6f,c053c,dc64f,e5e37,fad44,ce3dd,ad0f7,ee10f,dbcbf,e8074,f44f9,b51f0,f692a,e6d45,ff119,f01fe,d5e7c,a9711,d3d1e,f292a,ced71,e6668,ab459,f47b9,f0356,e6b0c,b66e9,abcda,16d82,15d22,f1b88,a4330,b2b3f,8af48,ab04f,6c125,cded9,c458e,a58c6,e5400,ffbe1,cf073,a23b3,36d33,2c773,fecd1,f52c9,e32ed,8aaae,1dccc,aa0f0,a73f1,7d027,73131,8a579,e6fb8,f1bbf,801c6,d4eb6,e79a0,85d3d,077c8,e001c,45cf4,8362b,c00e3,047f6,e5678,4d14b,c045d,1f4a4,a99b0,94c53,54a92,d3633,b84e8,f3c29,2fc4a,195c0,6000b,49b05,0203b,6effd,3a8cc,883e8,4f318,ba1cf,e6f82,b4c0a,79d4f,df8c9,ac2b2,a5fa8,ee70b,56c48,23082,f6de7,4ee52,0a7bd,a990d,1e31d,49268,6b4f8,cd884,684b6,9c972,69103,bc7d9,17d69,30081,94644,9b2eb,5c82d,8146e,3b63a,9c436,3ce2d,f7e51,e39fb,2d870,3bff4,e5cfc,04f25,77e6e,9c259,59e10,9f83a,a8d5b,a236a,2f303,dbab9,5f567,edddd,5ec6d,e8369,5d883,bea53,35f9b,683c0,b78ae,ce597,b30e3,6112b,3f620,d23ee,c3f1f,aafad,63844,6679a,542df,59cd0,d8d85,674bb,71dca,a04f4,a30a3,b32a7,208cd,c128c,88bc8,c123a,0ec98,83b56,242e0,0bed0,9795a,d802e,ab41c,db4a1,e0842,c4552,d9703,4d34d,c516b,8bc73,9269f,f8dde,e1e40,bff53,f1b8f,b20eb,c5b0f,e72f8,d57bf,de5e6,ef8b1,e376f,d79a1,acd5a,ba00b,a1ffa,f8679,ccf4a,bc708,ca016,d1c4e,a68f0,c213d,eda18,ade9a,eddd3,b20d6,ed9a4,b3cfa,c73e0,519d9,6d9af,ff6ac,0e915,9ac33,d927d,b47e4,fbe22,4f211,a1e33,f63f1,4e160,c5f3b,616e0,6cd0d,89473,f4f21,9bcb3,2867b,807a8,130fb,12c69,017d6,6aeaa,fe06a,59fbd,4a0ff,d27ca,eb735,819ab,4f065,f4a64,a6615,95ff2,1e938,e5b71,2c989,8b864,67974,8109c,a376e,9e031,80cf3,e7b13,084fd,ff06b,2e73a,50e6b,238d6,6e76a,859eb,467bf,21e1a,961a7,bd190,86f9b,93a64,d72d6,b12b0,ca724,46d09,b8699,78c25,a7f66,ffcd7,7b165,04636,5841a,c1cc5,290e8,863ac,c97bc,bd2f8,88155,5655d,62935,8619d,ff906,9bc46,8eda8,dbb4a,c8eb5,bad13,81427,8714d,1fe18,af074,69abd,4639e,31152,3a172,e6d89,65b42,08412,d087e,c6cc0,85d52,ba77a,0afcd,33a5e,b60ef,ca7d7,8e6ac,71375,61b21,eb252,0ad2c,2dd2f,5a7b7,c0fdf,cf98b,38e59,e8b7c,118f9,fd67f,2c3bb,d4024,ce1c0,c4a96,d4b59,09f62,22173,f308d,6b269,df4cc,ce9b0,33f22,58c97,90577,397c5,fe055,a3c92,19b64,efa09,53c4b,10e0c,ee187,881a9,97ecd,1ad72,6017d,14258,c0b7e,0ed7e,6bb19,38f11,4fa06,e5dd8,9bbfc,cf457,9a9ad,52ea4,38e5c,4193a,9b59f,b1371,26352,28e07,71e5e,d923d,a74c9,c204e,7170f,73ccd,d5470,c16c1,a5b36,51fe7,c5ac6,32c78,80b37,44ac6,37b37,1243e,d3937,3e01b,65c9d,3fea6,2a2c3,1cb7a,9821c,7971e,1163c,49c8f,646d7,54584,32bfa,b9ed8,8b4b7,7b211,4b622,c996e,eb49c,69a0d,75b11,45352,44bf2,07512,cb263,24b24,f7294,f1353,284e5,c00dd,57854,44d40,128d2,2205b,94278,e9c3b,6f533,b6b90,58638,4f718,0f36c,1ac30,34b2f,72e3c,2b14a,574e6,a367c,41689,66c69,79bf5,0b51d,7e87f,8bb46,d1d95,b3c79,0b2b6,95c8c,321df,61c35,d4e94,16c87,e4408,8bd10,f6bf8,003c0,5a1d3,42799,edac5,005e8,cb158,547de,1840d,a2f3b,3d25a,965f0,5bd1b,cf0d7,40922,fdd50,80b13,0e9e1,8e766,8d6c5,69132,6746e,ad831,8d197,b9c3c,fe61a,afd0c,cdaa6,cfa3e,2a692,88fbb,88658,c0bd1,568f4,2cca3,3cd85,91402,eca62,d4863,70705,d47f6,704ea,3b054,29a98,1e3c8,ccc22,fcaa3,43b54,5eaf6,5c85d,dfcfa,6497c,da3cc,bbd1c,62646,212f6,49141,b6396,6bfe9,14b90,47364,8798b,0eec5,83039,8b0b5,f43f7,b2dea,a8723,4e5bd,c741c,33380,43429,503c6,05fcb,70446,35d8c,4d9c9,c5ad4,07ba3,53515,dd3c4,c92ea,39a17,e1817,86aea,ca38a,243cf,2a4ee,42e8d,09f91,3f35d,11e0b,072c6,73b62,d4333,5b9c6,0b507,f027c,5aea2,e78cd,33416,602cd,4507d,c723b,b5113,54fa6,9d161,853ef,11fbd,97b3c,7a981,c87e2,6425d,fecd3,bb685,fb2b7,aa1d3,eb46c,0ea23,b14b5,368fa,7c563,78d15,c8c06,7fc96,95162,f33bb,e5540,e046d,0c159,49e6f,8f8eb,9d464,5cfa8,aba3d,786db,60a01,60697,e11cd,2048c,bc16b,c10b7,99425,7e170,bd4f8,44af8,0fa2e,b2701,783ba,83705,5c305,85b33,f9b21,4c0b1,f5ac0,02465,5fddb,58158,307f5,252fe,d2870,871b9,8ffaf,24fc4,222a3,09f74,183b4,f131f,c8f3d,b9564,a9bfb,a7a67,d693a,d5212,657d8,9e71f,d5671,abf5d,4f2f5,abeaa,dde4a,8de3b,5e4f0,0fce3,23d21,30b17,76889,c8367,636ee,330a4,7cd61,2136b,98a4f,9b3aa,f12bb,da6f6,f5aa6,03acc,b8b49,b107b,9b26d,b4fa8,efb1c,0335b,a174e,e1ff8,84748,fc362,f80c5,bb949,a0e5a,52b1f,34b1f,d889e,afa49,7d679,29305,278c4,9585c,1e33a,61585,0e2a8,2a1e0,35e89,c3f4e,24d88,63e36,a8539,8b54b,99028,4f644,681cc,d4b63,7b903,45a57,87437,310a7,26880,a631e,7a736,981ea,ddf01,a1e78,2dd02,90ae2,8ebd5,a922c,34e3a,96c58,a44b7,1d849,87ad5,d9e12,fdec7,0b9d5,1cc77,6a6eb,76eac,060cb,ef741,d8d7a,fc7bc,e4a80,deee9,c974f,bc264,77894,e3639,99176,08f47,7d3e8,5d5f9,fa17e,21ae2,c0b90,445f8,4a294,c6cd0,db775,6e474,ecb0a,71b2c,aa3c7,cf79b,30d3b,8635f,376b0,b8bc4,e28bd,0a9df,0a39e,fc6d8,128be,70456,d7f5e,88e4f,74deb,bd184,01761,321d8,0ab46,9be31,9e209,c2780,efd55,5129f,0634a,0e0ec,e7ef9,b574d,f3b12,eb6e6,a1b60,e3e7c,c2f2c,e9402,2b757,fbef3,a8756,beca3,f7c37,fc06f,a4af2,c2b36,b4e29,ac2fa,e4599,fc6d2,eeec7,e720e,c705c,b8990,c2ded,c8716,f4c47,d3f35,c9bbf,a4f22,af502,cf67c,a4bba,b6268,cb28f,bf7d0,ca3b1,fbce0,d9174,b992f,43831,4be83,ab827,09298,de6ca,39c10,9b2b8,5ff22,48aeb,82f57,b4173,0b0a2,ae848,953e8,de7cc,86e55,a7f08,55208,1b0aa,db1e7,7ca7d,8ac2e,02a6c,33e3b,7d133,b1c42,fae58,039c3,26425,5dd2c,cf4b7,3af32,a1ec0,37572,85680,c331c,27222,f11ad,c619f,ee425,5286a,9f093,d3dcf,80ac4,97e71,d29fa,73988,abce4,c1072,8b749,abb35,e3c04,3ebcf,27a67,1eca2,3ca38,f0d9a,08a0f,6efd4,535ff,b0cfa,2be29,2e6f9,5e5a0,0331c,8797d,42700,b2d3b,ee98d,28198,a2b02,ba561,2dd94,f935c,42380,b5ebc,9887c,e7f9d,9970d,5ce92,7606b,d2f61,4cdcc,e4e52,f9e1f,57680,f08fb,e1603,ff658,1e293,b1ed6,94ac8,92d2c,f5541,20a5b,48979,f0bdd,7c362,4fc99,154c4,dfaec,f9a9c,95fb6,a1a57,b0e99,a8ea1,a8fdf,d463e,c720e,e2d2d,dbcd8,e0265,d0729,a7786,fc82a,b3478,b8cb6,b2990,be567,eec0c,3576d,41eed,aaba2,c0138,7fd3e,ee164,46c17,8302c,e6b90,fca49,97736,c3502,e35a4,585e2,eee72,ee94f,a2ba8,0a40d,47864,90832,ba75e,4e3a9,07d62,75b2c,76f65,eca2f,b389c,66a17,b01bf,c5098,d62fa,89687,94f69,6da2f,dc76e,6802e,a69a4,96eed,c12c3,d40fe,d8dee,e95ce,dbd1f,c8971,927a3,2566e,f047d,41172,f3548,fea02,f634b,d3998,b3cee,bb6b2,aa44c,ddb87,ed1cb,a0eee,b1f4d,a5b2b,a93f2,d704f,bdb25,2d1d4,a9eb5,b0671,9b18f,2b0c4,8f1ec,07f6d,ed8f7,a74a2,46806,faa42,a580e,4fc7a,0fb21,97686,37b6b,c9263,f1b18,79e07,e9c0b,01e28,d345f,fcb88,f1301,75642,2a3b2,f70b6,2993b,b493e,64a3d,21f14,f8543,ee954,533ba,cb0c6,abefd,c3276,bc3f4,fc3af,c6b09,af933,b9e26');
  $ExamJson = json_decode($Auth->Curl('GET', 'https://tkdata.mnks.cn/ExamData/' . $ExamCodes[$ID] . '.json'), true);
  if ($ExamJson['tx'] === 1) {
    $QuestionType = '判断题';
    $Topic = $ExamJson['tm'];
    $Option = ['A: 对', 'B: 错'];
    $Answer = ($ExamJson['da'] === '对') ? 'A' : 'B';
  } elseif ($ExamJson['tx'] === 2) {
    $QuestionType = '选择题';
    $Extraction = explode('<br/>', $ExamJson['tm']);
    $Topic = $Extraction[0];
    for ($I = 1; $I < count($Extraction); $I++) {
      $OptionExtraction = explode('、', $Extraction[$I]);
      $Ago = $OptionExtraction[0];
      array_shift($OptionExtraction);
      $Option[] = $Ago . ': ' . implode('', $OptionExtraction);
    }
    $Answer = $ExamJson['da'];
  } elseif ($ExamJson['tx'] === 3) {
    $QuestionType = '多选题';
    $Extraction = explode('<br/>', $ExamJson['tm']);
    $Topic = $Extraction[0];
    for ($I = 1; $I < count($Extraction); $I++) {
      $OptionExtraction = explode('、', $Extraction[$I]);
      $Ago = $OptionExtraction[0];
      array_shift($OptionExtraction);
      $Option[] = $Ago . ': ' . implode('', $OptionExtraction);
    }
    $Answer = $ExamJson['da'];
  }
  if (substr($ExamJson['tv'], 0, 7) === 'http://' || substr($ExamJson['tv'], 0, 8) === 'https://') {
    $Image = $ExamJson['tv'];
  } else {
    $Image = 'https://sucimg.itc.cn/sblog' . $ExamJson['tv'];
  }
  $Response['Data'] = [
    'QuestionType' => $QuestionType,
    'Topic' => $Topic,
    'Option' => $Option,
    'Answer' => $Answer,
    'Image' => $Image,
    'Total' => count($ExamCodes)
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
