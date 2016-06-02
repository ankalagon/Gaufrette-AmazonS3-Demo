<?php

require __DIR__.'/vendor/autoload.php';

use Garden\Cli\Cli;
use Garden\Cli\LogFormatter;


$log = new LogFormatter();
$cli = new Cli();

$cli->description('Demo app to upload data to Amazon S3')
    ->opt('location', 'Location from .credentials.php file', false)
    ->opt('destination', 'Destinactiont on S3 upload file', false)
    ->opt('file', 'File or files to upload, ie. /tmp/myimage.jpg or /tmp/myimages/*', true);

$args = $cli->parse($argv, true);

$config = \Tester\Config::get(__DIR__.'/.credentials.php', $args->getOpt('location'));

$files = glob($args->getOpt('file'));
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
    }
    $log->success(sprintf('Files to upload: %s', count($files)));
}

$filesystem = \Tester\S3::get($config);

foreach ($files as $file) {
    $filesystem->write(basename($file), file_get_contents($file), true);
    $log->success(sprintf('Uploaded %s', basename($file)));
}




die();

print_r($filesystem->write('myFile.html', 'Hello world!', true));
