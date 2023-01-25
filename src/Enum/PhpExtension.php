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

enum PhpExtension: string
{
    case apcu = 'apcu';

    case bcmath = 'bcmath';

    case bz2 = 'bz2';

    case event = 'event';

    case exif = 'exif';

    case gd = 'gd';

    case gnupg = 'gnupg';

    case imagick = 'imagick';

    case memcached = 'memcached';

    case mongodb = 'mongodb';

    case mysqli = 'mysqli';

    case opcache = 'opcache';

    case pcntl = 'pcntl';

    case pdo_mysql = 'pdo_mysql';

    case pdo_pgsql = 'pdo_pgsql';

    case pgsql = 'pgsql';

    case protobuf = 'protobuf';

    case redis = 'redis';

    case sockets = 'sockets';

    case ssh2 = 'ssh2';

    case swoole = 'swoole';

    case xdebug = 'xdebug';

    case zip = 'zip';

    case zstd = 'zstd';

    public function enableConf(): string
    {
        return match ($this) {
            self::apcu => 'extension=apcu.so',
            self::bcmath => 'extension=bcmath.so',
            self::bz2 => 'extension=bz2.so',
            self::event => 'extension=event.so',
            self::exif => 'extension=exif.so',
            self::gd => 'extension=gd.so',
            self::gnupg => 'extension=gnupg.so',
            self::imagick => 'extension=imagick.so',
            self::memcached => 'extension=memcached.so',
            self::mongodb => 'extension=mongodb.so',
            self::mysqli => 'extension=mysqli.so',
            self::opcache => 'extension=opcache.so',
            self::pcntl => 'extension=pcntl.so',
            self::pdo_mysql => 'extension=pdo_mysql.so',
            self::pdo_pgsql => 'extension=pdo_pgsql.so',
            self::pgsql => 'extension=pgsql.so',
            self::protobuf => 'extension=protobuf.so',
            self::redis => 'extension=redis.so',
            self::sockets => 'extension=sockets.so',
            self::ssh2 => 'extension=ssh2.so',
            self::swoole => '#extension=swoole.so',
            self::xdebug => 'zend_extension=xdebug.so',
            self::zip => 'extension=zip.so',
            self::zstd => 'extension=zstd.so',
        };
    }
}
