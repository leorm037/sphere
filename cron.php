<?php

const PROJECT_DIR = "/var/www/html/sphere";
const DIR_UPLOAD = PROJECT_DIR . "/upload";

function createDir($dirName) {
    $dir = PROJECT_DIR . DIRECTORY_SEPARATOR . 'globe' . DIRECTORY_SEPARATOR . $dirName;

    mkdir($dir);
    sleep(1);

    if (is_dir($dir)) {
        return array('state' => true, 'msg' => $dir);
    } else {
        return array('state' => false, 'msg' => 'Nao foi possivel criar o diretorio');
    }
}

function moveFile($fileName, $dir) {
    $filePathOld = PROJECT_DIR . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $fileName;
    $filePathNew = $dir . DIRECTORY_SEPARATOR . $fileName;

    if (is_file($filePathOld)) {
        if (is_dir($dir)) {
            
            rename($filePathOld, $filePathNew);

            if (is_file($filePathNew)) {
                return array('state' => true, 'msg' => $filePathNew);
            } else {
                return array('state' => false, 'msg' => 'O arquivo não foi copiado');
            }
        } else {
            return array('state' => false, 'msg' => "Diretorio de destino nao existe.");
        }
    } else {
        return array('state' => false, 'msg' => "Arquivo de origem nao existe");
    }
}

function createSphere($panels, $circumference, $filePath) {
    $path = substr($filePath, 0, strrpos($filePath,DIRECTORY_SEPARATOR));
    
    $sphereSlicer = 'cd ' . $path . ' && /opt/sphere-slicer/sphere-slicer.pl %panels% %circumference% %filePath%';
   
    $command = str_replace(array('%panels%', '%circumference%', '%filePath%'), array($panels, $circumference, $filePath), $sphereSlicer);
    
    $output .= shell_exec($command);
    echo $output;
}

$files = array_diff(scandir(DIR_UPLOAD), array('..', '.'));
$queue = array();

if (count($files) > 0) {
    foreach ($files as $key => $fileName) {

        $dirName = date('YmdHis');

        $dirObj = createDir($dirName);

        if ($dirObj['state'] == true) {
            $fileObj = moveFile($fileName, $dirObj['msg']);
            
            if ($fileObj['state'] == true) {
                $queue[] = $fileObj['msg'];
            } else {
                echo $fileObj['msg'];
            }
        } else {
            echo $dirObj['msg'];
        }
    }
    
    foreach ($queue as $key => $value) {
        createSphere(16, 4096, $value);
    }
}