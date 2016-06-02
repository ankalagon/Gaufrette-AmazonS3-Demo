<?php

namespace Tester;

use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3 as AwsS3Adapter;
use Gaufrette\Filesystem;

class S3
{
    static $_config = '';

    public static function get($config)
    {
        self::$_config = $config;

        $s3client = S3Client::factory(self::$_config);
        $adapter = new AwsS3Adapter($s3client, self::$_config['bucketName'], [], true);
        return new Filesystem($adapter);
    }
}
