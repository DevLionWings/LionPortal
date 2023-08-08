<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

use Auth;

class Convertion
{
    public static function CONVERT($num = '')
    {
        $num    = ( string ) ( ( int ) $num );
        
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
             
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
             
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
             
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
             
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                 
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                $words  = str_replace( ',' , ' and' , $words );
            }
             
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    private static function charAt($s, $i)
    {
        return substr($s, $i, 1);
    }
    private static function getValidityNaN($str, $from, $to, $min = 1, $max = 9)
    {
        $val = false;
        $from = ($from < 0) ? 0 : $from;
        for ($i = $from; $i < $to; $i++) {
            if (((int) Convertion::charAt($str, $i) >= $min) && ((int) Convertion::charAt($str, $i) <= $max)) $val = true;
        }
        return $val;
    }
    private static function getTerbilang($i, $str, $len)
    {
        $numA = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN");
        $numB = array("", "SE", "DUA ", "TIGA ", "EMPAT ", "LIMA ", "ENAM ", "TUJUH ", "DELAPAN ", "SEMBILAN ");
        $numC = array("", "SATU ", "DUA ", "TIGA ", "EMPAT ", "LIMA ", "ENAM ", "TUJUH ", "DELAPAN ", "SEMBILAN ");
        $numD = array(0 => "PULUH", 1 => "BELAS", 2 => "RATUS", 4 => "RIBU", 7 => "JUTA", 10 => "MILYAR", 13 => "TRILIUN");
        $buf = "";
        $pos = $len - $i;
        switch ($pos) {
            case 1:
                if (!Convertion::getValidityNaN($str, $i - 1, $i, 1, 1))
                    $buf = $numA[(int) Convertion::charAt($str, $i)];
                break;
            case 2:
            case 5:
            case 8:
            case 11:
            case 14:
                if ((int) Convertion::charAt($str, $i) == 1) {
                    if ((int) Convertion::charAt($str, $i + 1) == 0)
                        $buf = ($numB[(int) Convertion::charAt($str, $i)]) . ($numD[0]);
                    else
                        $buf = ($numB[(int) Convertion::charAt($str, $i + 1)]) . ($numD[1]);
                } else if ((int) Convertion::charAt($str, $i) > 1) {
                    $buf = ($numB[(int) Convertion::charAt($str, $i)]) . ($numD[0]);
                }
                break;
            case 3:
            case 6:
            case 9:
            case 12:
            case 15:
                if ((int) Convertion::charAt($str, $i) > 0) {
                    $buf = ($numB[(int) Convertion::charAt($str, $i)]) . ($numD[2]);
                }
                break;
            case 4:
            case 7:
            case 10:
            case 13:
                if (Convertion::getValidityNaN($str, $i - 2, $i)) {
                    if (!Convertion::getValidityNaN($str, $i - 1, $i, 1, 1))
                        $buf = $numC[(int) Convertion::charAt($str, $i)] . ($numD[$pos]);
                    else
                        $buf = $numD[$pos];
                } else if ((int) Convertion::charAt($str, $i) > 0) {
                    if ($pos == 4)
                        $buf = ($numB[(int) Convertion::charAt($str, $i)]) . ($numD[$pos]);
                    else
                        $buf = ($numC[(int) Convertion::charAt($str, $i)]) . ($numD[$pos]);
                }
                break;
        }
        return $buf;
    }
 
    /**
     * Dapatkan nilai terbilang dari suatu bilangan
     * Contoh:
     * $val = Convertion::terbilang("2000");
     *   menghasilkan: dua ribu
     *
     * @param string $nominal Bilangan yang akan dibaca
     * @return string String Terbilang
     */
    public static function TERBILANG($nominal)
    {
        $buf = "";
        $str = $nominal . "";
        $len = strlen($str);
 
        for ($i = 0; $i < $len; $i++) {
            $buf = trim($buf) . " " . (Convertion::getTerbilang($i, $str, $len));
        }
        return trim($buf);
    }
}



