<?php

$mensagem = "";

if(isset($_FILES['panorama'])){
    $filename = $_FILES['panorama']['tmp_name'];
    $destination = "/var/www/html/sphere/upload/" . $_FILES['panorama']['name'];
    
    if(move_uploaded_file($filename, $destination)){
        $mensagem = "Arquivo enviado com sucesso!";
    } else {
        $mensagem = "Não foi possível salvar o arquivo (" . $_FILES['panorama']['error'] . ").";
    }
}

?>
<html lang="pt_BR">
    <head>
        <title>Sphere</title>
        
        <style>
            table {
                border-collapse: collapse;
            }

            table, td {
                border: 1px solid black;
            }
            
            td {
                padding: 10px 40px 10px 10px;
            }
        </style>
    </head>
    <body>
        <?php
            echo $mensagem . "\n";
        ?>

        <h1>Sphere</h1>

        <form action="index.php" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000"

            <label>Arquivo</label>
            <input type="file" name="panorama">
            
            <label>Fatias</label>
            <input type="number" name="panorama">
            
            <label>Resolução (pixels)</label>
            <input type="number" name="panorama">
            
            <input type="submit" value="Enviar arquivo" name="enviar">
        </form>
        
        <table>
            <thead>
                <tr>
                    <td>Panorama</td>
                    <td>Arquivos</td>
                </tr>
            </thead>
            <tbody>                
            <?php
                $path = "/var/www/html/sphere/globe";
                
                $panoramas = array();
                
                $files = array_diff(scandir($path),array('..', '.'));
                
                if (count($files) > 0) {                    
                    foreach ($files as $key => $dirName) {
                        $dirPath = $path . DIRECTORY_SEPARATOR . $dirName;

                        $slices = array_diff(scandir($dirPath),array('..', '.'));
                        
                        $panels = array();
                        
                        $panoramaName = "";
                        
                        foreach ($slices as $slice => $fileName) {
                            if (substr($fileName, 0, 5) == 'panel') {
                                $panels[] = array('name' => $fileName, 'fileUrl' => 'globe/' . $dirName . '/' . $fileName);
                            } else {
                                $panoramaName = $fileName;
                            }
                            
                        }
                        
                        $panoramas[] = array('name' => $panoramaName, 'panels' => $panels);
                    }

                    for ($i=0;$i<count($panoramas);$i++) {
                        echo "<tr>\n";
                        echo "<td>" . $panoramas[$i]['name'] . "</td>\n";
                        echo "<td><ul>";
                        
                        for ($j=0;$j<count($panoramas[$i]['panels']);$j++) {
                            echo "<li><a href='" . $panoramas[$i]['panels'][$j]['fileUrl'] . "'>" . $panoramas[$i]['panels'][$j]['name'] . "</a></li>\n";
                        }
                        
                        echo "</ul></td>";
                        echo "</tr>\n";
                    }
                }
            ?>
        </table>
        
        <h1>Panorama</h1>
        
        <table>
            <thead>
                <tr>
                    <td>Panorama enviado</td>
                </tr>
            </thead>
            <tbody>
            <?php
                $path = "/var/www/html/sphere/upload";
                
                $panoramas = array();
                
                $files = array_diff(scandir($path),array('..', '.'));
                
                if (count($files) > 0) {                    
                    foreach ($files as $key => $fileName) {
                        $dirPath = $path . DIRECTORY_SEPARATOR . $dirName;
                        echo "<tr>\n";
                        echo "<td>" . $fileName . "</td>\n";
                        echo "</tr>\n";
                    }
                }
            ?>
        </table>
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>
