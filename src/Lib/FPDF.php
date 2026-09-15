<?php

namespace ProyectoTFGRodrigo\Lib;

class FPDF
{
    private array $pages = [];
    private string $content = "";
    private float $x = 12;
    private float $y = 12;
    private float $fontSize = 11;
    private string $font = "F1";
    private string $textColor = "0 0 0 rg";
    private string $fillColor = "1 1 1 rg";
    private string $drawColor = "0 0 0 RG";
    private float $lineWidth = 0.2;
    private array $images = [];

    public function AddPage(): void
    {
        if ($this->content !== "") {
            $this->pages[] = $this->content;
        }

        $this->content = "";
        $this->x = 12;
        $this->y = 12;
    }

    public function SetFont(string $family, string $style = "", int $size = 11): void
    {
        $this->font = stripos($style, "B") !== false ? "F2" : "F1";
        $this->fontSize = $size;
    }

    public function SetTextColor(int $r, int $g = 0, int $b = 0): void
    {
        $this->textColor = $this->rgb($r, $g, $b) . " rg";
    }

    public function SetFillColor(int $r, int $g = 0, int $b = 0): void
    {
        $this->fillColor = $this->rgb($r, $g, $b) . " rg";
    }

    public function SetDrawColor(int $r, int $g = 0, int $b = 0): void
    {
        $this->drawColor = $this->rgb($r, $g, $b) . " RG";
    }

    public function SetLineWidth(float $width): void
    {
        $this->lineWidth = $width;
    }

    public function SetX(float $x): void
    {
        $this->x = $x;
    }

    public function SetY(float $y): void
    {
        $this->y = $y;
    }

    public function SetXY(float $x, float $y): void
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function Rect(float $x, float $y, float $w, float $h, string $style = ""): void
    {
        $this->drawRect($x, $y, $w, $h, strtoupper($style) === "F");
    }

    public function Image(string $file, float $x, float $y, float $w = 0, float $h = 0): void
    {
        if (!file_exists($file)) {
            return;
        }

        $imageKey = realpath($file) ?: $file;

        if (!isset($this->images[$imageKey])) {
            $this->images[$imageKey] = $this->parsePng($file);
        }

        $image = $this->images[$imageKey];

        if ($w <= 0 && $h <= 0) {
            $w = $image["width"] * 0.264583;
            $h = $image["height"] * 0.264583;
        } elseif ($w <= 0) {
            $w = $h * $image["width"] / $image["height"];
        } elseif ($h <= 0) {
            $h = $w * $image["height"] / $image["width"];
        }

        $this->content .= "q " . $this->pt($w) . " 0 0 " . $this->pt($h) . " "
            . $this->pt($x) . " " . $this->ptY($y + $h)
            . " cm /" . $image["name"] . " Do Q\n";
    }

    public function Ln(float $height = 6): void
    {
        $this->x = 12;
        $this->y += $height;
    }

    public function Cell(float $w, float $h = 6, string $txt = "", int|string $border = 0, int $ln = 0, string $align = "L", bool $fill = false): void
    {
        if ($fill) {
            $this->drawRect($this->x, $this->y, $w, $h, true);
        }

        if ((string) $border !== "0") {
            $this->drawRect($this->x, $this->y, $w, $h, false);
        }

        $textWidth = $this->getTextWidth($txt);
        $textX = $this->x + 2;

        if ($align === "R") {
            $textX = $this->x + $w - $textWidth - 2;
        } elseif ($align === "C") {
            $textX = $this->x + max(2, ($w - $textWidth) / 2);
        }

        $this->text($textX, $this->y + ($h * 0.68), $txt);

        if ($ln > 0) {
            $this->Ln($h);
        } else {
            $this->x += $w;
        }
    }

    public function Output(string $dest = "I", string $name = "documento.pdf"): string
    {
        if ($this->content !== "") {
            $this->pages[] = $this->content;
            $this->content = "";
        }

        $pdf = $this->buildPdf();

        if ($dest === "S") {
            return $pdf;
        }

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"" . basename($name) . "\"");
        header("Content-Length: " . strlen($pdf));
        echo $pdf;
        return "";
    }

    private function text(float $x, float $y, string $txt): void
    {
        $this->content .= "BT /{$this->font} {$this->fontSize} Tf {$this->textColor} "
            . $this->pt($x) . " " . $this->ptY($y) . " Td (" . $this->escape($txt) . ") Tj ET\n";
    }

    private function getTextWidth(string $txt): float
    {
        $txt = iconv("UTF-8", "Windows-1252//TRANSLIT", $txt);
        $width = 0;
        $wideChars = "ABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
        $narrowChars = ".,:;!|'iIl ";

        for ($i = 0; $i < strlen($txt); $i++) {
            $char = $txt[$i];

            if (strpos($wideChars, $char) !== false) {
                $width += 0.68;
            } elseif (strpos($narrowChars, $char) !== false) {
                $width += 0.28;
            } else {
                $width += 0.52;
            }
        }

        return $width * $this->fontSize * 0.36;
    }

    private function drawRect(float $x, float $y, float $w, float $h, bool $fill): void
    {
        $operator = $fill ? "f" : "S";
        $this->content .= "{$this->fillColor} {$this->drawColor} {$this->lineWidth} w "
            . $this->pt($x) . " " . $this->ptY($y + $h) . " "
            . $this->pt($w) . " " . $this->pt($h) . " re {$operator}\n";
    }

    private function buildPdf(): string
    {
        $objects = [];
        $pageRefs = [];
        $nextObject = 5;
        $imageResourceEntries = [];

        foreach ($this->images as $key => $image) {
            $imageObject = $nextObject++;
            $alphaObject = null;

            if ($image["alpha"] !== "") {
                $alphaObject = $nextObject++;
            }

            $this->images[$key]["object"] = $imageObject;
            $this->images[$key]["alphaObject"] = $alphaObject;
            $imageResourceEntries[] = "/" . $image["name"] . " {$imageObject} 0 R";

            $smask = $alphaObject ? " /SMask {$alphaObject} 0 R" : "";
            $objects[$imageObject] = "{$imageObject} 0 obj << /Type /XObject /Subtype /Image /Width {$image["width"]} /Height {$image["height"]} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /FlateDecode{$smask} /Length " . strlen($image["rgb"]) . " >> stream\n{$image["rgb"]}\nendstream endobj\n";

            if ($alphaObject) {
                $objects[$alphaObject] = "{$alphaObject} 0 obj << /Type /XObject /Subtype /Image /Width {$image["width"]} /Height {$image["height"]} /ColorSpace /DeviceGray /BitsPerComponent 8 /Filter /FlateDecode /Length " . strlen($image["alpha"]) . " >> stream\n{$image["alpha"]}\nendstream endobj\n";
            }
        }

        $xObjects = !empty($imageResourceEntries)
            ? " /XObject << " . implode(" ", $imageResourceEntries) . " >>"
            : "";

        foreach ($this->pages as $pageContent) {
            $pageObject = $nextObject++;
            $contentObject = $nextObject++;
            $pageRefs[] = "{$pageObject} 0 R";

            $objects[$pageObject] = "{$pageObject} 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Resources << /Font << /F1 3 0 R /F2 4 0 R >>{$xObjects} >> /Contents {$contentObject} 0 R >> endobj\n";
            $objects[$contentObject] = "{$contentObject} 0 obj << /Length " . strlen($pageContent) . " >> stream\n{$pageContent}endstream endobj\n";
        }

        $objects[1] = "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
        $objects[2] = "2 0 obj << /Type /Pages /Kids [" . implode(" ", $pageRefs) . "] /Count " . count($pageRefs) . " >> endobj\n";
        $objects[3] = "3 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n";
        $objects[4] = "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >> endobj\n";
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (max(array_keys($objects)) + 1) . "\n0000000000 65535 f \n";

        for ($i = 1; $i <= max(array_keys($objects)); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, "0", STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer << /Size " . (max(array_keys($objects)) + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefPosition}\n%%EOF";

        return $pdf;
    }

    private function pt(float $mm): string
    {
        return number_format($mm * 2.83465, 2, ".", "");
    }

    private function ptY(float $mm): string
    {
        return number_format(841.89 - ($mm * 2.83465), 2, ".", "");
    }

    private function rgb(int $r, int $g, int $b): string
    {
        return number_format($r / 255, 3, ".", "") . " " . number_format($g / 255, 3, ".", "") . " " . number_format($b / 255, 3, ".", "");
    }

    private function escape(string $txt): string
    {
        $txt = iconv("UTF-8", "Windows-1252//TRANSLIT", $txt);
        return str_replace(["\\", "(", ")"], ["\\\\", "\\(", "\\)"], $txt);
    }

    private function parsePng(string $file): array
    {
        $data = file_get_contents($file);
        $position = 8;
        $width = 0;
        $height = 0;
        $bitDepth = 0;
        $colorType = 0;
        $idat = "";

        while ($position < strlen($data)) {
            $length = unpack("N", substr($data, $position, 4))[1];
            $type = substr($data, $position + 4, 4);
            $chunk = substr($data, $position + 8, $length);
            $position += 12 + $length;

            if ($type === "IHDR") {
                $header = unpack("Nwidth/Nheight/CbitDepth/CcolorType", $chunk);
                $width = $header["width"];
                $height = $header["height"];
                $bitDepth = $header["bitDepth"];
                $colorType = $header["colorType"];
            } elseif ($type === "IDAT") {
                $idat .= $chunk;
            } elseif ($type === "IEND") {
                break;
            }
        }

        if ($bitDepth !== 8 || !in_array($colorType, [2, 6], true)) {
            return $this->emptyImageData($width, $height);
        }

        $channels = $colorType === 6 ? 4 : 3;
        $raw = gzuncompress($idat);
        $stride = $width * $channels;
        $previous = array_fill(0, $stride, 0);
        $offset = 0;
        $rgb = "";
        $alpha = "";

        for ($y = 0; $y < $height; $y++) {
            $filter = ord($raw[$offset]);
            $offset++;
            $scanline = array_values(unpack("C*", substr($raw, $offset, $stride)));
            $offset += $stride;
            $current = $this->unfilterPngScanline($scanline, $previous, $filter, $channels);

            for ($x = 0; $x < $width; $x++) {
                $base = $x * $channels;
                $rgb .= chr($current[$base]) . chr($current[$base + 1]) . chr($current[$base + 2]);

                if ($channels === 4) {
                    $alpha .= chr($current[$base + 3]);
                }
            }

            $previous = $current;
        }

        return [
            "name" => "Im" . (count($this->images) + 1),
            "width" => $width,
            "height" => $height,
            "rgb" => gzcompress($rgb),
            "alpha" => $alpha !== "" ? gzcompress($alpha) : "",
        ];
    }

    private function unfilterPngScanline(array $scanline, array $previous, int $filter, int $bpp): array
    {
        $result = [];
        $length = count($scanline);

        for ($i = 0; $i < $length; $i++) {
            $left = $i >= $bpp ? $result[$i - $bpp] : 0;
            $up = $previous[$i] ?? 0;
            $upLeft = $i >= $bpp ? ($previous[$i - $bpp] ?? 0) : 0;

            if ($filter === 0) {
                $value = $scanline[$i];
            } elseif ($filter === 1) {
                $value = $scanline[$i] + $left;
            } elseif ($filter === 2) {
                $value = $scanline[$i] + $up;
            } elseif ($filter === 3) {
                $value = $scanline[$i] + floor(($left + $up) / 2);
            } else {
                $value = $scanline[$i] + $this->paeth($left, $up, $upLeft);
            }

            $result[$i] = $value & 255;
        }

        return $result;
    }

    private function paeth(int $a, int $b, int $c): int
    {
        $p = $a + $b - $c;
        $pa = abs($p - $a);
        $pb = abs($p - $b);
        $pc = abs($p - $c);

        if ($pa <= $pb && $pa <= $pc) return $a;
        if ($pb <= $pc) return $b;

        return $c;
    }

    private function emptyImageData(int $width, int $height): array
    {
        return [
            "name" => "Im" . (count($this->images) + 1),
            "width" => max(1, $width),
            "height" => max(1, $height),
            "rgb" => gzcompress(str_repeat("\xFF\xFF\xFF", max(1, $width * $height))),
            "alpha" => "",
        ];
    }
}
