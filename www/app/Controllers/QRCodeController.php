<?php

namespace App\Controllers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Lib\AES_256;

class QRCodeController
{

    private function generate(string $text, int $size = 200, int $margin = 10, string $labelText = "", int $labelSize = 12, string $response = "image", string $key = "")
    {
        try {
            if (!empty($key)) {
                $aes256 = new AES_256();
                $aes256->setKey($key);
                $text = $aes256->encrypt_cbc($text);
            }
            if (strlen($text) > 1200) {
                throw new \Exception("The maximum amount of characters is 1200 without encryption or 300 characters with encryption.");
            }
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
                throw new \Exception("Could not generate QRCode.");
            }
            if ($response === "image") {
                header('Content-Type: ' . $result->getMimeType());
                header('Content-disposition: inline; filename="image.png"');
                echo $result->getString();
                die;
            }
            if ($response === "string") {
                return $result->getDataUri();
            }
        } catch (\Throwable $th) {
            return "Error: " . $th->getMessage();
        }
    }

    public function create()
    {
        // Params REQ or JSON
        $text = (string) request()->get("text");
        $size = (int) request()->get("size", 200);
        $margin = (int) request()->get("margin", 10);
        $labelText = (string) request()->get("labelText", "");
        $labelSize = (int) request()->get("labelSize", 12);
        $response = (string) request()->get("response", "image");// image | string
        $key = (string) request()->get("key", "");
        return $this->generate($text, $size, $margin, $labelText, $labelSize, $response, $key);
    }

    public function read()
    {
        $response = ["text" => "", "error" => null];
        $text = (string) request()->get("text", "");
        $key = (string) request()->get("key", "");
        $file = request()->get("file");
        try {
            if ($text === "" && is_array($file) && count($file) === 1 && ($file = $file[0]) !== null && $file["type"] === "image/png" && $file["error"] === 0 && $file["size"] > 0) {
                $qrcode = new \Zxing\QrReader($file["tmp_name"]);
                $text = $qrcode->text();
                $response["text"] = $text;
            } else if ($text === "") {
                dd($file);
            }
            if ($key !== "" && $text !== "") {
                $aes256 = new AES_256();
                $aes256->setKey($key);
                $text2 = $aes256->decrypt_cbc($text);
                if ($text2 !== "") {
                    $text = $text2;
                    $response["text"] = $text;
                } else {
                    throw new \Exception("Unable to perform decryption.");
                }
            }
        } catch (\Throwable $th) {
            $response["error"] = $th->getMessage();
        }
        return $response;
    }
    
}
