<?php 

    set_time_limit(0);

    class GeneradorDB
    {
        private $maxWidth;
        private $maxHeight;
        private $minWidth;
        private $minHeight;

        function __construct()
        {
            $this->maxWidth  = 100;
            $this->maxHeight = 100;

            $this->minWidth  = 20;
            $this->minHeight = 20;
        }

        /**
         * Split and cut a part of one image
         * 
         * @param  [GD Resource]  $source    [Pointer of the GD image]
         * @param  [&Array]       &$dest     [Array in which the result will be added]
         * @param  string         $pieceName [The label of the Piece to cut]
         * @param  integer        $x1        [The begin X coordinate of the Image]
         * @param  integer        $y1        [The begin Y coordinate of the Image]
         * @param  integer        $x2        [The last  X coordinate of the Image]
         * @param  integer        $y2        [The last  Y coordinate of the Image]
         * @param  integer        $grade     [The Minimum Weight of the Y axes for know that is a word]
         */
        function shortcut($source = null, &$dest = null, $pieceName = "", $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $grade = 5)
        {
            if ($source != null && $dest !== null)
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

                        $dest[] = array("attr" => $pieceName, "img" => $piece, "text" => "");
                        imagedestroy($pieceTemp);
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
        function normalizeImage($source = null)
        {
            if ($source !== null)
            {
                $w = imagesx($source);
                $h = imagesy($source);

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
                        $p1 = str_pad(strval($x), 5, "0", STR_PAD_LEFT);
                        $p2 = str_pad(strval($y), 5, "0", STR_PAD_LEFT);

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
                        else if (imagecolorat($source, $x, $y) > 0)
                        {
                            $arrayInter[$y] = intval(0);
                        }
                        else
                        {
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

                for ($x = 0; $x < $this->maxWidth; $x++)
                { 
                    for ($y = 0 ; $y < $this->maxWidth; $y++)
                    { 
                        $array1D[] = $array2D[$y][$x];
                    }
                }

                return $array1D;
            }
            else
            {
                //echo "No picture for normalize";
                return null;
            }
        }

        function matrixToFile($matrix = null, $clase = "0")
        {
            if ($matrix !== null)
            {
                $link = fopen("basedatos$clase.csv", "a+");

                for ($i = 0; $i < sizeof($matrix); $i++)
                {
                    fwrite($link, $matrix[$i].",");
                }

                fwrite($link, $clase);
                fwrite($link, "\n");
                fclose($link);
            }
        }

        function createDB()
        {
            $rect = imagecreatefrompng("./rectangle.png");
            $squt = imagecreatefrompng("./square.png");
            $tran = imagecreatefrompng("./triangle.png");

            for ($i = $this->minWidth; $i < $this->maxWidth; $i++)
            {
                for ($j = 0; $j < 360; $j+=10)
                {
                    $color = imagecolorallocate($rect, 255, 255, 255);

                    $arrayData = array();
                    $temporal  = imagerotate($rect, $j, $color);
                    $temporal2 = imagescale($temporal, $i);
                    $this->shortcut($temporal2, $arrayData, "Rect", 0, 0, $i, $i, 1);

                    $matrix = $this->normalizeImage($arrayData[0]["img"]);
                    $this->matrixToFile($matrix, "1");

                    imagedestroy($temporal);
                    imagedestroy($temporal2);
                }
            }

            for ($i = $this->minWidth; $i < $this->maxWidth; $i++)
            {
                for ($j = 0; $j < 360; $j+=10)
                {
                    $color = imagecolorallocate($squt, 255, 255, 255);

                    $arrayData = array();
                    $temporal  = imagerotate($squt, $j, $color);
                    $temporal2 = imagescale($temporal, $i);
                    $this->shortcut($temporal2, $arrayData, "Sqt", 0, 0, $i, $i, 1);

                    $matrix = $this->normalizeImage($arrayData[0]["img"]);
                    $this->matrixToFile($matrix, "2");

                    imagedestroy($temporal);
                    imagedestroy($temporal2);
                }
            }

            for ($i = $this->minWidth; $i < $this->maxWidth; $i++)
            {
                for ($j = 0; $j < 360; $j+=10)
                {
                    $color = imagecolorallocate($tran, 255, 255, 255);

                    $arrayData = array();
                    $temporal  = imagerotate($tran, $j, $color);
                    $temporal2 = imagescale($temporal, $i);
                    $this->shortcut($temporal2, $arrayData, "tran", 0, 0, $i, $i, 1);

                    $matrix = $this->normalizeImage($arrayData[0]["img"]);
                    $this->matrixToFile($matrix, "3");

                    imagedestroy($temporal);
                    imagedestroy($temporal2);
                }
            }

            imagedestroy($rect);
            imagedestroy($squt);
            imagedestroy($tran);
        }

    }

    $gen = new GeneradorDB();
    $gen->createDB();

 ?>

