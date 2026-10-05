<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ShippingController extends Controller
{
    // ID kota asal toko Sweet Dreams (Jakarta Selatan = 136 di Komerce)
    protected int $originCityId;
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl      = config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1');
        $this->apiKey       = (string) config('services.rajaongkir.key', '');
        $this->originCityId = (int) config('services.rajaongkir.origin_city_id', 136);
    }

    /**
     * Daftar 34 provinsi resmi Indonesia (RajaOngkir Komerce ID)
     */
    public static function staticProvinces(): array
    {
        return [
            ['id' => 15, 'name' => 'BALI'],
            ['id' => 24, 'name' => 'BANGKA BELITUNG'],
            ['id' => 11, 'name' => 'BANTEN'],
            ['id' => 6,  'name' => 'BENGKULU'],
            ['id' => 19, 'name' => 'DI YOGYAKARTA'],
            ['id' => 10, 'name' => 'DKI JAKARTA'],
            ['id' => 17, 'name' => 'GORONTALO'],
            ['id' => 13, 'name' => 'JAMBI'],
            ['id' => 5,  'name' => 'JAWA BARAT'],
            ['id' => 12, 'name' => 'JAWA TENGAH'],
            ['id' => 18, 'name' => 'JAWA TIMUR'],
            ['id' => 28, 'name' => 'KALIMANTAN BARAT'],
            ['id' => 3,  'name' => 'KALIMANTAN SELATAN'],
            ['id' => 4,  'name' => 'KALIMANTAN TENGAH'],
            ['id' => 7,  'name' => 'KALIMANTAN TIMUR'],
            ['id' => 31, 'name' => 'KALIMANTAN UTARA'],
            ['id' => 8,  'name' => 'KEPULAUAN RIAU'],
            ['id' => 30, 'name' => 'LAMPUNG'],
            ['id' => 2,  'name' => 'MALUKU'],
            ['id' => 32, 'name' => 'MALUKU UTARA'],
            ['id' => 9,  'name' => 'NANGGROE ACEH DARUSSALAM (NAD)'],
            ['id' => 1,  'name' => 'NUSA TENGGARA BARAT (NTB)'],
            ['id' => 21, 'name' => 'NUSA TENGGARA TIMUR (NTT)'],
            ['id' => 14, 'name' => 'PAPUA'],
            ['id' => 29, 'name' => 'PAPUA BARAT'],
            ['id' => 25, 'name' => 'RIAU'],
            ['id' => 34, 'name' => 'SULAWESI BARAT'],
            ['id' => 33, 'name' => 'SULAWESI SELATAN'],
            ['id' => 27, 'name' => 'SULAWESI TENGAH'],
            ['id' => 20, 'name' => 'SULAWESI TENGGARA'],
            ['id' => 22, 'name' => 'SULAWESI UTARA'],
            ['id' => 23, 'name' => 'SUMATERA BARAT'],
            ['id' => 26, 'name' => 'SUMATERA SELATAN'],
            ['id' => 16, 'name' => 'SUMATERA UTARA'],
        ];
    }

    /**
     * Ambil daftar provinsi (dari cache / API / fallback resmi)
     */
    public function getProvincesList(): array
    {
        $list = Cache::remember('rajaongkir_provinces', 86400 * 30, function () {
            try {
                $res = Http::withoutVerifying()
                    ->withHeaders(['key' => $this->apiKey])
                    ->timeout(6)
                    ->get("{$this->baseUrl}/destination/province");

                if ($res->successful()) {
                    $items = $res->json('data', []);
                    if (!empty($items)) {
                        return $items;
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }
            return self::staticProvinces();
        });

        if (empty($list)) {
            $list = self::staticProvinces();
        }

        // Urutkan abjad A-Z
        usort($list, fn($a, $b) => strcmp($a['name'], $b['name']));

        return $list;
    }

    /**
     * GET /api/shipping/provinces
     * Daftar semua provinsi di Indonesia
     */
    public function provinces()
    {
        return response()->json($this->getProvincesList());
    }

        /**
     * Fallback kota statis untuk 34 provinsi resmi Indonesia
     * Menjamin dropdown kota tetap berfungsi 100% meskipun API luar sedang limit/gangguan
     */
    public static function staticCitiesForProvince(int $provinceId): array
    {
        $map = [

    // 1. NTB
    1 => [
        ['id' => 101, 'name' => 'KOTA MATARAM'],
        ['id' => 102, 'name' => 'KOTA BIMA'],
        ['id' => 103, 'name' => 'KABUPATEN LOMBOK BARAT'],
        ['id' => 104, 'name' => 'KABUPATEN LOMBOK TENGAH'],
        ['id' => 105, 'name' => 'KABUPATEN LOMBOK TIMUR'],
        ['id' => 106, 'name' => 'KABUPATEN LOMBOK UTARA'],
        ['id' => 107, 'name' => 'KABUPATEN SUMBAWA'],
        ['id' => 108, 'name' => 'KABUPATEN SUMBAWA BARAT'],
        ['id' => 109, 'name' => 'KABUPATEN DOMPU'],
        ['id' => 110, 'name' => 'KABUPATEN BIMA'],
    ],
    // 2. MALUKU
    2 => [
        ['id' => 201, 'name' => 'KOTA AMBON'],
        ['id' => 202, 'name' => 'KOTA TUAL'],
        ['id' => 203, 'name' => 'KABUPATEN BURU'],
        ['id' => 204, 'name' => 'KABUPATEN BURU SELATAN'],
        ['id' => 205, 'name' => 'KABUPATEN KEPULAUAN ARU'],
        ['id' => 206, 'name' => 'KABUPATEN MALUKU BARAT DAYA'],
        ['id' => 207, 'name' => 'KABUPATEN MALUKU TENGAH'],
        ['id' => 208, 'name' => 'KABUPATEN MALUKU TENGGARA'],
        ['id' => 209, 'name' => 'KABUPATEN KEPULAUAN TANIMBAR'],
        ['id' => 210, 'name' => 'KABUPATEN SERAM BAGIAN BARAT'],
        ['id' => 211, 'name' => 'KABUPATEN SERAM BAGIAN TIMUR'],
    ],
    // 3. KALIMANTAN SELATAN
    3 => [
        ['id' => 301, 'name' => 'KOTA BANJARMASIN'],
        ['id' => 302, 'name' => 'KOTA BANJARBARU'],
        ['id' => 303, 'name' => 'KABUPATEN BANJAR'],
        ['id' => 304, 'name' => 'KABUPATEN BARITO KUALA'],
        ['id' => 305, 'name' => 'KABUPATEN HULU SUNGAI SELATAN'],
        ['id' => 306, 'name' => 'KABUPATEN HULU SUNGAI TENGAH'],
        ['id' => 307, 'name' => 'KABUPATEN HULU SUNGAI UTARA'],
        ['id' => 308, 'name' => 'KABUPATEN KOTABARU'],
        ['id' => 309, 'name' => 'KABUPATEN TABALONG'],
        ['id' => 310, 'name' => 'KABUPATEN TANAH BUMBU'],
        ['id' => 311, 'name' => 'KABUPATEN TANAH LAUT'],
        ['id' => 312, 'name' => 'KABUPATEN TAPIN'],
        ['id' => 313, 'name' => 'KABUPATEN BALANGAN'],
    ],
    // 4. KALIMANTAN TENGAH
    4 => [
        ['id' => 401, 'name' => 'KOTA PALANGKA RAYA'],
        ['id' => 402, 'name' => 'KABUPATEN BARITO SELATAN'],
        ['id' => 403, 'name' => 'KABUPATEN BARITO TIMUR'],
        ['id' => 404, 'name' => 'KABUPATEN BARITO UTARA'],
        ['id' => 405, 'name' => 'KABUPATEN GUNUNG MAS'],
        ['id' => 406, 'name' => 'KABUPATEN KAPUAS'],
        ['id' => 407, 'name' => 'KABUPATEN KATINGAN'],
        ['id' => 408, 'name' => 'KABUPATEN KOTAWARINGIN BARAT'],
        ['id' => 409, 'name' => 'KABUPATEN KOTAWARINGIN TIMUR'],
        ['id' => 410, 'name' => 'KABUPATEN LAMANDAU'],
        ['id' => 411, 'name' => 'KABUPATEN MURUNG RAYA'],
        ['id' => 412, 'name' => 'KABUPATEN PULANG PISAU'],
        ['id' => 413, 'name' => 'KABUPATEN SUKAMARA'],
        ['id' => 414, 'name' => 'KABUPATEN SERUYAN'],
    ],
    // 5. JAWA BARAT
    5 => [
        ['id' => 55,  'name' => 'KOTA BANDUNG'],
        ['id' => 60,  'name' => 'KABUPATEN BANDUNG BARAT'],
        ['id' => 633, 'name' => 'KOTA BANJAR'],
        ['id' => 63,  'name' => 'KOTA BEKASI'],
        ['id' => 77,  'name' => 'KOTA BOGOR'],
        ['id' => 634, 'name' => 'KABUPATEN CIAMIS'],
        ['id' => 62,  'name' => 'KABUPATEN CIANJUR'],
        ['id' => 56,  'name' => 'KOTA CIMAHI'],
        ['id' => 129, 'name' => 'KOTA CIREBON'],
        ['id' => 199, 'name' => 'KOTA DEPOK'],
        ['id' => 59,  'name' => 'KABUPATEN GARUT'],
        ['id' => 131, 'name' => 'KABUPATEN INDRAMAYU'],
        ['id' => 329, 'name' => 'KABUPATEN KARAWANG'],
        ['id' => 132, 'name' => 'KABUPATEN KUNINGAN'],
        ['id' => 133, 'name' => 'KABUPATEN MAJALENGKA'],
        ['id' => 635, 'name' => 'KABUPATEN PANGANDARAN'],
        ['id' => 532, 'name' => 'KABUPATEN PURWAKARTA'],
        ['id' => 533, 'name' => 'KABUPATEN SUBANG'],
        ['id' => 538, 'name' => 'KOTA SUKABUMI'],
        ['id' => 57,  'name' => 'KABUPATEN SUMEDANG'],
        ['id' => 632, 'name' => 'KOTA TASIKMALAYA'],
    ],
    // 6. BENGKULU
    6 => [
        ['id' => 601, 'name' => 'KOTA BENGKULU'],
        ['id' => 602, 'name' => 'KABUPATEN BENGKULU SELATAN'],
        ['id' => 603, 'name' => 'KABUPATEN BENGKULU TENGAH'],
        ['id' => 604, 'name' => 'KABUPATEN BENGKULU UTARA'],
        ['id' => 605, 'name' => 'KABUPATEN KAUR'],
        ['id' => 606, 'name' => 'KABUPATEN KEPAHIANG'],
        ['id' => 607, 'name' => 'KABUPATEN LEBONG'],
        ['id' => 608, 'name' => 'KABUPATEN MUKOMUKO'],
        ['id' => 609, 'name' => 'KABUPATEN REJANG LEBONG'],
        ['id' => 610, 'name' => 'KABUPATEN SELUMA'],
    ],
    // 7. KALIMANTAN TIMUR
    7 => [
        ['id' => 701, 'name' => 'KOTA SAMARINDA'],
        ['id' => 702, 'name' => 'KOTA BALIKPAPAN'],
        ['id' => 703, 'name' => 'KOTA BONTANG'],
        ['id' => 704, 'name' => 'KABUPATEN BERAU'],
        ['id' => 705, 'name' => 'KABUPATEN KUTAI BARAT'],
        ['id' => 706, 'name' => 'KABUPATEN KUTAI KARTANEGARA'],
        ['id' => 707, 'name' => 'KABUPATEN KUTAI TIMUR'],
        ['id' => 708, 'name' => 'KABUPATEN MAHAKAM ULU'],
        ['id' => 709, 'name' => 'KABUPATEN PASER'],
        ['id' => 710, 'name' => 'KABUPATEN PENAJAM PASER UTARA'],
    ],
    // 8. KEPULAUAN RIAU
    8 => [
        ['id' => 801, 'name' => 'KOTA BATAM'],
        ['id' => 802, 'name' => 'KOTA TANJUNGPINANG'],
        ['id' => 803, 'name' => 'KABUPATEN BINTAN'],
        ['id' => 804, 'name' => 'KABUPATEN KARIMUN'],
        ['id' => 805, 'name' => 'KABUPATEN KEPULAUAN ANAMBAS'],
        ['id' => 806, 'name' => 'KABUPATEN LINGGA'],
        ['id' => 807, 'name' => 'KABUPATEN NATUNA'],
    ],
    // 9. NANGGROE ACEH DARUSSALAM
    9 => [
        ['id' => 901, 'name' => 'KOTA BANDA ACEH'],
        ['id' => 902, 'name' => 'KOTA LANGSA'],
        ['id' => 903, 'name' => 'KOTA LHOKSEUMAWE'],
        ['id' => 904, 'name' => 'KOTA SABANG'],
        ['id' => 905, 'name' => 'KOTA SUBULUSSALAM'],
        ['id' => 906, 'name' => 'KABUPATEN ACEH BARAT'],
        ['id' => 907, 'name' => 'KABUPATEN ACEH BESAR'],
        ['id' => 908, 'name' => 'KABUPATEN ACEH SELATAN'],
        ['id' => 909, 'name' => 'KABUPATEN ACEH TENGAH'],
        ['id' => 910, 'name' => 'KABUPATEN ACEH TIMUR'],
        ['id' => 911, 'name' => 'KABUPATEN ACEH UTARA'],
        ['id' => 912, 'name' => 'KABUPATEN BIREUEN'],
        ['id' => 913, 'name' => 'KABUPATEN PIDIE'],
    ],
    // 10. DKI JAKARTA
    10 => [
        ['id' => 135, 'name' => 'JAKARTA BARAT'],
        ['id' => 137, 'name' => 'JAKARTA PUSAT'],
        ['id' => 136, 'name' => 'JAKARTA SELATAN'],
        ['id' => 139, 'name' => 'JAKARTA TIMUR'],
        ['id' => 138, 'name' => 'JAKARTA UTARA'],
        ['id' => 141, 'name' => 'KEPULAUAN SERIBU'],
    ],
    // 11. BANTEN
    11 => [
        ['id' => 1101, 'name' => 'KOTA TANGERANG'],
        ['id' => 1102, 'name' => 'KOTA TANGERANG SELATAN'],
        ['id' => 1103, 'name' => 'KOTA SERANG'],
        ['id' => 1104, 'name' => 'KOTA CILEGON'],
        ['id' => 1105, 'name' => 'KABUPATEN TANGERANG'],
        ['id' => 1106, 'name' => 'KABUPATEN SERANG'],
        ['id' => 1107, 'name' => 'KABUPATEN LEBAK'],
        ['id' => 1108, 'name' => 'KABUPATEN PANDEGLANG'],
    ],
    // 12. JAWA TENGAH
    12 => [
        ['id' => 576, 'name' => 'BANJARNEGARA'],
        ['id' => 591, 'name' => 'BANYUMAS'],
        ['id' => 564, 'name' => 'BATANG'],
        ['id' => 565, 'name' => 'BLORA'],
        ['id' => 540, 'name' => 'BOYOLALI'],
        ['id' => 589, 'name' => 'BREBES'],
        ['id' => 149, 'name' => 'CILACAP'],
        ['id' => 567, 'name' => 'DEMAK'],
        ['id' => 571, 'name' => 'GROBOGAN'],
        ['id' => 561, 'name' => 'JEPARA'],
        ['id' => 541, 'name' => 'KARANGANYAR'],
        ['id' => 384, 'name' => 'KEBUMEN'],
        ['id' => 568, 'name' => 'KENDAL'],
        ['id' => 542, 'name' => 'KLATEN'],
        ['id' => 562, 'name' => 'KUDUS'],
        ['id' => 383, 'name' => 'MAGELANG'],
        ['id' => 569, 'name' => 'PATI'],
        ['id' => 563, 'name' => 'PEKALONGAN'],
        ['id' => 570, 'name' => 'PEMALANG'],
        ['id' => 575, 'name' => 'PURBALINGGA'],
        ['id' => 386, 'name' => 'PURWOREJO'],
        ['id' => 572, 'name' => 'REMBANG'],
        ['id' => 573, 'name' => 'SALATIGA'],
        ['id' => 560, 'name' => 'SEMARANG'],
        ['id' => 543, 'name' => 'SRAGEN'],
        ['id' => 544, 'name' => 'SUKOHARJO'],
        ['id' => 539, 'name' => 'SURAKARTA'],
        ['id' => 588, 'name' => 'TEGAL'],
        ['id' => 387, 'name' => 'TEMANGGUNG'],
        ['id' => 545, 'name' => 'WONOGIRI'],
        ['id' => 385, 'name' => 'WONOSOBO'],
    ],
    // 13. JAMBI
    13 => [
        ['id' => 1301, 'name' => 'KOTA JAMBI'],
        ['id' => 1302, 'name' => 'KOTA SUNGAI PENUH'],
        ['id' => 1303, 'name' => 'KABUPATEN BATANGHARI'],
        ['id' => 1304, 'name' => 'KABUPATEN BUNGO'],
        ['id' => 1305, 'name' => 'KABUPATEN KERINCI'],
        ['id' => 1306, 'name' => 'KABUPATEN MERANGIN'],
        ['id' => 1307, 'name' => 'KABUPATEN MUARO JAMBI'],
        ['id' => 1308, 'name' => 'KABUPATEN SAROLANGUN'],
        ['id' => 1309, 'name' => 'KABUPATEN TANJUNG JABUNG BARAT'],
        ['id' => 1310, 'name' => 'KABUPATEN TANJUNG JABUNG TIMUR'],
        ['id' => 1311, 'name' => 'KABUPATEN TEBO'],
    ],
    // 14. PAPUA
    14 => [
        ['id' => 1401, 'name' => 'KOTA JAYAPURA'],
        ['id' => 1402, 'name' => 'KABUPATEN JAYAPURA'],
        ['id' => 1403, 'name' => 'KABUPATEN MERAUKE'],
        ['id' => 1404, 'name' => 'KABUPATEN MIMIKA (TIMIKA)'],
        ['id' => 1405, 'name' => 'KABUPATEN BIAK NUMFOR'],
        ['id' => 1406, 'name' => 'KABUPATEN JAYAWIJAYA (WAMENA)'],
        ['id' => 1407, 'name' => 'KABUPATEN NABIRE'],
        ['id' => 1408, 'name' => 'KABUPATEN KEPULAUAN YAPEN (SERUI)'],
        ['id' => 1409, 'name' => 'KABUPATEN KEEROM'],
        ['id' => 1410, 'name' => 'KABUPATEN SARMI'],
        ['id' => 1411, 'name' => 'KABUPATEN ASMAT'],
        ['id' => 1412, 'name' => 'KABUPATEN BOVEN DIGOEL'],
        ['id' => 1413, 'name' => 'KABUPATEN MAPPI'],
        ['id' => 1414, 'name' => 'KABUPATEN PANIAI'],
        ['id' => 1415, 'name' => 'KABUPATEN PUNCAK JAYA'],
        ['id' => 1416, 'name' => 'KABUPATEN TOLIKARA'],
        ['id' => 1417, 'name' => 'KABUPATEN WAROPEN'],
        ['id' => 1418, 'name' => 'KABUPATEN YAHUKIMO'],
    ],
    // 15. BALI
    15 => [
        ['id' => 1501, 'name' => 'KOTA DENPASAR'],
        ['id' => 1502, 'name' => 'KABUPATEN BADUNG'],
        ['id' => 1503, 'name' => 'KABUPATEN BANGLI'],
        ['id' => 1504, 'name' => 'KABUPATEN BULELENG'],
        ['id' => 1505, 'name' => 'KABUPATEN GIANYAR'],
        ['id' => 1506, 'name' => 'KABUPATEN JEMBRANA'],
        ['id' => 1507, 'name' => 'KABUPATEN KARANGASEM'],
        ['id' => 1508, 'name' => 'KABUPATEN KLUNGKUNG'],
        ['id' => 1509, 'name' => 'KABUPATEN TABANAN'],
    ],
    // 16. SUMATERA UTARA
    16 => [
        ['id' => 1601, 'name' => 'KOTA MEDAN'],
        ['id' => 1602, 'name' => 'KOTA BINJAI'],
        ['id' => 1603, 'name' => 'KOTA PEMATANGSIANTAR'],
        ['id' => 1604, 'name' => 'KOTA TEBING TINGGI'],
        ['id' => 1605, 'name' => 'KOTA TANJUNGBALAI'],
        ['id' => 1606, 'name' => 'KOTA SIBOLGA'],
        ['id' => 1607, 'name' => 'KOTA PADANGSIDIMPUAN'],
        ['id' => 1608, 'name' => 'KOTA GUNUNGSITOLI'],
        ['id' => 1609, 'name' => 'KABUPATEN DELI SERDANG'],
        ['id' => 1610, 'name' => 'KABUPATEN LANGKAT'],
        ['id' => 1611, 'name' => 'KABUPATEN KARO'],
        ['id' => 1612, 'name' => 'KABUPATEN SIMALUNGUN'],
        ['id' => 1613, 'name' => 'KABUPATEN ASAHAN'],
        ['id' => 1614, 'name' => 'KABUPATEN LABUHANBATU'],
        ['id' => 1615, 'name' => 'KABUPATEN TOBA'],
    ],
    // 17. GORONTALO
    17 => [
        ['id' => 1701, 'name' => 'KOTA GORONTALO'],
        ['id' => 1702, 'name' => 'KABUPATEN GORONTALO'],
        ['id' => 1703, 'name' => 'KABUPATEN BONE BOLANGO'],
        ['id' => 1704, 'name' => 'KABUPATEN BOALEMO'],
        ['id' => 1705, 'name' => 'KABUPATEN POHUWATO'],
        ['id' => 1706, 'name' => 'KABUPATEN GORONTALO UTARA'],
    ],
    // 18. JAWA TIMUR
    18 => [
        ['id' => 1801, 'name' => 'KOTA SURABAYA'],
        ['id' => 1802, 'name' => 'KOTA MALANG'],
        ['id' => 1803, 'name' => 'KOTA KEDIRI'],
        ['id' => 1804, 'name' => 'KOTA MADIUN'],
        ['id' => 1805, 'name' => 'KOTA BLITAR'],
        ['id' => 1806, 'name' => 'KOTA MOJOKERTO'],
        ['id' => 1807, 'name' => 'KOTA PASURUAN'],
        ['id' => 1808, 'name' => 'KOTA PROBOLINGGO'],
        ['id' => 1809, 'name' => 'KOTA BATU'],
        ['id' => 1810, 'name' => 'KABUPATEN SIDOARJO'],
        ['id' => 1811, 'name' => 'KABUPATEN GRESIK'],
        ['id' => 1812, 'name' => 'KABUPATEN JEMBER'],
        ['id' => 1813, 'name' => 'KABUPATEN BANYUWANGI'],
        ['id' => 1814, 'name' => 'KABUPATEN BOJONEGORO'],
        ['id' => 1815, 'name' => 'KABUPATEN TUBAN'],
        ['id' => 1816, 'name' => 'KABUPATEN LAMONGAN'],
        ['id' => 1817, 'name' => 'KABUPATEN PAMEKASAN'],
        ['id' => 1818, 'name' => 'KABUPATEN SUMENEP'],
    ],
    // 19. DI YOGYAKARTA
    19 => [
        ['id' => 260, 'name' => 'BANTUL'],
        ['id' => 263, 'name' => 'GUNUNG KIDUL'],
        ['id' => 262, 'name' => 'KULON PROGO'],
        ['id' => 261, 'name' => 'SLEMAN'],
        ['id' => 259, 'name' => 'YOGYAKARTA'],
    ],
    // 20. SULAWESI TENGGARA
    20 => [
        ['id' => 2001, 'name' => 'KOTA KENDARI'],
        ['id' => 2002, 'name' => 'KOTA BAUBAU'],
        ['id' => 2003, 'name' => 'KABUPATEN KOLAKA'],
        ['id' => 2004, 'name' => 'KABUPATEN KONAWE'],
        ['id' => 2005, 'name' => 'KABUPATEN MUNA'],
        ['id' => 2006, 'name' => 'KABUPATEN BUTON'],
        ['id' => 2007, 'name' => 'KABUPATEN WAKATOBI'],
    ],
    // 21. NTT
    21 => [
        ['id' => 2101, 'name' => 'KOTA KUPANG'],
        ['id' => 2102, 'name' => 'KABUPATEN MANGGARAI BARAT (LABUAN BAJO)'],
        ['id' => 2103, 'name' => 'KABUPATEN SIKKA (MAUMERE)'],
        ['id' => 2104, 'name' => 'KABUPATEN ENDE'],
        ['id' => 2105, 'name' => 'KABUPATEN FLORES TIMUR'],
        ['id' => 2106, 'name' => 'KABUPATEN ALOR'],
        ['id' => 2107, 'name' => 'KABUPATEN SUMBA TIMUR'],
        ['id' => 2108, 'name' => 'KABUPATEN SUMBA BARAT'],
        ['id' => 2109, 'name' => 'KABUPATEN BELU'],
        ['id' => 2110, 'name' => 'KABUPATEN TIMOR TENGAH SELATAN'],
    ],
    // 22. SULAWESI UTARA
    22 => [
        ['id' => 2201, 'name' => 'KOTA MANADO'],
        ['id' => 2202, 'name' => 'KOTA BITUNG'],
        ['id' => 2203, 'name' => 'KOTA TOMOHON'],
        ['id' => 2204, 'name' => 'KOTA KOTAMOBAGU'],
        ['id' => 2205, 'name' => 'KABUPATEN MINAHASA'],
        ['id' => 2206, 'name' => 'KABUPATEN MINAHASA UTARA'],
        ['id' => 2207, 'name' => 'KABUPATEN MINAHASA SELATAN'],
        ['id' => 2208, 'name' => 'KABUPATEN BOLAANG MONGONDOW'],
    ],
    // 23. SUMATERA BARAT
    23 => [
        ['id' => 2301, 'name' => 'KOTA PADANG'],
        ['id' => 2302, 'name' => 'KOTA BUKITTINGGI'],
        ['id' => 2303, 'name' => 'KOTA PAYAKUMBUH'],
        ['id' => 2304, 'name' => 'KOTA PARIAMAN'],
        ['id' => 2305, 'name' => 'KOTA SOLOK'],
        ['id' => 2306, 'name' => 'KOTA SAWAHLUNTO'],
        ['id' => 2307, 'name' => 'KOTA PADANG PANJANG'],
        ['id' => 2308, 'name' => 'KABUPATEN AGAM'],
        ['id' => 2309, 'name' => 'KABUPATEN PESISIR SELATAN'],
        ['id' => 2310, 'name' => 'KABUPATEN TANAH DATAR'],
    ],
    // 24. BANGKA BELITUNG
    24 => [
        ['id' => 2401, 'name' => 'KOTA PANGKALPINANG'],
        ['id' => 2402, 'name' => 'KABUPATEN BANGKA'],
        ['id' => 2403, 'name' => 'KABUPATEN BANGKA BARAT'],
        ['id' => 2404, 'name' => 'KABUPATEN BANGKA TENGAH'],
        ['id' => 2405, 'name' => 'KABUPATEN BANGKA SELATAN'],
        ['id' => 2406, 'name' => 'KABUPATEN BELITUNG'],
        ['id' => 2407, 'name' => 'KABUPATEN BELITUNG TIMUR'],
    ],
    // 25. RIAU
    25 => [
        ['id' => 2501, 'name' => 'KOTA PEKANBARU'],
        ['id' => 2502, 'name' => 'KOTA DUMAI'],
        ['id' => 2503, 'name' => 'KABUPATEN BENGKALIS'],
        ['id' => 2504, 'name' => 'KABUPATEN INDRAGIRI HILIR'],
        ['id' => 2505, 'name' => 'KABUPATEN INDRAGIRI HULU'],
        ['id' => 2506, 'name' => 'KABUPATEN KAMPAR'],
        ['id' => 2507, 'name' => 'KABUPATEN PELALAWAN'],
        ['id' => 2508, 'name' => 'KABUPATEN ROKAN HILIR'],
        ['id' => 2509, 'name' => 'KABUPATEN ROKAN HULU'],
        ['id' => 2510, 'name' => 'KABUPATEN SIAK'],
        ['id' => 2511, 'name' => 'KABUPATEN KEPULAUAN MERANTI'],
    ],
    // 26. SUMATERA SELATAN
    26 => [
        ['id' => 2601, 'name' => 'KOTA PALEMBANG'],
        ['id' => 2602, 'name' => 'KOTA PRABUMULIH'],
        ['id' => 2603, 'name' => 'KOTA LUBUKLINGGAU'],
        ['id' => 2604, 'name' => 'KOTA PAGAR ALAM'],
        ['id' => 2605, 'name' => 'KABUPATEN BANYUASIN'],
        ['id' => 2606, 'name' => 'KABUPATEN LAHAT'],
        ['id' => 2607, 'name' => 'KABUPATEN MUARA ENIM'],
        ['id' => 2608, 'name' => 'KABUPATEN MUSI BANYUASIN'],
        ['id' => 2609, 'name' => 'KABUPATEN OGAN ILIR'],
        ['id' => 2610, 'name' => 'KABUPATEN OGAN KOMERING ILIR'],
    ],
    // 27. SULAWESI TENGAH
    27 => [
        ['id' => 2701, 'name' => 'KOTA PALU'],
        ['id' => 2702, 'name' => 'KABUPATEN BANGGAI'],
        ['id' => 2703, 'name' => 'KABUPATEN DONGGALA'],
        ['id' => 2704, 'name' => 'KABUPATEN MOROWALI'],
        ['id' => 2705, 'name' => 'KABUPATEN PARIGI MOUTONG'],
        ['id' => 2706, 'name' => 'KABUPATEN POSO'],
        ['id' => 2707, 'name' => 'KABUPATEN TOLI-TOLI'],
    ],
    // 28. KALIMANTAN BARAT
    28 => [
        ['id' => 2801, 'name' => 'KOTA PONTIANAK'],
        ['id' => 2802, 'name' => 'KOTA SINGKAWANG'],
        ['id' => 2803, 'name' => 'KABUPATEN BENGKAYANG'],
        ['id' => 2804, 'name' => 'KABUPATEN KAPUAS HULU'],
        ['id' => 2805, 'name' => 'KABUPATEN KETAPANG'],
        ['id' => 2806, 'name' => 'KABUPATEN KUBU RAYA'],
        ['id' => 2807, 'name' => 'KABUPATEN MEMPAWAH'],
        ['id' => 2808, 'name' => 'KABUPATEN SAMBAS'],
        ['id' => 2809, 'name' => 'KABUPATEN SANGGAU'],
        ['id' => 2810, 'name' => 'KABUPATEN SINTANG'],
    ],
    // 29. PAPUA BARAT
    29 => [
        ['id' => 2901, 'name' => 'KOTA SORONG'],
        ['id' => 2902, 'name' => 'KABUPATEN MANOKWARI'],
        ['id' => 2903, 'name' => 'KABUPATEN FAKFAK'],
        ['id' => 2904, 'name' => 'KABUPATEN KAIMANA'],
        ['id' => 2905, 'name' => 'KABUPATEN RAJA AMPAT'],
        ['id' => 2906, 'name' => 'KABUPATEN SORONG'],
        ['id' => 2907, 'name' => 'KABUPATEN SORONG SELATAN'],
        ['id' => 2908, 'name' => 'KABUPATEN TELUK BINTUNI'],
        ['id' => 2909, 'name' => 'KABUPATEN TELUK WONDAMA'],
    ],
    // 30. LAMPUNG
    30 => [
        ['id' => 3001, 'name' => 'KOTA BANDAR LAMPUNG'],
        ['id' => 3002, 'name' => 'KOTA METRO'],
        ['id' => 3003, 'name' => 'KABUPATEN LAMPUNG SELATAN'],
        ['id' => 3004, 'name' => 'KABUPATEN LAMPUNG TENGAH'],
        ['id' => 3005, 'name' => 'KABUPATEN LAMPUNG TIMUR'],
        ['id' => 3006, 'name' => 'KABUPATEN LAMPUNG UTARA'],
        ['id' => 3007, 'name' => 'KABUPATEN PESAWARAN'],
        ['id' => 3008, 'name' => 'KABUPATEN PRINGSEWU'],
        ['id' => 3009, 'name' => 'KABUPATEN TANGGAMUS'],
        ['id' => 3010, 'name' => 'KABUPATEN TULANG BAWANG'],
    ],
    // 31. KALIMANTAN UTARA
    31 => [
        ['id' => 3101, 'name' => 'KOTA TARAKAN'],
        ['id' => 3102, 'name' => 'KABUPATEN BULUNGAN'],
        ['id' => 3103, 'name' => 'KABUPATEN MALINAU'],
        ['id' => 3104, 'name' => 'KABUPATEN NUNUKAN'],
        ['id' => 3105, 'name' => 'KABUPATEN TANA TIDUNG'],
    ],
    // 32. MALUKU UTARA
    32 => [
        ['id' => 3201, 'name' => 'KOTA TERNATE'],
        ['id' => 3202, 'name' => 'KOTA TIDORE KEPULAUAN'],
        ['id' => 3203, 'name' => 'KABUPATEN HALMAHERA BARAT'],
        ['id' => 3204, 'name' => 'KABUPATEN HALMAHERA SELATAN'],
        ['id' => 3205, 'name' => 'KABUPATEN HALMAHERA TENGAH'],
        ['id' => 3206, 'name' => 'KABUPATEN HALMAHERA TIMUR'],
        ['id' => 3207, 'name' => 'KABUPATEN HALMAHERA UTARA'],
        ['id' => 3208, 'name' => 'KABUPATEN KEPULAUAN SULA'],
    ],
    // 33. SULAWESI SELATAN
    33 => [
        ['id' => 3301, 'name' => 'KOTA MAKASSAR'],
        ['id' => 3302, 'name' => 'KOTA PALOPO'],
        ['id' => 3303, 'name' => 'KOTA PAREPARE'],
        ['id' => 3304, 'name' => 'KABUPATEN BANTAENG'],
        ['id' => 3305, 'name' => 'KABUPATEN BONE'],
        ['id' => 3306, 'name' => 'KABUPATEN BULUKUMBA'],
        ['id' => 3307, 'name' => 'KABUPATEN GOWA'],
        ['id' => 3308, 'name' => 'KABUPATEN LUWU'],
        ['id' => 3309, 'name' => 'KABUPATEN MAROS'],
        ['id' => 3310, 'name' => 'KABUPATEN PINRANG'],
        ['id' => 3311, 'name' => 'KABUPATEN TANA TORAJA'],
        ['id' => 3312, 'name' => 'KABUPATEN WAJO'],
    ],
    // 34. SULAWESI BARAT
    34 => [
        ['id' => 3401, 'name' => 'KABUPATEN MAMUJU'],
        ['id' => 3402, 'name' => 'KABUPATEN MAJENE'],
        ['id' => 3403, 'name' => 'KABUPATEN MAMASA'],
        ['id' => 3404, 'name' => 'KABUPATEN PASANGKAYU'],
        ['id' => 3405, 'name' => 'KABUPATEN POLEWALI MANDAR'],
    ],

        ];

        return $map[$provinceId] ?? [];
    }

    /**
     * GET /api/shipping/cities?province_id={id}&search={keyword}
     * Daftar kota berdasarkan provinsi atau pencarian
     */
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id', '');
        $search     = $request->query('search', '');

        if (!empty($provinceId)) {
            $cacheKey = "rajaongkir_cities_prov_{$provinceId}";
            $cached = Cache::get($cacheKey);
            if (!empty($cached) && is_array($cached)) {
                return response()->json($cached);
            }

            try {
                $res = Http::withoutVerifying()
                    ->withHeaders(['key' => $this->apiKey])
                    ->timeout(10)
                    ->get("{$this->baseUrl}/destination/city/{$provinceId}");

                if ($res->successful()) {
                    $items = $res->json('data', []);
                    if (!empty($items) && is_array($items)) {
                        usort($items, fn($a, $b) => strcmp($a['name'], $b['name']));
                        Cache::put($cacheKey, $items, 86400 * 30);
                        return response()->json($items);
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }

            // Fallback ke data statis jika API eksternal offline/timeout
            $fallback = self::staticCitiesForProvince((int) $provinceId);
            if (!empty($fallback)) {
                return response()->json($fallback);
            }

            return response()->json([]);
        }

        if (!empty($search)) {
            try {
                $res = Http::withoutVerifying()
                    ->withHeaders(['key' => $this->apiKey])
                    ->timeout(10)
                    ->get("{$this->baseUrl}/destination/domestic-destination", ['search' => $search]);

                if ($res->successful()) {
                    return response()->json($res->json('data', []));
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([]);
    }

        /**
     * POST /api/shipping/cost
     * Hitung ongkos kirim secara live dari RajaOngkir dengan auto-fallback zona logistik
     * Body: { destination_city_id?, city?, province_id?, weight?, courier? }
     */
    public function cost(Request $request)
    {
        $data = $request->validate([
            'destination_city_id' => 'nullable',
            'city'                => 'nullable|string',
            'province_id'         => 'nullable',
            'weight'              => 'nullable|integer|min:1',
            'courier'             => 'nullable|string',
        ]);

        $destinationId = $data['destination_city_id'] ?? null;
        $cityName      = $data['city'] ?? null;
        $provinceId    = $data['province_id'] ?? null;
        $weight        = $data['weight'] ?? 500;

        // Jika ID belum ada tapi nama kota dikirim, coba resolusi ID
        if (empty($destinationId) && !empty($cityName)) {
            $destinationId = $this->resolveCityId($cityName);
        }

        // Cek Cache terlebih dahulu jika ada ID tujuan
        $cacheKey = !empty($destinationId) ? "rajaongkir_cost_{$this->originCityId}_{$destinationId}_{$weight}" : null;
        if ($cacheKey && Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (!empty($cached) && is_array($cached)) {
                return response()->json([
                    'success'        => true,
                    'destination_id' => $destinationId,
                    'services'       => $cached,
                    'source'         => 'live_cache'
                ]);
            }
        }

        $couriers = !empty($data['courier']) && $data['courier'] !== 'all' 
            ? [$data['courier']] 
            : ['jnt', 'jne', 'spx', 'sap', 'pos', 'lion', 'sicepat', 'ide', 'anteraja'];

        $services = [];

        // Jika ada destinationId dan belum di-cache, coba panggil API Komerce
        if (!empty($destinationId)) {
            try {
                $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($couriers, $destinationId, $weight) {
                    return array_map(function ($courier) use ($pool, $destinationId, $weight) {
                        return $pool->as($courier)->withoutVerifying()
                            ->withHeaders(['key' => $this->apiKey])
                            ->timeout(6)
                            ->asForm()
                            ->post("{$this->baseUrl}/calculate/domestic-cost", [
                                'origin'      => $this->originCityId,
                                'destination' => (int) $destinationId,
                                'weight'      => $weight,
                                'courier'     => $courier,
                            ]);
                    }, $couriers);
                });

                foreach ($responses as $courier => $res) {
                    if (!($res instanceof \Illuminate\Http\Client\Response) || !$res->successful()) {
                        continue;
                    }

                    $items = $res->json('data', []) ?: [];
                    foreach ($items as $item) {
                        $serviceCode = $item['service'] ?? 'REG';
                        $rawDesc     = $item['description'] ?? '';

                        // Saring layanan kargo / truk untuk paket pakaian < 10kg
                        if ($weight < 10000) {
                            if (str_starts_with(strtoupper($serviceCode), 'JTR')) continue;
                            if (str_contains($serviceCode, '>') || str_contains($serviceCode, '<')) continue;
                            if (stripos($rawDesc, 'trucking') !== false) continue;
                            if (stripos($rawDesc, 'cargo') !== false) continue;
                        }

                        $code           = strtolower($item['code'] ?? $courier);
                        $cleanServiceId = $code . '-' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $serviceCode));

                        // Label nama ekspedisi
                        $courierBrand = match ($code) {
                            'jne'      => 'JNE',
                            'jnt'      => 'J&T',
                            'pos'      => 'POS',
                            'lion'     => 'Lion Parcel',
                            'sap'      => 'SAP Express',
                            'spx'      => 'Shopee Express',
                            'sicepat'  => 'SiCepat',
                            'ide'      => 'ID Express',
                            'anteraja' => 'Anteraja',
                            default    => strtoupper($code),
                        };

                        // Rapikan etd
                        $rawEtd = trim($item['etd'] ?? '');
                        if (empty($rawEtd) || $rawEtd === '-') {
                            $etdFormatted = '2-3 hari';
                        } else {
                            $etdFormatted = preg_replace('/\s*day[s]?/i', ' hari', $rawEtd);
                            $etdFormatted = preg_replace('/(\d+)-()\s*hari/i', ' hari', $etdFormatted);
                        }

                        // Rapikan deskripsi jika berupa kode angka (seperti POS 240/447)
                        $descText = $rawDesc;
                        if (empty($descText) || is_numeric($descText)) {
                            $descText = stripos($serviceCode, 'next') !== false || stripos($serviceCode, 'ons') !== false
                                ? 'Layanan Kilat / Next Day'
                                : 'Layanan Reguler Standar';
                        }

                        $services[] = [
                            'id'          => $cleanServiceId,
                            'courier'     => $courierBrand,
                            'service'     => $serviceCode,
                            'name'        => "{$courierBrand} {$serviceCode}",
                            'description' => "{$descText} · Est. {$etdFormatted}",
                            'cost'        => (int) ($item['cost'] ?? 0),
                            'etd'         => $etdFormatted,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Jika API eksternal sukses memberikan layanan, simpan di cache
        if (!empty($services)) {
            usort($services, fn($a, $b) => $a['cost'] <=> $b['cost']);
            if ($cacheKey) {
                Cache::put($cacheKey, $services, 86400); // 24 jam
            }
            return response()->json([
                'success'        => true,
                'destination_id' => $destinationId,
                'services'       => $services,
                'source'         => 'live_api'
            ]);
        }

        // Fallback realistis sesuai zona wilayah & berat barang jika API offline atau quota 429
        $fallbackServices = $this->fallbackServicesForLocation((int) $provinceId, $cityName, $weight);

        return response()->json([
            'success'        => true,
            'destination_id' => $destinationId,
            'services'       => $fallbackServices,
            'source'         => 'zone_fallback',
            'message'        => 'Tarif estimasi realistik sesuai wilayah logistik pengiriman.'
        ]);
    }

    /**
     * Tarif pengiriman realistis berbasis zona wilayah Indonesia & berat paket (kg)
     * Digunakan saat API eksternal mencapai batas kuota harian atau offline
     */
    public function fallbackServicesForLocation(?int $provinceId, ?string $cityName = null, int $weight = 500): array
    {
        $provId = (int) $provinceId;
        $name   = strtolower((string) $cityName);
        $kg     = max(1, (int) ceil($weight / 1000));

        // Zona 7: Papua & Papua Barat (Papua Tengah, Selatan, Pegunungan)
        if (in_array($provId, [14, 29]) || str_contains($name, 'papua') || str_contains($name, 'jayapura') || str_contains($name, 'merauke') || str_contains($name, 'mimika') || str_contains($name, 'timika') || str_contains($name, 'sorong') || str_contains($name, 'manokwari') || str_contains($name, 'biak') || str_contains($name, 'nabire')) {
            $rates = [
                ['pos',  'POS',         'Pos Reguler', 'POS Indonesia Reguler',     'Layanan Standar Pos ke Indonesia Timur', 95000,  '5-9 hari'],
                ['lion', 'Lion Parcel', 'REGPACK',     'Lion Parcel REGPACK',       'Layanan Reguler Kargo Udara',            105000, '5-8 hari'],
                ['jne',  'JNE',         'REG',         'JNE Regular',               'Layanan Reguler Standar',                110000, '4-7 hari'],
                ['jnt',  'J&T',         'EZ',          'J&T Express EZ',            'Layanan Reguler Standar',                115000, '4-7 hari'],
            ];
        }
        // Zona 6: Maluku & Maluku Utara, NTT, Kaltara
        elseif (in_array($provId, [2, 32, 21, 31]) || str_contains($name, 'maluku') || str_contains($name, 'ambon') || str_contains($name, 'ternate') || str_contains($name, 'kupang') || str_contains($name, 'tarakan')) {
            $rates = [
                ['pos',  'POS',         'Pos Reguler', 'POS Indonesia Reguler',     'Layanan Standar Pos',                    60000, '4-7 hari'],
                ['lion', 'Lion Parcel', 'REGPACK',     'Lion Parcel REGPACK',       'Layanan Reguler Hemat',                  65000, '4-6 hari'],
                ['jne',  'JNE',         'REG',         'JNE Regular',               'Layanan Reguler Standar',                68000, '3-5 hari'],
                ['jnt',  'J&T',         'EZ',          'J&T Express EZ',            'Layanan Reguler Standar',                72000, '3-5 hari'],
            ];
        }
        // Zona 5: Sulawesi & Kalimantan & Sumut, Aceh, Riau
        elseif (in_array($provId, [33, 22, 27, 20, 34, 17, 28, 3, 4, 7, 16, 9, 25, 8]) || str_contains($name, 'makassar') || str_contains($name, 'manado') || str_contains($name, 'balikpapan') || str_contains($name, 'samarinda') || str_contains($name, 'banjarmasin') || str_contains($name, 'pontianak') || str_contains($name, 'medan') || str_contains($name, 'batam') || str_contains($name, 'pekanbaru') || str_contains($name, 'aceh')) {
            $rates = [
                ['lion',    'Lion Parcel', 'REGPACK',     'Lion Parcel REGPACK',       'Layanan Reguler Hemat',                  38000, '3-5 hari'],
                ['pos',     'POS',         'Pos Reguler', 'POS Indonesia Reguler',     'Layanan Standar Pos',                    39000, '3-5 hari'],
                ['jnt',     'J&T',         'EZ',          'J&T Express EZ',            'Layanan Reguler Standar',                42000, '2-4 hari'],
                ['sicepat', 'SiCepat',     'SIUNTUNG',    'SiCepat Express',           'Layanan Reguler Cepat',                  44000, '2-3 hari'],
                ['jne',     'JNE',         'REG',         'JNE Regular',               'Layanan Reguler Standar',                45000, '2-4 hari'],
            ];
        }
        // Zona 4: Bali, NTB, Lampung, Sumsel, Jambi, Bengkulu, Sumbar, Babel
        elseif (in_array($provId, [15, 1, 30, 26, 13, 6, 23, 24]) || str_contains($name, 'bali') || str_contains($name, 'denpasar') || str_contains($name, 'lombok') || str_contains($name, 'mataram') || str_contains($name, 'palembang') || str_contains($name, 'lampung') || str_contains($name, 'padang') || str_contains($name, 'jambi')) {
            $rates = [
                ['lion',    'Lion Parcel',    'REGPACK',      'Lion Parcel REGPACK',       'Layanan Reguler Hemat',                  25000, '2-4 hari'],
                ['jnt',     'J&T',            'EZ',           'J&T Express EZ',            'Layanan Reguler Standar',                26000, '2-3 hari'],
                ['spx',     'Shopee Express', 'SPX Standard', 'Shopee Express SPX',        'Layanan Reguler Hemat',                  26000, '2-3 hari'],
                ['pos',     'POS',            'Pos Reguler',  'POS Indonesia Reguler',     'Layanan Standar Pos',                    26000, '2-4 hari'],
                ['sicepat', 'SiCepat',        'SIUNTUNG',     'SiCepat Express',           'Layanan Reguler Cepat',                  27000, '2-3 hari'],
                ['jne',     'JNE',            'REG',          'JNE Regular',               'Layanan Reguler Standar',                28000, '2-3 hari'],
            ];
        }
        // Zona 3: Jawa Tengah, DI Yogyakarta, Jawa Timur
        elseif (in_array($provId, [12, 19, 18]) || str_contains($name, 'jawa tengah') || str_contains($name, 'jogja') || str_contains($name, 'yogyakarta') || str_contains($name, 'semarang') || str_contains($name, 'solo') || str_contains($name, 'surabaya') || str_contains($name, 'malang') || str_contains($name, 'banyumas')) {
            $rates = [
                ['jnt',     'J&T',            'EZ',           'J&T Express EZ',            'Layanan Reguler Standar',                16000, '2-3 hari'],
                ['spx',     'Shopee Express', 'SPX Standard', 'Shopee Express SPX',        'Layanan Reguler Hemat',                  16000, '2-3 hari'],
                ['jne',     'JNE',            'REG',          'JNE Regular',               'Layanan Reguler Standar',                17000, '2-3 hari'],
                ['sicepat', 'SiCepat',        'SIUNTUNG',     'SiCepat Express',           'Layanan Reguler Cepat',                  18000, '1-2 hari'],
                ['pos',     'POS',            'Pos Reguler',  'POS Indonesia Reguler',     'Layanan Standar Pos',                    18000, '2-3 hari'],
                ['sap',     'SAP Express',    'UDRREG',       'SAP Express Reguler',       'Layanan Standar',                        29500, '2-4 hari'],
                ['sap',     'SAP Express',    'UDRONS',       'SAP Express ONS (Next Day)','Layanan Kilat 1 Hari Tiba',              38500, '1 hari'],
            ];
        }
        // Zona 2: Jawa Barat, Banten
        elseif (in_array($provId, [5, 11]) || str_contains($name, 'jawa barat') || str_contains($name, 'bandung') || str_contains($name, 'cirebon') || str_contains($name, 'serang')) {
            $rates = [
                ['jnt',     'J&T',            'EZ',           'J&T Express EZ',            'Layanan Reguler Standar',                12000, '1-2 hari'],
                ['spx',     'Shopee Express', 'SPX Standard', 'Shopee Express SPX',        'Layanan Reguler Hemat',                  12000, '1-2 hari'],
                ['jne',     'JNE',            'REG',          'JNE Regular',               'Layanan Reguler Standar',                13000, '1-2 hari'],
                ['sicepat', 'SiCepat',        'SIUNTUNG',     'SiCepat Express',           'Layanan Reguler Cepat',                  13000, '1-2 hari'],
                ['pos',     'POS',            'Pos Reguler',  'POS Indonesia Reguler',     'Layanan Standar Pos',                    13000, '1-3 hari'],
            ];
        }
        // Zona 1: DKI Jakarta & Sekitarnya (Origin Toko Sweet Dreams)
        else {
            $rates = [
                ['jnt',     'J&T',            'EZ',           'J&T Express EZ',            'Layanan Reguler Standar',                10000, '1-2 hari'],
                ['spx',     'Shopee Express', 'SPX Standard', 'Shopee Express SPX',        'Layanan Reguler Hemat',                  10000, '1-2 hari'],
                ['jne',     'JNE',            'REG',          'JNE Regular',               'Layanan Reguler Standar',                10000, '1-2 hari'],
                ['sicepat', 'SiCepat',        'SIUNTUNG',     'SiCepat Express',           'Layanan Reguler Cepat',                  11000, '1 hari'],
                ['gosend',  'Gojek',          'Same Day',     'GoSend Same Day',           'Estimasi tiba dalam 2–4 jam',            22000, 'Hari ini'],
            ];
        }

        $services = [];
        foreach ($rates as $r) {
            $services[] = [
                'id'          => $r[0] . '-' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $r[2])),
                'courier'     => $r[1],
                'service'     => $r[2],
                'name'        => $r[3],
                'description' => $r[4] . ' · Est. ' . $r[6],
                'cost'        => (int) ($r[5] * $kg),
                'etd'         => $r[6],
            ];
        }

        usort($services, fn($a, $b) => $a['cost'] <=> $b['cost']);
        return $services;
    }

    /**
     * Resolusi nama kota menjadi ID tujuan
     */
    protected function resolveCityId(string $cityName): ?int
    {
        $clean = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $cityName));
        if (empty($clean)) return null;

        try {
            $res = Http::withoutVerifying()
                ->withHeaders(['key' => $this->apiKey])
                ->timeout(6)
                ->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $clean,
                ]);

            if ($res->successful()) {
                $items = $res->json('data') ?: [];
                foreach ($items as $item) {
                    if (stripos($item['city_name'] ?? '', $clean) !== false) {
                        return (int) ($item['id'] ?? null);
                    }
                }
                if (!empty($items[0]['id'])) {
                    return (int) $items[0]['id'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }
}
