<?php
/*if (empty($argv[1])) die("[\033[31mERROR\033[0m] No plugin name given \n Example: ./build.sh goo1-wsdc".PHP_EOL);
$id = $argv[1];
if (!file_exists("/app/src/".$id) AND file_exists("/app/src/goo1-custom-".$id)) $id = "goo1-custom-".$id;
if (!file_exists("/app/src/".$id)) die("[\033[31mERROR\033[0m] Unknown Plugin name or not found ".$id.PHP_EOL);*/

$rootPath = __DIR__."/src/";

// Initialize archive object
$zip = new ZipArchive();
$zip->open(__DIR__."/dist/cloudflare-flexible-ssl.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = str_replace(DIRECTORY_SEPARATOR, "/", substr($filePath, strlen($rootPath)));

        // Add current file to archive
        $zip->addFile($filePath, "cloudflare-flexible-ssl/".$relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

if (!file_exists(__DIR__."/dist/cloudflare-flexible-ssl.zip")) die("[\033[31mERROR\033[0m] File not created".PHP_EOL);
echo("[\033[32mDONE\033[0m] zip file created".PHP_EOL);
echo("[*] Filesize: ".filesize(__DIR__."/dist/cloudflare-flexible-ssl.zip").PHP_EOL);
echo("[*] Modified: ".date("Y-m-d H:i:s",filemtime(__DIR__."/dist/cloudflare-flexible-ssl.zip")).PHP_EOL);
echo("[*] finished ".date("Y-m-d H:i:s").PHP_EOL);

