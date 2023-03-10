# Z-BlogPHP Image

基于最新的 [Z-BlogPHP](https://github.com/zblogcn/zblogphp) 源码构建容器镜像。  

由于每个人对运行环境的需求不同，为保证镜像的通用性和灵活性，仅包含 PHP 运行环境。  
如需搭配 Nginx、MySQL 等服务，考虑使用 Docker Compose 等容器编排工具。  

## 特性

### 镜像特性

- 基于 Z-BlogPHP 最新的 Github 源码
- 提供 PHP 7.4 ～ 8.2 版本
- 包含 Composer 工具
- 支持 AMD 64 和 ARM 架构
- 提供不同规格的扩展套件（详见下文）

### 本项目特性

- [x] 批量生成不同 PHP 版本和扩展套件的 Dockerfile
- [ ] 自定义配置生成 Dockerfile
- [ ] 自定义配置生成 docker-compose.yml

## 场景

- 在容器中运行 Z-BlogPHP 博客程序
- 开发调试 Z-BlogPHP 源码
- 开发调试 Z-BlogPHP 插件

## 使用

### Docker 镜像

根据所安装扩展的不同，分为 `mysql`、`pgsql`、和 `dev` 三种套件（具体扩展详见下一小节）。  

### 构建状态

| Image Tag              | Platform                | Status |
| ---------------------- | ----------------------- | ------ |
| 8.2-fpm                | amd64, arm64v8          | [![8.2-fpm](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-82-fpm.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-82-fpm.yml) |
| 8.2-fpm-alpine         | amd64, arm64v8, arm32v7 | [![8.2-fpm-alpine](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-82-fpm-alpine.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-82-fpm-alpine.yml) |
| 8.1-fpm                | amd64, arm64v8          | [![8.1-fpm](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-81-fpm.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-81-fpm.yml) |
| latest, 8.1-fpm-alpine | amd64, arm64v8, arm32v7 | [![8.1-fpm-alpine](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-81-fpm-alpine.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-81-fpm-alpine.yml) |
| 8.0-fpm                | amd64, arm64v8, arm32v7 | [![8.0-fpm](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-80-fpm.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-80-fpm.yml) |
| 8.0-fpm-alpine         | amd64, arm64v8, arm32v7 | [![8.0-fpm-alpine](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-80-fpm-alpine.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-80-fpm-alpine.yml) |
| 7.4-fpm                | amd64, arm64v8, arm32v7 | [![7.4-fpm](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-74-fpm.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-74-fpm.yml) |
| 7.4-fpm-alpine         | amd64, arm64v8, arm32v7 | [![7.4-fpm-alpine](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-74-fpm-alpine.yml/badge.svg?branch=publish)](https://github.com/chrishyze/zblogphp-image/actions/workflows/publish-74-fpm-alpine.yml) |

#### 包含 MySQL 等扩展的镜像（默认镜像）  

- [8.2-fpm](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm/mysql/Dockerfile),
  [8.1-fpm](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm/mysql/Dockerfile),
  [8.0-fpm](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm/mysql/Dockerfile),
  [7.4-fpm](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm/mysql/Dockerfile)
- [8.2-fpm-alpine](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm-alpine/mysql/Dockerfile),
  **[8.1-fpm-alpine (latest)](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm-alpine/mysql/Dockerfile)**,
  [8.0-fpm-alpine](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm-alpine/mysql/Dockerfile),
  [7.4-fpm-alpine](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm-alpine/mysql/Dockerfile)

#### 包含 PostgreSQL 等扩展的镜像  

- [8.2-fpm-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm/pgsql/Dockerfile),
  [8.1-fpm-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm/pgsql/Dockerfile),
  [8.0-fpm-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm/pgsql/Dockerfile),
  [7.4-fpm-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm/pgsql/Dockerfile)
- [8.2-fpm-alpine-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm-alpine/pgsql/Dockerfile),
  [8.1-fpm-alpine-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm-alpine/pgsql/Dockerfile),
  [8.0-fpm-alpine-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm-alpine/pgsql/Dockerfile),
  [7.4-fpm-alpine-pgsql](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm-alpine/pgsql/Dockerfile)

#### 包含大量扩展的开发镜像  

- [8.2-fpm-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm/dev/Dockerfile),
  [8.1-fpm-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm/dev/Dockerfile),
  [8.0-fpm-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm/dev/Dockerfile),
  [7.4-fpm-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm/dev/Dockerfile)
- [8.2-fpm-alpine-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.2/fpm-alpine/dev/Dockerfile),
  [8.1-fpm-alpine-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.1/fpm-alpine/dev/Dockerfile),
  [8.0-fpm-alpine-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/8.0/fpm-alpine/dev/Dockerfile),
  [7.4-fpm-alpine-dev](https://github.com/chrishyze/zblogphp-image/blob/master/dockerfile/7.4/fpm-alpine/dev/Dockerfile),

#### 镜像文件结构  

- Z-BlogPHP 运行目录位于 `/var/www/zblogphp`
- 附带一份源码压缩包，位于 `/usr/local/src/zblogphp.zip`

### 自行构建镜像

以下命令将根据预设配置 `config/generate-publish.json`，批量生成 Dockerfile：

```shell
php bin/zbpimage generator:publish
```

生成的文件在 `dockerfile` 目录下。  

|           | mysql   | pgsql   | dev\*   |
| --------- | ------- | ------- | ------- |
| apcu      |         |         | ✓       |
| bcmath    |         |         | ✓       |
| bz2       |         |         | ✓       |
| event     |         |         | ✓       |
| exif      |         |         | ✓       |
| gd        | ✓       | ✓       | ✓       |
| gnupg     |         |         | ✓       |
| imagick   | ✓       | ✓       | ✓       |
| memcached |         |         | ✓       |
| mongodb   |         |         | ✓       |
| mysqli    | ✓       |         | ✓       |
| opcache   | ✓       | ✓       | ✓       |
| pcntl     |         |         | ✓       |
| pdo_mysql | ✓       |         | ✓       |
| pdo_pgsql |         | ✓       | ✓       |
| pgsql     |         | ✓       | ✓       |
| protobuf  |         |         | ✓       |
| redis     | ✓       | ✓       | ✓       |
| sockets   |         |         | ✓       |
| ssh2      |         |         | ✓       |
| swoole    |         |         | ✓\*\*   |
| xdebug    |         |         | ✓       |
| zip       |         |         | ✓       |
| zstd      |         |         | ✓       |

> \* `dev` 套件仅建议开发人员使用  
> \*\* 因 `swoole` 扩展与 `xdebug` 扩展存在一定的冲突，默认不启用  
> `mysql` 与 `pgsql` 套件其实都包含有 `dev` 套件的所有扩展，只是没有启用，如有需要可以通过 `/usr/local/etc/php/php.ini` 启用

### 自定义镜像

#### 方案一

最简单且常用的办法，就是在本镜像之上再自行构建：  

```dockerfile
FROM chrishyze/zblogphp:latest

RUN 自定义构建步骤...
```

## License

The MIT License
