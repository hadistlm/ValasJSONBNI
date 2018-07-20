<?
class GetValasBNI
{   
    /**
     * initialize class to open teh run function
     */
    public function __construct(){
        self::run();
    }

    /**
     * Run the program
     */
    public static function run(){
        // to see the actual data structure
        // echo '<pre>';
        echo json_decode($this->processConvert());
    }

    /**
     * Function to get the web view
     */
    public function cURLGet( $url ){
        $data = curl_init();
        curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($data, CURLOPT_URL, $url );
        curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        $hasil = curl_exec($data);
        curl_close($data);

        return ($hasil) ? $hasil : '' ;   
    }

    /**
     * Function to retrieve the final Data
     */
    public function processConvert(){
        $data = $this->cURLGet('http://www.bni.co.id/id-id/beranda/informasivalas');

        if (empty($data)) :
            return json_encode(false);
        endif;

        $pecah = explode('<tbody>', $data);
        $pecah2 = explode ('<td class="align-center">',$pecah[3]);

        foreach ($pecah2 as $value) {
            if ( strlen($value) < 10 ) continue;
            $kursName = substr($value, 0, 3);
            $output[$kursName] = explode(',00', $value);
        }

        foreach ($output as $key => $value) :
            $valasList = array();

            foreach ($value as $keyValas => $valas) :
                if (strlen($valas) < 20) continue;

                if ($keyValas == 0) {
                    $explodeKey = str_split($valas, 2);
                    $valasList['jual'] = $explodeKey[16].$explodeKey[17].$explodeKey[18]; 
                }else {
                    $explodeKey = str_split($valas, 7);
                    $valasList['beli'] = explode('>', $explodeKey[4] )[1];
                }
            endforeach;

            $final[$key] = $valasList;
        endforeach;

        return ($final) ? json_encode($final) : array() ;      
    }  
}