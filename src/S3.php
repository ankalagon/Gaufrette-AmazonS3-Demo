<?php

namespace Tester;

use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3 as AwsS3Adapter;
use Gaufrette\Filesystem;

class S3
{
    public static function get($config)
    {
        $s3client = S3Client::factory($config);
        $adapter = new AwsS3Adapter($s3client, $config['bucketName'], [], true);
        return new Filesystem($adapter);

        $adapter = $this->filesystem->getAdapter();
        $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
        $adapter->write($filename, file_get_contents($file->getPathname()));
    }
}
