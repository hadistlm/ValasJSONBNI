<?php

class GetValasBNI
{
    /**
     * Run the program
     */
    public static function run( $vendor = 'default', $valas = 'all' ){
        $data   = self::processConvert($vendor);
        $return = array();

        if ( $valas != 'all' ) {
            $selected          = strtoupper($valas);
            $return[$selected] = $data->$selected;
        }else {
            $return = $data;
        }

        return ($return) ? json_encode($return) : json_encode(FALSE) ;
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
    public function processConvert($vendor){
        // get the link based on vendor
        switch ($vendor) {
            case 'syariah':
                $data = self::cURLGet('https://www.bnisyariah.co.id/id-id/beranda/informasivalas');
                break;
            
            default:
                $data = self::cURLGet('http://www.bni.co.id/id-id/beranda/informasivalas');
                break;
        }

        if (empty($data)) :
            return false;
        endif;

        // function get valas from BNI Syariah
        if ($vendor == 'syariah') :
            $pecah  = explode('<table class="table table-striped angrid-grid table_info_counter">', $data);
            $pecah1 = explode('<tr>', $pecah[1]);

            // explode by main table
            foreach ($pecah1 as $value) :
                $pecah3[] = explode('<td class="align-center">', $value);
            endforeach;

            // explode by their cells
            foreach ($pecah3 as $key => $gets) :
                if ($key == 0 || $key == 1) continue;
                $pecah4[] = explode('<td class="align-right">', $gets[1]);
            endforeach;

            // explode final
            foreach ($pecah4 as $key => $value) :
                $name = str_replace('</td>', '', $value[0]);
                $jual = (int) filter_var($value[1], FILTER_SANITIZE_NUMBER_INT);
                $beli = (int) filter_var($value[2], FILTER_SANITIZE_NUMBER_INT);

                $final[$name] = array('jual' => sprintf('%.2f', $jual / 100), 'beli' => sprintf('%.2f', $beli / 100));
            endforeach;

        // function get valas from BNI
        else: 
            $pecah = explode('<tbody>', $data);
            $pecah2 = explode ('<td class="align-center">',$pecah[3]);

            foreach ($pecah2 as $value) :
                if ( strlen($value) < 10 ) continue;
                
                $kursName = substr($value, 0, 3);
                $output[$kursName] = explode(',00', $value);
            endforeach;

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
        endif;

        return ($final) ? (object) $final : array() ;      
    }  
}

?>