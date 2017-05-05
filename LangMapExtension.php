<?php
function getList($dir)
{
    chdir($dir);
    return array_diff(scandir(getcwd()), ['.', '..']);
}
function bkFile($filePath)
{
    exec('cp -rf '.$filePath .' '.$filePath.'__BK');
}

function myArrayDiff()
{
    $out = [];
    $arg_list = func_get_args();
    foreach ($arg_list as $arg) {
        $out += array_diff($arg, $out);
    }
    return $out;
}

/**
 * Init data to process
 * @var [type]
 */
echo "\n ==============================\n";

echo "Looking on: ".rtrim($argv[1], '/');
echo "\n\nAre you sure you want to do this?  Type 'yes' to continue: ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 'yes') {
    echo "Nothing :'( !\n";
    exit;
}
fclose($handle);
echo "\n";
echo "Thank you, continuing...\n";

// Init lang directory
$langPaths = getcwd().'/'.rtrim($argv[1], '/');
// Prefix text when doing merge file
$prefixText = "NeedTrans-";

// list all lang folder
$langDirectorys = array_diff(scandir($langPaths), ['.', '..', '.DS_Store']);

var_dump($langDirectorys);

//die();
// data output
$data = [];

// get data
foreach ($langDirectorys as $lang) {
    $dirPath = $langPaths.'/'.$lang;
    $arrayFiles = getList($dirPath);
    foreach ($arrayFiles as $f) {
        // example
        // data[file_name_is_message.php][folder_is_en/jp/vn..];
        $data[$f][$lang] = include($f);
    }
}

// loop each file
foreach ($data as $k => $file) {
    // get list folder name like ['en', 'vn', 'jp']
    $countryCode = array_keys($file);
    foreach ($countryCode as $code) {
        $countDataCopy = 0;
        $misskeys = [];
        foreach ($countryCode as $code2) {
            // get missing keys
            $misskey = myArrayDiff(array_keys($file[$code2]), array_keys($file[$code]), $misskeys);
            // copy missing data
            foreach ($misskey as $key) {
                if (!empty($data[$k][$code2][$key])) {
                    $dataMerge = $data[$k][$code2][$key];

                    if (is_array($dataMerge)) {
                        foreach ($dataMerge as $i => $dataNeedMerge) {
                            $dataMerge[$i] = $prefixText.$dataNeedMerge;
                        }
                    } else {
                        $dataMerge =  $prefixText.$dataMerge;
                    }
                    $data[$k][$code] += [$key => $dataMerge];
                }
            }
        }
    }
}
echo "\n===========> Start write override language file\n";

echo "\n\nAre you sure you want to do this?  Type 'yes' to continue: ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 'yes') {
    echo "Done :'( !\n";
    exit;
}

fclose($handle);

foreach ($data as $fileName => $file) {
    foreach ($file as $folderName => $folder) {
        $fileLangPath = $langPaths.'/'.$folderName.'/'.$fileName;
        bkFile($fileLangPath);
        var_dump($fileLangPath);
        file_put_contents($fileLangPath, "<?php \r\n return ".var_export($folder, true).';');
    }
}

echo "\n";
echo "Finished\n";
