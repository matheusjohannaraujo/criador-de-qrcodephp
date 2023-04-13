<?php

namespace App\Controllers;

class QRCodeController
{

    public function create() :void
    {
        $text = input_json("text");
        $redundancy = input_json("redundancy", 1);
        $pixelsize = input_json("pixelsize", 8);
        $mimetype = input_json("mimetype", "jpeg");
        $filename = input_json("filename", md5($text));
        $this->generateImage($text, $redundancy, $pixelsize, $mimetype, $filename);
    }

    public function generateImage(
        string $text,
        int $redundancy,
        int $pixelsize,
        string $mimetype,
        string $filename
    ) :void {
        //dumpd($text, $redundancy, $pixelsize, $mimetype, $filename);
        if ($text == null || strlen($text) > 2000) {
            exit("TEXT <= 2000 caracteres");
        }
        switch ($redundancy) {
            case 1:
                $redundancy = "L";
                break;
            case 2:
                $redundancy = "M";
                break;
            case 3:
                $redundancy = "Q";
                break;
            case 4:
                $redundancy = "H";
                break;
            default:
                $redundancy = "L";
        }
        if ($pixelsize < 4 || $pixelsize > 48) {
            $pixelsize = 8;
        }
        $mimetype = strtolower($mimetype);
        $filename = $filename . "." . $mimetype;
        switch ($pixelsize) {
            case "jpeg":
                $mimetype = "J";
                break;
            case "png":
                $mimetype = "P";
                break;
            default:
                $mimetype = "J";
        }
        $str = view("qr_img_0.50j/php/qr_img", [
            "d" => $text,
            "e" => $redundancy,
            "s" => $pixelsize,
            "t" => $mimetype,
        ]);
        output()
            ->name($filename)
            ->content($str)
            ->go();
    }

}
