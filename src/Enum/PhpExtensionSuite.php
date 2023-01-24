<?php
/**
 * This file is part of zblogphp-image.
 *
 * @author  chrishyze <chrishyze@gmail.com>
 * @link    https://github.com/chrishyze/zblogphp-image
 * @license https://github.com/chrishyze/zblogphp-image/blob/master/LICENSE
 */

declare(strict_types=1);

namespace ZblogPhpImage\Enum;

enum PhpExtensionSuite: string
{
    case MySql = 'mysql';

    case PgSql = 'pgsql';

    case Dev = 'dev';

    public function extensions(): array
    {
        return match ($this) {
            self::MySql => ['gd', 'imagick', 'mysqli', 'opcache', 'pdo_mysql', 'redis'],
            self::PgSql => ['gd', 'imagick', 'opcache', 'pdo_pgsql', 'pgsql', 'redis'],
            self::Dev => ['amqp', 'apcu', 'bcmath', 'bz2', 'csv', 'event', 'exif', 'gd', 'gnupg', 'grpc', 'imagick',
                'imap', 'ldap', 'memcached', 'mongodb', 'mysqli', 'oauth', 'opcache', 'pcntl', 'pdo_mysql', 'pdo_pgsql',
                'pgsql', 'protobuf', 'redis', 'sockets', 'ssh2', 'swoole', 'xdebug', 'yaml', 'zip', 'zstd'],
        };
    }

    public function customExtensionDefaultSelection(): string
    {
        return match ($this) {
            self::MySql => implode(',', range(0, 5)),
            self::PgSql => implode(',', range(0, 5)),
            self::Dev => implode(',', range(0, 32)),
        };
    }
}
