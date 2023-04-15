<?php

namespace App\Controllers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeController
{

    private function create(string $text, int $size = 200, int $margin = 10, string $labelText = null, int $labelSize = 12, string $response = "image")
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($text)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin($margin)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin());
            /*->logoPath(__DIR__.'/logo.png')*/
        if ($labelText !== null && $labelText !== "") {
            $result = $result->labelText($labelText)
                ->labelFont(new NotoSans($labelSize))
                ->labelAlignment(new LabelAlignmentCenter());
        }
        $result = $result
            ->validateResult(false)
            ->build();

        if ($result === null) {
            return "Falha ao gerar QRCode";
        }

        if ($response == "image") {
            header('Content-Type: ' . $result->getMimeType());
            echo $result->getString();
            die;
        }

        if ($response == "string") {
            return $result->getDataUri();
        }
    }

    public function json()
    {
        $text = (string) input_json("text");
        $size = (int) input_json("size", 200);
        $margin = (int) input_json("margin", 10);
        $labelText = (string) input_json("labelText", null);
        $labelSize = (int) input_json("labelSize", 12);
        $response = (string) input_json("response", "image");// image | string
        return $this->create($text, $size, $margin, $labelText, $labelSize, $response);
    }

    public function params()
    {
        $text = (string) request()->get("text");
        $size = (int) request()->get("size", 200);
        $margin = (int) request()->get("margin", 10);
        $labelText = (string) request()->get("labelText", null);
        $labelSize = (int) request()->get("labelSize", 12);
        $response = (string) request()->get("response", "image");// image | string
        return $this->create($text, $size, $margin, $labelText, $labelSize, $response);
    }
    
}
