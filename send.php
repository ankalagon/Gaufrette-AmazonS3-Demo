<?php

require __DIR__.'/vendor/autoload.php';

use Garden\Cli\Cli;
use Garden\Cli\LogFormatter;


$log = new LogFormatter();
$cli = new Cli();

$cli->description('Demo app to upload data to Amazon S3')
    ->command('push')
    ->description('Push data to a S3 bucket.')
    ->opt('path', 'Path to upload file', false)
    ->opt('file', 'File or files to upload, ie. /tmp/myimage.jpg or /tmp/myimages/*', true)
    ->command('list')
    ->description('list objects in a bucket.')
    ->opt('path', 'path to retrieve, ie. "myDir/OtherDir"', false, 'string')
    ->command('*')
    ->opt('location', 'Location from .credentials.php file', false);

$args = $cli->parse($argv, true);

$config = \Tester\Config::get(__DIR__.'/.credentials.php', $args->getOpt('location'));
$filesystem = \Tester\S3::get($config);

if ($args->getCommand() == 'list') {
    $files = $filesystem->listKeys($args->getOpt('path', ''));
    foreach ($files as $file) {
        echo $file.PHP_EOL;
    }
} elseif($args->getCommand() == 'push') {
    $files = glob($args->getOpt('file'));
    $path = $args->getOpt('path');
    if ($path != '') {
        $path = trim($path, ' /').'/';
    }

    if (0 == count($files)) {
        $log->error(sprintf('No file to upload in "%s"', $args->getOpt('file')));
    } else {
        $ommitedFiles = 0;
        foreach ($files as $key => $file) {
            if (false == is_file($file)) {
                unset($files[$key]);
                $ommitedFiles++;
            }
        }
        if ($ommitedFiles > 0) {
            $log->success(sprintf('Skipping %s objects (not files)', $ommitedFiles));
git status        }
        $log->success(sprintf('Files to upload: %s', count($files)));

        foreach ($files as $file) {
            $fileName = $rawFilename = $path . basename($file);
            $filesystem->write($rawFilename, file_get_contents($file), true);

            if (\Tester\Config::getDomain()) {
                $fileName = \Tester\Config::getDomain() . $rawFilename;
            }

            $size = number_format($filesystem->size($rawFilename) / 1024, 2);
            $log->success(sprintf('Uploaded %s, size of fil1e: %skB', $fileName, $size));

            if (\Tester\Config::getCloudFrontDomain()) {;
                $log->success(sprintf('Uploaded %s', \Tester\Config::getCloudFrontDomain() . $rawFilename));
            }
        }
    }
}







die();

print_r($filesystem->write('myFile.html', 'Hello world!', true));
