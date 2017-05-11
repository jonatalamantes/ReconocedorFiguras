<?php 

    require_once(__DIR__."/ImageAnalizator.php");

    //Export the data to a file
    $data = $_POST["image"];

    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    file_put_contents('image.png', $data);

    //Read the file
    $an = new ImageAnalizator();
    
    //Remove the Alpha channel
    $img = imagecreatefrompng("image.png");
    $background = imagecolorallocate($img, 255,255,255);
    imagefill($img, 0, 0, $background);
    imagealphablending($img, true);
    imagesavealpha($img, true);

    //To Binary Image
    $an->blackConstrat($img);
    $temporal2 = $an->shortcut($img, 0, 0, imagesx($img), imagesy($img), 1);
    $temporal1 = imagescale($temporal2, $an->getMaxWidth(), $an->getMaxHeight());

    //Create the training File
    $an->normalizeImage($temporal1, "python-svm/input.dat");

    imagedestroy($temporal2);
    imagedestroy($temporal1);

    //Exec the python script
    $salida = array();
    $out = 0;

    exec("python python-svm/clasificar_figuras.py", $salida, $out);
    var_dump($salida);
    
    //Devolver el resultado de la clasificacion
    if ($out == 1)
    {
        echo "Rectangulo";
    }
    else if ($out == 2)
    {
        echo "Cuadrado";
    }
    else if ($out == 3)
    {
        echo "Triangulo";
    }

 ?>