<?php
// 颜色模型转换
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 颜色模型
    $Model = (int)$Auth->RangeIntParameters('Model', 1, 6);
    // 色值
    $Value = (string)$Auth->StringParameters('Value');
}

if ($ValidRequest) {
    $Invalid = true;
    switch ($Model) {
        case 1:
            $RGB = HexToRGB($Value);
            break;
        case 2:
            $RGB = explode(',', $Value);
            break;
        case 3:
            $HSVNo = explode(',', $Value);
            $RGB = HSVToRGB($HSVNo[0], $HSVNo[1], $HSVNo[2]);
            break;
        case 4:
            $HSLNo = explode(',', $Value);
            $RGB = HSLToRGB($HSLNo[0], $HSLNo[1], $HSLNo[2]);
            break;
        case 5:
            $CMYKNo = explode(',', $Value);
            $RGB = CMYKToRGB($CMYKNo[0], $CMYKNo[1], $CMYKNo[2], $CMYKNo[3]);
            break;
        case 6:
            $Response['Message'] = 'HSI模型转RGB模型的误差极大,交给GPT也无济于事,慎用吧';
            $HSINo = explode(',', $Value);
            $RGB = HSIToRGB($HSINo[0], $HSINo[1], $HSINo[2]);
            break;
    }
    $HEXNo = RGBToHex($RGB[0], $RGB[1], $RGB[2], false);
    $HEXYes = RGBToHex($RGB[0], $RGB[1], $RGB[2], true);
    $HSVNo = RGBToHSV($RGB[0], $RGB[1], $RGB[2], false);
    $HSVYes = RGBToHSV($RGB[0], $RGB[1], $RGB[2], true);
    $HSLNo = RGBToHSL($RGB[0], $RGB[1], $RGB[2], false);
    $HSLYes = RGBToHSL($RGB[0], $RGB[1], $RGB[2], true);
    $CMYKNo = RGBToCMYK($RGB[0], $RGB[1], $RGB[2], false);
    $CMYKYes = RGBToCMYK($RGB[0], $RGB[1], $RGB[2], true);
    $HSINo = RGBToHSI($RGB[0], $RGB[1], $RGB[2], false);
    $HSIYes = RGBToHSI($RGB[0], $RGB[1], $RGB[2], true);
    $Response['Data'] = [
        'HEX' => [$HEXNo, $HEXYes],
        'RGB' => implode(',', $RGB),
        'HSV' => [implode(',', $HSVNo), implode(',', $HSVYes)],
        'HSL' => [implode(',', $HSLNo), implode(',', $HSLYes)],
        'CMYK' => [implode(',', $CMYKNo), implode(',', $CMYKYes)],
        'HSI' => [implode(',', $HSINo), implode(',', $HSIYes)],
    ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function HexToRGB(string $Hex = 'ffffff'): array
{
    return [
        hexdec(substr($Hex, 0, 2)),
        hexdec(substr($Hex, 2, 2)),
        hexdec(substr($Hex, 4, 2))
    ];
}

function RGBToHex(int $R = 255, int $G = 255, int $B = 255, bool $Type): string
{
    if ($Type) {
        return '#' . sprintf('%02x%02x%02x', $R, $G, $B);
    } else {
        return sprintf('%02x%02x%02x', $R, $G, $B);
    }
}

function RGBToHSV(int $R = 255, int $G = 255, int $B = 255, bool $Type): array
{
    $R /= 255;
    $G /= 255;
    $B /= 255;
    $MAX = max($R, $G, $B);
    $MIN = min($R, $G, $B);
    $Delta = $MAX - $MIN;
    if ($MAX === $MIN) {
        $H = 0;
    } elseif ($MAX === $R) {
        $H = 60 * (($G - $B) / $Delta);
    } elseif ($MAX === $G) {
        $H = 60 * (($B - $R) / $Delta + 2);
    } elseif ($MAX === $B) {
        $H = 60 * (($R - $G) / $Delta + 4);
    }
    $S = ($MAX != 0) ? ($MAX - $MIN) / $MAX : 0;
    $V = $MAX;
    if ($Type) {
        return array(
            round($H) . '°',
            round($S * 100, 2) . '%',
            round($V * 100, 2) . '%'
        );
    } else {
        return array(
            round($H),
            round($S * 100, 2),
            round($V * 100, 2)
        );
    }
}

function HSVToRGB(int $H = 0, int $S = 0, int $V = 100): array
{
    $H = ($H % 360) / 360;
    $S /= 100;
    $V /= 100;
    $I = floor($H * 6);
    $F = $H * 6 - $I;
    $P = $V * (1 - $S);
    $Q = $V * (1 - $F * $S);
    $T = $V * (1 - (1 - $F) * $S);
    switch ($I % 6) {
        case 0:
            $R = $V;
            $G = $T;
            $B = $P;
            break;
        case 1:
            $R = $Q;
            $G = $V;
            $B = $P;
            break;
        case 2:
            $R = $P;
            $G = $V;
            $B = $T;
            break;
        case 3:
            $R = $P;
            $G = $Q;
            $B = $V;
            break;
        case 4:
            $R = $T;
            $G = $P;
            $B = $V;
            break;
        case 5:
            $R = $V;
            $G = $P;
            $B = $Q;
            break;
    }
    return array(
        round($R * 255),
        round($G * 255),
        round($B * 255)
    );
}

function RGBToHSL(int $R = 255, int $G = 255, int $B = 255, bool $Type): array
{
    $R /= 255;
    $G /= 255;
    $B /= 255;
    $MAX = max($R, $G, $B);
    $MIN = min($R, $G, $B);
    $Delta = $MAX - $MIN;
    if ($MAX === $MIN) {
        $H = 0;
    } elseif ($MAX === $R) {
        $H = 60 * (($G - $B) / $Delta);
    } elseif ($MAX === $G) {
        $H = 60 * (($B - $R) / $Delta + 2);
    } elseif ($MAX === $B) {
        $H = 60 * (($R - $G) / $Delta + 4);
    }
    $L = ($MAX + $MIN) / 2;
    if ($MAX === 0) {
        $S = 0;
    } else {
        $S = (1 - abs(2 * $L - 1)) / $Delta;
    }
    if ($Type) {
        return array(
            round($H) . '°',
            round($S * 100, 2) . '%',
            round($L * 100, 2) . '%'
        );
    } else {
        return array(
            round($H),
            round($S * 100, 2),
            round($L * 100, 2)
        );
    }
}

function HSLToRGB(int $H = 0, int $S = 0, int $L = 100): array
{
    $H /= 360;
    $S /= 100;
    $L /= 100;
    if ($S == 0) {
        $R = $L;
        $G = $L;
        $B = $L;
    } else {
        function Hue2RGB($v1, $v2, $vH)
        {
            if ($vH < 0) $vH += 1;
            if ($vH > 1) $vH -= 1;
            if ((6 * $vH) < 1) return ($v1 + ($v2 - $v1) * 6 * $vH);
            if ((2 * $vH) < 1) return $v2;
            if ((3 * $vH) < 2) return ($v1 + ($v2 - $v1) * ((2 / 3 - $vH) * 6));
            return $v1;
        }
        if ($L < 0.5) {
            $v2 = $L * (1 + $S);
        } else {
            $v2 = ($L + $S) - ($S * $L);
        }
        $v1 = 2 * $L - $v2;
        $R = Hue2RGB($v1, $v2, $H + (1 / 3));
        $G = Hue2RGB($v1, $v2, $H);
        $B = Hue2RGB($v1, $v2, $H - (1 / 3));
    }
    return [
        round($R * 255),
        round($G * 255),
        round($B * 255)
    ];
}

function RGBToCMYK(int $R = 255, int $G = 255, int $B = 255, bool $Type): array
{
    $R /= 255;
    $G /= 255;
    $B /= 255;
    $K = 1 - max($R, $G, $B);
    $C = (1 - $R - $K) / (1 - $K);
    $M = (1 - $G - $K) / (1 - $K);
    $Y = (1 - $B - $K) / (1 - $K);
    $C *= 100;
    $M *= 100;
    $Y *= 100;
    $K *= 100;
    if ($Type) {
        return array(
            round($C) . '%',
            round($M) . '%',
            round($Y) . '%',
            round($K) . '%'
        );
    } else {
        return array(
            round($C),
            round($M),
            round($Y),
            round($K)
        );
    }
}

function CMYKToRGB(int $C = 0, int $M = 0, int $Y = 0, int $K = 0): array
{
    $C /= 100;
    $M /= 100;
    $Y /= 100;
    $K /= 100;
    $R = 255 * (1 - $C) * (1 - $K);
    $G = 255 * (1 - $M) * (1 - $K);
    $B = 255 * (1 - $Y) * (1 - $K);
    return array(
        round($R),
        round($G),
        round($B)
    );
}

function RGBToHSI(int $R = 255, int $G = 255, int $B = 255, bool $Type): array
{
    $R /= 255;
    $G /= 255;
    $B /= 255;
    $I = ($R + $G + $B) / 3;
    $MIN = min($R, $G, $B);
    $S = 1 - 3 * $MIN / ($R + $G + $B);
    $Numerator = 0.5 * (($R - $G) + ($R - $B));
    $Denominator = sqrt(($R - $G) ** 2 + ($R - $B) * ($G - $B));
    $Theta = acos($Numerator / $Denominator);
    if ($B <= $G) {
        $H = $Theta;
    } else {
        $H = 2 * pi() - $Theta;
    }
    $H = $H * 180 / pi();
    $S *= 100;
    $I *= 100;
    if ($Type) {
        return array(
            round($H, 2) . '°',
            round($S, 2) . '%',
            round($I, 2) . '%'
        );
    } else {
        return array(
            round($H, 2),
            round($S, 2),
            round($I, 2)
        );
    }
}

function HSIToRGB(int $H = 0, int $S = 0, int $I = 100): array
{
    $H /= 360;
    $S /= 100;
    $I /= 100;
    if ($S == 0) {
        $R = $I;
        $G = $I;
        $B = $I;
    } else {
        if ($H == 1) $H = 0;
        $H *= 6;
        $I2 = floor($H);
        $F = $H - $I2;
        $A = $I * (1 - $S);
        $B = $I * (1 - $S * $F);
        $C = $I * (1 - $S * (1 - $F));
        switch ($I2) {
            case 0:
                $R = $I;
                $G = $C;
                $B = $A;
                break;
            case 1:
                $R = $B;
                $G = $I;
                $B = $A;
                break;
            case 2:
                $R = $A;
                $G = $I;
                $B = $C;
                break;
            case 3:
                $R = $A;
                $G = $B;
                $B = $I;
                break;
            case 4:
                $R = $C;
                $G = $A;
                $B = $I;
                break;
            case 5:
                $R = $I;
                $G = $A;
                $B = $B;
                break;
        }
    }
    return array(
        round($R * 255),
        round($G * 255),
        round($B * 255)
    );
}
