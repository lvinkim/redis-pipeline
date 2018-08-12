# tail -f {{file}} > redis 订阅频道

### 环境配置
```
$ cp .env.example .env
$ vi .env

# 上报的机器名
HOST_NICKNAME={{hostname}}

# 应用接口端口
APP_EXPORT_PORT={{port}}

# 自带 redis 管理缓存
CACHE_REDIS_HOST=redis
CACHE_REDIS_PORT=6379
CACHE_REDIS_PASS=
CACHE_REDIS_ADMIN_PORT={{admin_port}}
CACHE_REDIS_ADMIN_PASS={{admin_password}}

# pipeline redis 相关配置
REDIS_HOST={{redis_host}}
REDIS_PORT={{redis_port}}
REDIS_PASSWORD={{redis_password}}

# 日志来源挂载目录
INPUT_DIRECTORY={{path}}

```

### 管道配置
```
$ cd config
$ cp pipeline.json.mini pipeline.json
$ vi pipeline.json
[
  {
    "channel": "health-watcher",
    "filePath": "PROJECT_DIRECTORY/var/logstash/health-watcher.log.",
    "postfixFormat": "Y-m-d",
    "enable": true
  }
]

channel : 表示 redis 订阅的频道
filepath : tail -f 监听的文件
```

### 安装
```
$ docker-compose run --rm crontab composer install
```

### 启动
```
$ docker-compose up -d
```