# 洱海API

**初高中写的, 仅供学习参考(虽然也没有什么学习加载)**

**抓包类API随着时间, 可能会失去功能, 仅供参考!**

## 部署

### 环境需求

1. Nginx
2. PHP(7.4)
   1. curl
   2. gd2
   3. mysql
   4. openssl
   5. redis
3. MySQL
4. Redis

### 向导

1. 执行 `MySQL.sql` 数据库文件
2. 修改 `.env` 配置
3. 访问 `[http|https]://[域名]` 返回延迟即可
4. 注册API账号,使用秘钥访问 `[http|https]://[域名]`

## 用到的库

1.  vlucas/phpdotenv
    1. 用途: 用于从`.env`文件加载环境变量的库
    2. 版本: v5.5.0
2.  ramsey/uuid
    1.  用途: 用于生成和处理通用唯一标识符(UUID)的库
    2.  版本: 4.2.3
3. endroid/qrcode
   1. 用途: 用于生成QR码的库
   2. 版本: 4.6.1
4. phpmailer/phpmailer
   1. 用途: 邮件创建和发送的库
   2. 版本: v6.9.1
5. overtrue/chinese-calendar
   1. 用途: 用于转换和查询中国农历的工具的库
   2. 版本: 1.0.2
