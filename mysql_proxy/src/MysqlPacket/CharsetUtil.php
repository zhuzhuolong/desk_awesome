<?php

namespace Proxy\MysqlPacket;

class CharsetUtil
{
    private static array $CHARSET_TO_INDEX = [];
    const INDEX_TO_CHARSET = [
        1   => ['big5', 'big5_chinese_ci', true],
        2   => ['latin2', 'latin2_czech_cs', false],
        3   => ['dec8', 'dec8_swedish_ci', true],
        4   => ['cp850', 'cp850_general_ci', true],
        5   => ['latin1', 'latin1_german1_ci', false],
        6   => ['hp8', 'hp8_english_ci', true],
        7   => ['koi8r', 'koi8r_general_ci', true],
        8   => ['latin1', 'latin1_swedish_ci', true],
        9   => ['latin2', 'latin2_general_ci', true],
        10  => ['swe7', 'swe7_swedish_ci', true],
        11  => ['ascii', 'ascii_general_ci', true],
        12  => ['ujis', 'ujis_japanese_ci', true],
        13  => ['sjis', 'sjis_japanese_ci', true],
        14  => ['cp1251', 'cp1251_bulgarian_ci', false],
        15  => ['latin1', 'latin1_danish_ci', false],
        16  => ['hebrew', 'hebrew_general_ci', true],
        18  => ['tis620', 'tis620_thai_ci', true],
        19  => ['euckr', 'euckr_korean_ci', true],
        20  => ['latin7', 'latin7_estonian_cs', false],
        21  => ['latin2', 'latin2_hungarian_ci', false],
        22  => ['koi8u', 'koi8u_general_ci', true],
        23  => ['cp1251', 'cp1251_ukrainian_ci', false],
        24  => ['gb2312', 'gb2312_chinese_ci', true],
        25  => ['greek', 'greek_general_ci', true],
        26  => ['cp1250', 'cp1250_general_ci', true],
        27  => ['latin2', 'latin2_croatian_ci', false],
        28  => ['gbk', 'gbk_chinese_ci', true],
        29  => ['cp1257', 'cp1257_lithuanian_ci', false],
        30  => ['latin5', 'latin5_turkish_ci', true],
        31  => ['latin1', 'latin1_german2_ci', false],
        32  => ['armscii8', 'armscii8_general_ci', true],
        33  => ['utf8', 'utf8_general_ci', true],
        34  => ['cp1250', 'cp1250_czech_cs', false],
        35  => ['ucs2', 'ucs2_general_ci', true],
        36  => ['cp866', 'cp866_general_ci', true],
        37  => ['keybcs2', 'keybcs2_general_ci', true],
        38  => ['macce', 'macce_general_ci', true],
        39  => ['macroman', 'macroman_general_ci', true],
        40  => ['cp852', 'cp852_general_ci', true],
        41  => ['latin7', 'latin7_general_ci', true],
        42  => ['latin7', 'latin7_general_cs', false],
        43  => ['macce', 'macce_bin', false],
        44  => ['cp1250', 'cp1250_croatian_ci', false],
        45  => ['utf8mb4', 'utf8mb4_general_ci', true],
        46  => ['utf8mb4', 'utf8mb4_bin', false],
        47  => ['latin1', 'latin1_bin', false],
        48  => ['latin1', 'latin1_general_ci', false],
        49  => ['latin1', 'latin1_general_cs', false],
        50  => ['cp1251', 'cp1251_bin', false],
        51  => ['cp1251', 'cp1251_general_ci', true],
        52  => ['cp1251', 'cp1251_general_cs', false],
        53  => ['macroman', 'macroman_bin', false],
        54  => ['utf16', 'utf16_general_ci', true],
        55  => ['utf16', 'utf16_bin', false],
        57  => ['cp1256', 'cp1256_general_ci', true],
        58  => ['cp1257', 'cp1257_bin', false],
        59  => ['cp1257', 'cp1257_general_ci', true],
        60  => ['utf32', 'utf32_general_ci', true],
        61  => ['utf32', 'utf32_bin', false],
        63  => ['binary', 'binary', true],
        64  => ['armscii8', 'armscii8_bin', false],
        65  => ['ascii', 'ascii_bin', false],
        66  => ['cp1250', 'cp1250_bin', false],
        67  => ['cp1256', 'cp1256_bin', false],
        68  => ['cp866', 'cp866_bin', false],
        69  => ['dec8', 'dec8_bin', false],
        70  => ['greek', 'greek_bin', false],
        71  => ['hebrew', 'hebrew_bin', false],
        72  => ['hp8', 'hp8_bin', false],
        73  => ['keybcs2', 'keybcs2_bin', false],
        74  => ['koi8r', 'koi8r_bin', false],
        75  => ['koi8u', 'koi8u_bin', false],
        77  => ['latin2', 'latin2_bin', false],
        78  => ['latin5', 'latin5_bin', false],
        79  => ['latin7', 'latin7_bin', false],
        80  => ['cp850', 'cp850_bin', false],
        81  => ['cp852', 'cp852_bin', false],
        82  => ['swe7', 'swe7_bin', false],
        83  => ['utf8', 'utf8_bin', false],
        84  => ['big5', 'big5_bin', false],
        85  => ['euckr', 'euckr_bin', false],
        86  => ['gb2312', 'gb2312_bin', false],
        87  => ['gbk', 'gbk_bin', false],
        88  => ['sjis', 'sjis_bin', false],
        89  => ['tis620', 'tis620_bin', false],
        90  => ['ucs2', 'ucs2_bin', false],
        91  => ['ujis', 'ujis_bin', false],
        92  => ['geostd8', 'geostd8_general_ci', true],
        93  => ['geostd8', 'geostd8_bin', false],
        94  => ['latin1', 'latin1_spanish_ci', false],
        95  => ['cp932', 'cp932_japanese_ci', true],
        96  => ['cp932', 'cp932_bin', false],
        97  => ['eucjpms', 'eucjpms_japanese_ci', true],
        98  => ['eucjpms', 'eucjpms_bin', false],
        99  => ['cp1250', 'cp1250_polish_ci', false],
        101 => ['utf16', 'utf16_unicode_ci', false],
        102 => ['utf16', 'utf16_icelandic_ci', false],
        103 => ['utf16', 'utf16_latvian_ci', false],
        104 => ['utf16', 'utf16_romanian_ci', false],
        105 => ['utf16', 'utf16_slovenian_ci', false],
        106 => ['utf16', 'utf16_polish_ci', false],
        107 => ['utf16', 'utf16_estonian_ci', false],
        108 => ['utf16', 'utf16_spanish_ci', false],
        109 => ['utf16', 'utf16_swedish_ci', false],
        110 => ['utf16', 'utf16_turkish_ci', false],
        111 => ['utf16', 'utf16_czech_ci', false],
        112 => ['utf16', 'utf16_danish_ci', false],
        113 => ['utf16', 'utf16_lithuanian_ci', false],
        114 => ['utf16', 'utf16_slovak_ci', false],
        115 => ['utf16', 'utf16_spanish2_ci', false],
        116 => ['utf16', 'utf16_roman_ci', false],
        117 => ['utf16', 'utf16_persian_ci', false],
        118 => ['utf16', 'utf16_esperanto_ci', false],
        119 => ['utf16', 'utf16_hungarian_ci', false],
        120 => ['utf16', 'utf16_sinhala_ci', false],
        128 => ['ucs2', 'ucs2_unicode_ci', false],
        129 => ['ucs2', 'ucs2_icelandic_ci', false],
        130 => ['ucs2', 'ucs2_latvian_ci', false],
        131 => ['ucs2', 'ucs2_romanian_ci', false],
        132 => ['ucs2', 'ucs2_slovenian_ci', false],
        133 => ['ucs2', 'ucs2_polish_ci', false],
        134 => ['ucs2', 'ucs2_estonian_ci', false],
        135 => ['ucs2', 'ucs2_spanish_ci', false],
        136 => ['ucs2', 'ucs2_swedish_ci', false],
        137 => ['ucs2', 'ucs2_turkish_ci', false],
        138 => ['ucs2', 'ucs2_czech_ci', false],
        139 => ['ucs2', 'ucs2_danish_ci', false],
        140 => ['ucs2', 'ucs2_lithuanian_ci', false],
        141 => ['ucs2', 'ucs2_slovak_ci', false],
        142 => ['ucs2', 'ucs2_spanish2_ci', false],
        143 => ['ucs2', 'ucs2_roman_ci', false],
        144 => ['ucs2', 'ucs2_persian_ci', false],
        145 => ['ucs2', 'ucs2_esperanto_ci', false],
        146 => ['ucs2', 'ucs2_hungarian_ci', false],
        147 => ['ucs2', 'ucs2_sinhala_ci', false],
        159 => ['ucs2', 'ucs2_general_mysql500_ci', false],
        160 => ['utf32', 'utf32_unicode_ci', false],
        161 => ['utf32', 'utf32_icelandic_ci', false],
        162 => ['utf32', 'utf32_latvian_ci', false],
        163 => ['utf32', 'utf32_romanian_ci', false],
        164 => ['utf32', 'utf32_slovenian_ci', false],
        165 => ['utf32', 'utf32_polish_ci', false],
        166 => ['utf32', 'utf32_estonian_ci', false],
        167 => ['utf32', 'utf32_spanish_ci', false],
        168 => ['utf32', 'utf32_swedish_ci', false],
        169 => ['utf32', 'utf32_turkish_ci', false],
        170 => ['utf32', 'utf32_czech_ci', false],
        171 => ['utf32', 'utf32_danish_ci', false],
        172 => ['utf32', 'utf32_lithuanian_ci', false],
        173 => ['utf32', 'utf32_slovak_ci', false],
        174 => ['utf32', 'utf32_spanish2_ci', false],
        175 => ['utf32', 'utf32_roman_ci', false],
        176 => ['utf32', 'utf32_persian_ci', false],
        177 => ['utf32', 'utf32_esperanto_ci', false],
        178 => ['utf32', 'utf32_hungarian_ci', false],
        179 => ['utf32', 'utf32_sinhala_ci', false],
        192 => ['utf8', 'utf8_unicode_ci', false],
        193 => ['utf8', 'utf8_icelandic_ci', false],
        194 => ['utf8', 'utf8_latvian_ci', false],
        195 => ['utf8', 'utf8_romanian_ci', false],
        196 => ['utf8', 'utf8_slovenian_ci', false],
        197 => ['utf8', 'utf8_polish_ci', false],
        198 => ['utf8', 'utf8_estonian_ci', false],
        199 => ['utf8', 'utf8_spanish_ci', false],
        200 => ['utf8', 'utf8_swedish_ci', false],
        201 => ['utf8', 'utf8_turkish_ci', false],
        202 => ['utf8', 'utf8_czech_ci', false],
        203 => ['utf8', 'utf8_danish_ci', false],
        204 => ['utf8', 'utf8_lithuanian_ci', false],
        205 => ['utf8', 'utf8_slovak_ci', false],
        206 => ['utf8', 'utf8_spanish2_ci', false],
        207 => ['utf8', 'utf8_roman_ci', false],
        208 => ['utf8', 'utf8_persian_ci', false],
        209 => ['utf8', 'utf8_esperanto_ci', false],
        210 => ['utf8', 'utf8_hungarian_ci', false],
        211 => ['utf8', 'utf8_sinhala_ci', false],
        223 => ['utf8', 'utf8_general_mysql500_ci', false],
        224 => ['utf8mb4', 'utf8mb4_unicode_ci', false],
        225 => ['utf8mb4', 'utf8mb4_icelandic_ci', false],
        226 => ['utf8mb4', 'utf8mb4_latvian_ci', false],
        227 => ['utf8mb4', 'utf8mb4_romanian_ci', false],
        228 => ['utf8mb4', 'utf8mb4_slovenian_ci', false],
        229 => ['utf8mb4', 'utf8mb4_polish_ci', false],
        230 => ['utf8mb4', 'utf8mb4_estonian_ci', false],
        231 => ['utf8mb4', 'utf8mb4_spanish_ci', false],
        232 => ['utf8mb4', 'utf8mb4_swedish_ci', false],
        233 => ['utf8mb4', 'utf8mb4_turkish_ci', false],
        234 => ['utf8mb4', 'utf8mb4_czech_ci', false],
        235 => ['utf8mb4', 'utf8mb4_danish_ci', false],
        236 => ['utf8mb4', 'utf8mb4_lithuanian_ci', false],
        237 => ['utf8mb4', 'utf8mb4_slovak_ci', false],
        238 => ['utf8mb4', 'utf8mb4_spanish2_ci', false],
        239 => ['utf8mb4', 'utf8mb4_roman_ci', false],
        240 => ['utf8mb4', 'utf8mb4_persian_ci', false],
        241 => ['utf8mb4', 'utf8mb4_esperanto_ci', false],
        242 => ['utf8mb4', 'utf8mb4_hungarian_ci', false],
        243 => ['utf8mb4', 'utf8mb4_sinhala_ci', false],
        244 => ['utf8mb4', 'utf8mb4_german2_ci', false],
        245 => ['utf8mb4', 'utf8mb4_croatian_ci', false],
        246 => ['utf8mb4', 'utf8mb4_unicode_520_ci', false],
        247 => ['utf8mb4', 'utf8mb4_vietnamese_ci', false],
    ];

    public static function init()
    {
        // charset --> index
        foreach (self::INDEX_TO_CHARSET as $index => $charset) {
            if (!isset(self::$CHARSET_TO_INDEX[$charset[0]]) || $charset[2]) {
                self::$CHARSET_TO_INDEX[$charset[0]] = $index;
            }
        }

        self::$CHARSET_TO_INDEX['iso-8859-1'] = 14;
        self::$CHARSET_TO_INDEX['utf-8']      = 33;
    }

    public static function getCharset(int $index)
    {
        self::init();
        return self::INDEX_TO_CHARSET[$index][0];
    }

    public static function getIndex(string $charset)
    {
        self::init();
        if (null == $charset || 0 == strlen($charset)) {
            return 0;
        } else {
            $i = self::$CHARSET_TO_INDEX[strtolower($charset)];

            return (null == $i) ? 0 : $i;
        }
    }

    public static function charsetToEncoding($charset)
    {
        if ($charset == 'utf8mb4') {
            return 'utf-8';
        } elseif ($charset == 'utf8') {
            return 'utf-8';
        }
        return $charset;
    }
}
