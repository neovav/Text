<?php
namespace neovav\Text;

/**
 * Php class for text processing
 *
 * @author neovav <neovav@@outlook.com>
 * @date 2019.08.18 17:50
 * @version 0.0.1
 */

class Text
{

    /** @var string         Notifier class author                           */
    const AUTH      = 'NeoVAV';

    /** @var string         Number version                                  */
    const VERSION   = '0.0.1';

    private static $numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    private static $list_char = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m',
                                      'Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M',
                                      '1','2','3','4','5','6','7','8','9','0');

    private static $list_togle = array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j',
                                       'к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f',
                                       'х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shh','ъ'=>'','ы'=>'i','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
                                       'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'J',
                                       'К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F',
                                       'Х'=>'H','Ц'=>'C','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Shh','Ъ'=>'','Ы'=>'I','Ь'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
                                       ' '=>' ');

    /**
     * Method for trim empty chars
     *
     * @param string $string
     *
     * @return string
     */

    public static function trim(string $string) :string
    {
        return preg_replace( "/(^\s+)|(\s+$)/us", '', $string );
    }

    /**
     * Method remove chars beside digits
     *
     * @param string $string
     *
     * @return int
     */

    public static function digits(string $string) :int
    {
        return (int)preg_replace( '/[^0-9]/', '', $string );
    }


    /**
     * Remove chars beside digits
     *
     * @param string $string
     * @param string $except
     *
     * @return string
     */

    public static function delSymbolFromString(string $string, string $except = '') :string
    {
        $ret = false;
        $number = '';
        $num = mb_strlen($string);
        for($i = 0; $i < $num; $i++) {

            $c = mb_substr($string,$i,1);
            if( in_array($c, self::$numbers) || is_string($except) && $c==$except ||
                is_array($except) && $c==in_array($c,$except, true)) $number.=$c;
        };

        if($number !== '') $ret = $number;

        return $ret;
    }


    /**
     * Get code of the char in UNICODE
     *
     * @param string $char
     *
     * @return integer
     */

    public static function ordUnicode(string $char)
    {
        $k = mb_convert_encoding($char, 'UCS-2LE', 'UTF-8');
        $k1 = ord(mb_substr($k, 0, 1, 'utf-8'));
        $k2 = ord(mb_substr($k, 1, 1, 'utf-8'));
        $ret = $k2 * 256 + $k1;

        return $ret;
    }


    /**
     * Get code of the char in UNICODE at HEX view
     *
     * @param string $char
     *
     * @return string
     */

    public static function ordUnicodeHex(string $char) :string
    {
        $ret = dechex(self::ordUnicode($char));

        $n = mb_strlen($ret, 'utf-8');
        for($j=$n; $j<4; $j++) $ret = '0'.$ret;

        return $ret;
    }


    /**
     * String to unicode hex array
     *
     * @param string $string
     *
     * @return array
     */

    public static function stringToUnicodeHexArr(string $string) :array
    {
        $ret = [];

        if(!empty($string) && is_string($string)) {
            $len = mb_strlen($string, 'utf-8');
            for($i=0; $i<$len; $i++)
                $ret[] = self::ordUnicodeHex(mb_substr($string, $i, 1, 'utf-8'));
        };

        return $ret;
    }


    /**
     * Convert ucs2 to text
     *
     * @param string $ucs
     *
     * @return bool|string
     */

    public static function ucs2toText(string $ucs)
    {
        $ret = false;

        $len = mb_strlen($ucs, 'utf-8');
        $div = $len%4;
        if($div==0) {

            $list_char = [];
            for($i=0; $i<$len; $i=$i+4) {
                $char = hexdec(mb_substr($ucs, $i, 4, 'utf-8'));
                $c1 = $char%256;
                $c2 = ($char-$c1)/256;
                $c = chr($c1).chr($c2);
                $list_char[] = mb_convert_encoding($c, 'UTF-8', 'UCS-2LE');
            };
            $ret = implode('', $list_char);
        };

        return $ret;
    }


    /**
     * Convert text in PDU
     *
     * @param string $text
     *
     * @return string
     */

    public static function stringToPDU(string $text) :string
    {
        $bins = str_split($text);
        foreach ($bins as $k=>$v)
            $bins[$k] = mb_substr("0000000".base_convert(ord($v),10,2) , -7, null, 'utf-8');

        $hexes = array();
        $maxbins = count($bins) - 1;
        for ($i = 0 ; $i <= $maxbins ; $i++) {
            if ($i != $maxbins) {
                $hl = mb_strlen($bins[$i], 'utf-8');
                if ($hl > 0) {
                    $steal = $hl - 8;
                    $hexes[] = mb_substr($bins[$i+1], $steal, null, 'utf-8').$bins[$i];
                    $bins[$i+1] = mb_substr($bins[$i+1],0,7 + $steal, 'utf-8');
                };
            } else $hexes[] = mb_substr("00000000".$bins[$i] , -8, null, 'utf-8');
        };

        $pdu = array();
        foreach ($hexes as $v) {
            if ($v != "00000000") {
                $pdu[] = mb_substr("0".mb_strtoupper(base_convert($v, 2, 16)),-2, null, 'utf-8');
            };
        };

        $ret = implode('', $pdu);

        return $ret;
    }


    /**
     * Convert PDU in text
     *
     * @param string $pdu
     *
     * @return string
     */

    public static function pduToString(string $pdu) :string
    {
        $hexlen = mb_strlen($pdu, 'utf-8')/2 - 1;
        $hexes = array();
        for ($i = 0; $i <= $hexlen; $i++)
            $hexes[] = mb_substr("00000000".base_convert((mb_substr($pdu,($i*2), 2, 'utf-8')),16,2),-8, 'utf-8');

        $LeftOver = "";
        $Take = 7;
        $FullBin = "";
        for ($i=0 ; $i<= $hexlen ; $i++) {

            $rhs = 0 - $Take;
            $FullBin .= mb_substr($hexes[$i], $rhs, null, 'utf-8').$LeftOver;
            $lhs = 8-$Take;
            $LeftOver = mb_substr($hexes[$i],0, $lhs, 'utf-8');
            $Take = $Take-1;
            if ($Take == 0) {$Take = 7;}
            if (mb_strlen($LeftOver, 'utf-8') == 7) {
                $FullBin .= $LeftOver;
                $LeftOver = "";
            };
        };

        $chrnum = array();
        while ($FullBin != '') {

            $chrnum[] = chr(bindec(mb_substr($FullBin,0,7, 'utf-8')));
            $FullBin = mb_substr($FullBin,7, null, 'utf-8');
        };

        $ret =  implode('', $chrnum);

        return $ret;
    }


    /**
     * Password generation
     *
     * @param int $minLen
     * @param int $maxLen
     * @param array $add
     *
     * @return string
     */

    public static function genPass($minLen = 6, $maxLen = 12, array $add = null) :string
    {
        if($minLen>$maxLen) $r = rand($maxLen, $minLen);
            else $r = rand($minLen, $maxLen);

        if (is_array($add) && !empty($add))
            $list_char = array_merge(self::$list_char, $add);
                else $list_char = self::$list_char;

        $num = count($list_char)-1;
        $ret = '';
        for($i = 0; $i < $r; $i++) {
            $j = rand(0, $num);
            $ret .= $list_char[$j];
        };

        return $ret;
    }


    /**
     * Translite russian words
     *
     * @param string $str
     *
     * @return string
     */

    public static function translit(string $str) :string
    {
        $num = mb_strlen($str, 'utf-8');
        $ret='';
        for($i = 0; $i < $num; $i++) {
            $c = mb_substr($str,$i,1, 'utf-8');
            if(isset(self::$list_togle[$c])) $ret .=self::$list_togle[$c]; else $ret .= $c;
        };

        return $ret;
    }
}