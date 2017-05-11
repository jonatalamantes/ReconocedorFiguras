<?php 

    set_time_limit(0);

    class ImageAnalizator
    {
        private $maxWidth;
        private $maxHeight;

        function __construct()
        {
            $this->maxWidth  = 250;
            $this->maxHeight = 250;
        }

        /**
         * Transform the main image to a Binary Image with pixels of 0 and 1
         */
        function blackConstrat(&$source)
        {
            if ($source == null)
            {
                echo "Error al cargar la imagen, no existe el puntero";
                die;
            }

            $black = imagecolorallocate($source, 0, 0, 0);
            $white = imagecolorallocate($source, 255, 255, 255);

            for ($i = 0; $i < $this->actualWidth; $i++)
            {
                for ($j = 0; $j < $this->actualHeight; $j++)
                {
                    $val = imagecolorat($source, $i, $j);

                    $r = floor($val/(256*256));
                    $g = floor($val/(256))%256;
                    $b = $val%256;

                    if ($r < 150 && $g < 150 & $b < 150)
                    {
                        imagesetpixel($source, $i, $j, $black);
                    }
                    else
                    {
                        imagesetpixel($source,  $i, $j, $white);
                    }
                }       
            }
        }

        /**
         * Split and cut a part of one image
         * 
         * @param  [GD Resource]  $source    [Pointer of the GD image]
         * @param  integer        $x1        [The begin X coordinate of the Image]
         * @param  integer        $y1        [The begin Y coordinate of the Image]
         * @param  integer        $x2        [The last  X coordinate of the Image]
         * @param  integer        $y2        [The last  Y coordinate of the Image]
         * @param  integer        $grade     [The Minimum Weight of the Y axes for know that is a word]
         */
        function shortcut($source = null, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $grade = 5)
        {
            if ($source != null)
            {
                $incX = abs($x1-$x2);
                $incY = abs($y1-$y2);

                if ($incX != 0 && $incY != 0)
                {
                    $pieceTemp = imagecreatetruecolor($incX, $incY);
                    imagecopy($pieceTemp, $source, 0, 0, $x1, $y1, $incX, $incY);

                    //Search the upper limit
                    $upperLimit = 0;
                    for ($y = 0; $y < $incY; $y++)
                    {
                        $contador = 0;
                        for ($x = 0; $x < $incX; $x++)
                        {
                            $val = imagecolorat($pieceTemp, $x, $y);

                            if ($val == 0)
                            {
                                $contador++;
                            }
                        }

                        if ($contador >= $grade)
                        {
                            $upperLimit = $y;
                            break;
                        }
                    }

                    //Search the lower limit
                    $lowerLimit = 0;
                    for ($y = $incY-1; $y >= 0; $y--)
                    {
                        $contador = 0;
                        for ($x = 0; $x < $incX; $x++)
                        {
                            $val = imagecolorat($pieceTemp, $x, $y);

                            if ($val == 0)
                            {
                                $contador++;
                            }
                        }

                        if ($contador >= $grade)
                        {
                            $lowerLimit = $y;
                            break;
                        }
                    }

                    //Search the left limit
                    $leftLimit = 0;
                    for ($x = 0; $x < $incX; $x++)
                    {
                        $contador = 0;
                        for ($y = 0; $y < $incY; $y++)
                        {
                            $val = imagecolorat($pieceTemp, $x, $y);

                            if ($val == 0)
                            {
                                $contador++;
                            }
                        }

                        if ($contador >= $grade)
                        {
                            $leftLimit = $x;
                            break;
                        }
                    }

                    //Search the left limit
                    $rightLimit = 0;
                    for ($x = $incX-1; $x >= 0; $x--)
                    {
                        $contador = 0;
                        for ($y = 0; $y < $incY; $y++)
                        {
                            $val = imagecolorat($pieceTemp, $x, $y);

                            if ($val == 0)
                            {
                                $contador++;
                            }
                        }

                        if ($contador >= $grade)
                        {
                            $rightLimit = $x;
                            break;
                        }
                    }

                    $incX = abs($leftLimit-$rightLimit);
                    $incY = abs($upperLimit-$lowerLimit);

                    //Create the image from the limits
                    if ($incX != 0 && $incY != 0)
                    {
                        $piece = imagecreatetruecolor($incX, $incY);
                        imagecopy($piece, $pieceTemp, 0, 0, $leftLimit, $upperLimit, $incX, $incY);

                        imagedestroy($pieceTemp);
                        return $piece;
                    }
                }
            }
        }

        /**
         * Take one picture pointer and transform into a array with 0 and 1.
         * 
         * @param  [GD Source] $source [The pointer to the image for transform]
         * @return [Array]             [The matrinx of 0 and 1]
         */
        function normalizeImage($source = null, $export = null)
        {
            if ($source !== null)
            {
                $w = imagesx($source);
                $h = imagesy($source);

                $link = null;
                $date = null;

                $array2D  = array();
                $oddUpper = true;
                $oddLeft  = true;
                $array1D  = array();

                //Super Arreglo normalizado inicial
                for ($x = 0; $x < $this->maxWidth; $x++)
                {
                    $arrayInter = array();
                    for ($y = 0; $y < $this->maxHeight; $y++)
                    {
                        $rgb = imagecolorat($source, $x, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;

                        if ($x >= $w)
                        {
                            $arrayInter[] = 0;
                        }
                        else if ($y >= $h)
                        {
                            if ($oddLeft)
                            {
                                array_unshift($arrayInter, 0);
                            }
                            else
                            {
                                $arrayInter[] = intval(0);
                            }

                            $oddLeft = !$oddLeft;
                        }
                        else if ($r > 200 && $g > 200 && $b > 200) //blanco
                        {
                            //echo "0";
                            $arrayInter[$y] = intval(0);
                        }
                        else
                        {
                            //echo "1";
                            $arrayInter[$y] = intval(1);
                        }
                    }

                    if ($x >= $w)
                    {
                        if ($oddUpper)
                        {
                            array_unshift($array2D, $arrayInter);
                        }
                        else
                        {
                            $array2D[] = $arrayInter;
                        }

                        $oddUpper = !$oddUpper;
                    }
                    else
                    {
                        $array2D[] = $arrayInter;
                    }
                }

                if ($export !== null)
                {
                    $link = fopen($export, "w");
                    
                }

                for ($x = 0; $x < $this->maxWidth; $x++)
                { 
                    for ($y = 0 ; $y < $this->maxHeight; $y++)
                    { 
                        if ($export !== null)
                        {
                            fwrite($link, $array2D[$y][$x]);
                        }

                        $array1D[] = $array2D[$y][$x];
                    }

                    if ($export !== null)
                    {
                        fwrite($link, "\n");
                    }
                }

                if ($export !== null)
                {
                    fclose($link);
                }

                return $array1D;
            }
            else
            {
                //echo "No picture for normalize";
                return null;
            }
        }

        function createImage($byteString = "")
        {
            
        }

        function createDB()
        {
            $rect = imagecreatefrompng("db/rectangle.png");
            $squt = imagecreatefrompng("db/square.png");
            $tran = imagecreatefrompng("db/triangle.png");

            $this->blackConstrat($rect);
            $this->blackConstrat($squt);
            $this->blackConstrat($tran);

            $instance = 1;

            for ($j = 0; $j < 180; $j+=5)
            {
                $array = array();
                $color = imagecolorallocate($rect, 255, 255, 255);

                $temporal  = imagerotate($rect, $j, $color);
                $temporal2 = $this->shortcut($temporal, 0, 0, imagesx($temporal), imagesy($temporal), 1);
                
                if (imagesx($temporal2) > imagesy($temporal2))
                {
                    $temporal1 = imagescale($temporal2, $this->maxWidth);
                }
                else
                {
                    $ratio = imagesx($temporal2)/imagesy($temporal2);
                    $temporal1 = imagescale($temporal2, $ratio*$this->maxWidth);
                }

                $matrix = $this->normalizeImage($temporal1, "db/Rect/$instance");

                imagedestroy($temporal);
                imagedestroy($temporal2);
                imagedestroy($temporal1);
                $instance++;
            }

            $instance = 1;

            for ($j = 0; $j < 90; $j+=5)
            {
                $array = array();
                $color = imagecolorallocate($squt, 255, 255, 255);

                $temporal  = imagerotate($squt, $j, $color);
                $temporal2 = $this->shortcut($temporal, 0, 0, imagesx($temporal), imagesy($temporal), 1);
                
                if (imagesx($temporal2) > imagesy($temporal2))
                {
                    $temporal1 = imagescale($temporal2, $this->maxWidth);
                }
                else
                {
                    $ratio = imagesx($temporal2)/imagesy($temporal2);
                    $temporal1 = imagescale($temporal2, $ratio*$this->maxWidth);
                }

                $matrix = $this->normalizeImage($temporal1, "db/Sqt/$instance");

                imagedestroy($temporal);
                imagedestroy($temporal2);
                imagedestroy($temporal1);
                $instance++;
            }

            $instance = 1;
            for ($j = 0; $j < 120; $j+=5)
            {
                $array = array();
                $color = imagecolorallocate($tran, 255, 255, 255);

                $temporal  = imagerotate($tran, $j, $color);
                $temporal2 = $this->shortcut($temporal, 0, 0, imagesx($temporal), imagesy($temporal), 1);
                
                if (imagesx($temporal2) > imagesy($temporal2))
                {
                    $temporal1 = imagescale($temporal2, $this->maxWidth);
                }
                else
                {
                    $ratio = imagesx($temporal2)/imagesy($temporal2);
                    $temporal1 = imagescale($temporal2, $ratio*$this->maxWidth);
                }

                $matrix = $this->normalizeImage($temporal1, "db/Tran/$instance");

                imagedestroy($temporal);
                imagedestroy($temporal2);
                imagedestroy($temporal1);
                $instance++;
            }


            imagedestroy($rect);
            imagedestroy($squt);
            imagedestroy($tran);
        }

    }

 ?>

