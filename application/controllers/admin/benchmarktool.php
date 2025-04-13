<?php

class Benchmarktool extends Admin_Controller 
{

	public function __construct(){
		parent::__construct();
        
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        ini_set('display_errors',1);
        error_reporting(E_ALL);
        //$this->output->enable_profiler(TRUE);
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index()
	{
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/dashboard');
            exit();
        }
        
        if($this->config->item('enable_benchmark_tools') !=TRUE)
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/dashboard');
            exit();
        }

        // Load view
            $this->data['subview'] = 'admin/benchmarktool/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    
    private function in_path($array, $file_path)
    {
        foreach($array as $skip_path)
        {
            if(strpos($file_path, $skip_path) !== FALSE)
            {
                return TRUE;
            }
            elseif(strpos($file_path, str_replace('/', '\\', $skip_path)) !== FALSE)
            {
                return TRUE;
            }
        }
        
        return false;
    }

    private function clear_dir ($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            if(is_dir("$dir/$file")) {
                $this->clear_dir("$dir/$file"); 
                rmdir("$dir/$file");
            }
            else {
                unlink("$dir/$file");
            } 
        }
    }

    function remove_cache () {

        /* template */
        $this->clear_dir(FCPATH.'/templates/'.$this->data['settings']['template'].'/assets/cache/');

        /* template */
        $this->clear_dir(FCPATH.'/application/cache/');
          
        $this->session->set_flashdata('message', 
                '<p class="label label-success validation">'.lang_check('Cache removed').'</p>');
        redirect('admin/benchmarktool/index');
    }

    public function unused_images($par='0')
    {
        $files_for_skip = array('index.html', 'no_image.jpg', 'treefield_');

        // Get all images from file table
        $this->load->model('file_m');

        $files_db = $this->file_m->get();
        $files_db_filename = array();

        // Get only filenames
        foreach($files_db as $file)
        {
            $files_db_filename[$file->filename] = $file->repository_id;
        }

        //dump($files_db_filename);

        // Get files from files folder
        if ($handle = opendir(FCPATH.'/files')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {

                    $skip=false;
                    foreach($files_for_skip as $val)
                    {
                        if(substr_count($entry, $val) > 0)
                        {
                            $skip=true;
                            break;
                        }
                    }
                    if($skip)continue;

                    if(is_file(FCPATH.'/files/'.$entry) && !isset($files_db_filename[$entry]))
                    {
                        echo "root FILES NOT in DB: $entry<br />";

                        if($par == '1')
                        {
                            unlink(FCPATH.'/files/'.$entry);
                        }
                    }
                }
            }
            closedir($handle);
        }

        // Get files from files folder
        if ($handle = opendir(FCPATH.'/files/thumbnail/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {

                    $skip=false;
                    foreach($files_for_skip as $val)
                    {
                        if(substr_count($entry, $val) > 0)
                        {
                            $skip=true;
                            break;
                        }
                    }
                    if($skip)continue;

                    if(is_file(FCPATH.'/files/thumbnail/'.$entry) && !isset($files_db_filename[$entry]))
                    {
                        echo "thumbnail FILES NOT in DB: $entry<br />";

                        if($par == '1')
                        {
                            unlink(FCPATH.'/files/thumbnail/'.$entry);
                        }
                    }
                }
            }
            closedir($handle);
        }

        // Find repositories not used
        $all_rep = array();

        $reps = $this->col_from_table('ads_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('estate_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('user_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('slideshow_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('treefield_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('showroom_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('page_m', 'repository_id');
        $all_rep +=  $reps;

        $reps = $this->col_from_table('option_m', 'repository_id');
        $all_rep +=  $reps;

        $all_rep[config_db_item('website_logo')] = 'settings';
        $all_rep[config_db_item('website_logo_secondary')] = 'settings';
        $all_rep[config_db_item('website_favicon')] = 'settings';
        $all_rep[config_db_item('watermark_img')] = 'settings';
        $all_rep[config_db_item('search_background')] = 'settings';

        // Find reps by UPLOAD fields

        $this->db->select('*');
        $this->db->from('option');
        $this->db->join('property_value', 'option.id = property_value.option_id');
        $this->db->where('option.type', 'UPLOAD'); 
        $query = $this->db->get();
        $results = $query->result();

        $upload_fields = array();
        foreach($results as $row)
        {
            if(is_numeric($row->value_num))
            {
                $all_rep[$row->value_num] = 'fields_upload';
            }
        }

        foreach($files_db_filename as $key=>$val)
        {
            if(!isset($all_rep[$val]))
            {
                echo "REPOSITORY NOT FOUND: $val<br />";
                echo "<img style=\"background-color:black;max-height:100px;\" src=\"".base_url()."files/$key\"><br />";

                if($par == '1')
                {
                    unlink(FCPATH.'/files/'.$key);
                    unlink(FCPATH.'/files/thumbnail/'.$key);

                    // remove from file table
                    $this->db->delete('file', array('filename' => $key)); 

                }
            }
        }

        // Remove strict_cache and captcha files
        if($par == '1')
        {
            if ($handle = opendir(FCPATH.'/files/captcha/')) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
    
                        unlink(FCPATH.'/files/captcha/'.$entry);
                    }
                }
                closedir($handle);
            }

            if ($handle = opendir(FCPATH.'/files/strict_cache/')) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
    
                        unlink(FCPATH.'/files/strict_cache/'.$entry);
                    }
                }
                closedir($handle);
            }
        }

        echo 'TO remove files also, change parameter in url, 0 to 1<br />';

        exit('FINISH');
    }
    
    public function translation_definitions($par='0')
    {

        $files_content = '';
        $dir = FCPATH.'/templates/'.$this->data['settings']['template'];
        echo '<b>Check missing strings in translate file:</b><br />';
        if(is_dir($dir)) {

            function scanDir_f($dir, &$files_content) {
                $objs = scanDir($dir);
                foreach($objs as $val) {
                    if($val == "." || $val == "..") continue;
                    $file_p = $dir.'/'.$val;
                    
                    if(is_dir($file_p) && $val != "language" && $val != "assets") {
                        scanDir_f($file_p, $files_content);
                    } elseif(strpos($val, 'php') !== FALSE) {
                        $files_content .= file_get_contents($file_p);
                    }
                }
            }
            scanDir_f($dir, $files_content);
            
            $strings = array();
            preg_match_all('/\_l\((.*?)\)/is', $files_content, $matches);
            if($matches && isset($matches[1])){
                foreach ($matches[1] as $key => $value) {
                    $value = trim($value);
                    $_val = substr($value, 1);
                    if($value[0]== "'" && strpos($_val, "',") !== FALSE) {
                        $i = strpos($_val, "',");
                        if($i!= 0 && $_val[strpos($value, "',")-1] !=='\\'){
                            $pos = strpos($value, "',");
                            $value = substr($value, 0, $pos);
                        }
                    }
                    if($value[0]== '"' && strpos($_val, '",') !== FALSE) {
                        $i = strpos($_val, '",');
                        if($i!= 0 && $_val[strpos($_val, '",')-1] !=="\\"){
                            $pos = strpos($value, '",');
                            $value = substr($value, 0, $pos);
                        }
                    }
                    if($value[0]== "'" && strpos($_val, "'.") !== FALSE) {
                        $i = strpos($_val, "'.");
                        if($i!= 0 && $_val[strpos($_val, "'.")-1] !=='\\')
                            continue;
                    }
                    if($_val[0]== '"' && strpos($_val, '".') !== FALSE) {
                        $i = strpos($_val, '".');
                        if($i!= 0 && $_val[strpos($_val, '".')-1] !=="\\")
                            continue;
                    }
                    
                    $value= trim($value, "'");
                    $value= trim($value, '"');
                    $strings[] = $value;
                }
            }
            preg_match_all('/lang_check\((.*?)\)/is', $files_content, $matches);
            if($matches && isset($matches[1])){
                foreach ($matches[1] as $key => $value) {
                    $value = trim($value);
                    $_val = substr($value, 1);
                    if($value[0]== "'" && strpos($_val, "',") !== FALSE) {
                        $i = strpos($_val, "',");
                        if($i!= 0 && $_val[strpos($value, "',")-1] !=='\\'){
                            $pos = strpos($value, "',");
                            $value = substr($value, 0, $pos);
                        }
                    }
                    if($value[0]== '"' && strpos($_val, '",') !== FALSE) {
                        $i = strpos($_val, '",');
                        if($i!= 0 && $_val[strpos($_val, '",')-1] !=="\\"){
                            $pos = strpos($value, '",');
                            $value = substr($value, 0, $pos);
                        }
                    }
                    if($value[0]== "'" && strpos($_val, "'.") !== FALSE) {
                        $i = strpos($_val, "'.");
                        if($i!= 0 && $_val[strpos($_val, "'.")-1] !=='\\')
                            continue;
                    }
                    if($_val[0]== '"' && strpos($_val, '".') !== FALSE) {
                        $i = strpos($_val, '".');
                        if($i!= 0 && $_val[strpos($_val, '".')-1] !=="\\")
                            continue;
                    }
                    
                    $value= trim($value, "'");
                    $value= trim($value, '"');
                    $strings[] = $value;
                }
            }
            
            /*
            preg_match_all('/{lang_(.*?)}/is', $files_content, $matches);
            if($matches && isset($matches[1])){
                foreach ($matches[1] as $key => $value) {
                    $value= trim($value, "'");
                    $value= trim($value, '"');
                    $strings[] = $value;
                }
            }
            */
            $strings = array_unique($strings);
            
            $this->load->model('language_m');
            $this->load->helper('security');
            $this->data['content_language_id'] = $this->language_m->get_content_lang();
            $language_current = $this->language_m->get_name($this->data['content_language_id']);

            $path_current = FCPATH.'templates/'.$this->data['settings']['template'].'/language/'.$language_current.'/frontend_template_lang.php';
            include $path_current;
            
            if(!isset($lang)) $lang = array();
            
            $language_translations_content = $lang;
            
            /* add missing strings translate text */
            $count = 0;
            echo "<pre>";
            foreach ($strings as $key => $value) {
                $lang_val = $value;
                $key = $value;

                $lang_val = xss_clean($lang_val);
                $lang_val = str_replace('"', '\"', $lang_val);
                $lang_val = str_replace('$', '\\$', $lang_val);

                $key = str_replace('\'', '\\\'', $key);
                $key = str_replace('$', '\\$', $key);

                if(!isset($language_translations_content[$lang_val]) && strpos($lang_val, '$') === FALSE) {
                    echo '$lang[\''.$lang_val.'\'] = "'.$lang_val.'";'.PHP_EOL;
                    $count++;
                } 
            }
            echo "</pre>";
            /* end add ore replace new translate text */

            // Save file
            if($par == 1 && $count > 0) {
                $file_content = "\n";

                $previous = 't';
                
                foreach ($strings as $key => $val) {
                    $lang_val = $val;

                    $lang_val = xss_clean($lang_val);
                    $lang_val = str_replace('"', '\"', $lang_val);
                    $lang_val = str_replace('$', '\\$', $lang_val);

                    $key = str_replace('\'', '\\\'', $key);
                    $key = str_replace('$', '\\$', $key);
                    
                    if(!isset($language_translations_content[$lang_val]) && strpos($lang_val, '$') === FALSE) {

                        if(empty($previous) && !empty($lang_val))
                            $file_content.= "\n";

                        $file_content.= '$lang[\''.$lang_val.'\'] = "'.$lang_val.'";'."\n";
                        $previous = $lang_val;
                    }
                }
                
                $origin = file_get_contents($path_current);
                $origin = str_replace('?>', "\n", $origin);
                $file_content = $origin.$file_content;
                file_put_contents($path_current, $file_content);
            }
        }


        echo '<b>Find "'.$count.'" missing strings in translate file.';
        if($count>0 && $par==1)
            echo ' Strings was added, please now translate via Admin->languages->translate files -> frontend_template_lang.php';
        echo '</b><br />';

        exit('FINISH');
    }

    private function col_from_table($table_model, $col)
    {
        $this->load->model($table_model);

        $col_db = $this->$table_model->get();
        $col_db_filename = array();

        // Get only filenames
        foreach($col_db as $row)
        {
            if(isset($row->$col))
            {
                $col_db_filename[$row->$col] = $table_model;
            }
        }

        return $col_db_filename;
    }
    
    private function in_template($template, $file_path)
    {
        if(strpos($file_path, 'templates/') === 0 && strpos($file_path, 'templates/'.$template.'/') === FALSE)
        {
            return TRUE;
        }
        elseif(strpos($file_path, 'templates\\') === 0 && strpos($file_path, 'templates\\'.$template.'\\') === FALSE)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    private function sql_dump()
    {
        // Generate database import file
        $db_host =     $this->db->hostname;
        $db_username = $this->db->username;
        $db_password = $this->db->password;
        $db_database = $this->db->database;
        
        $filename = FCPATH.'sql_scripts/db-example-1.sql';
        
        $db_host_exp = explode(':', $db_host);
        $db_host = $db_host_exp[0];
        
        $cmd = "C:/xampp/mysql/bin/mysqldump --skip-add-locks --skip-disable-keys -h {$db_host} -u {$db_username} --password={$db_password} {$db_database} > {$filename}";
        exec($cmd);
        
        // remove comments and instructions
//        $lines = file($filename);
//        foreach($lines as $key => $line) {
//            if(strpos($line, '/') === 0 || strpos($line, '--') === 0)
//            {
//                unset($lines[$key]);
//            }
//        }
//        $sql = implode('', $lines);
//        file_put_contents($filename, $sql);
    }

    public function generate_script_local($template = 'local')
    {
$skip_files = array(
'application/controllers/admin/ads.php',
'application/controllers/admin/booking.php',
//'application/controllers/admin/expert.php',
//'application/controllers/admin/favorites.php',
//'application/controllers/admin/news.php',
//'application/controllers/admin/packages.php',
//'application/controllers/admin/swin_reviews.php',
'application/controllers/admin/reports.php',
'application/controllers/admin/savesearch.php',
'application/controllers/admin/showroom.php',
//'application/controllers/admin/treefield.php',
'application/controllers/paymentconsole.php',
'applicatio/controllers/paypalrest.php',
'application/controllers/propertycompare.php',
'application/controllers/trulia.php',
'application/libraries/Clickatellapi.php',
'application/controllers/admin/visits.php',
'application/libraries/Geoplugin.php',
'application/libraries/Glogin.php',
'application/libraries/Twlogin.php',
//'application/libraries/Pdf.php',
'application/libraries/Importcsv.php',
'application/libraries/Import_google_places.php',
'application/libraries/Import_foursquare.php',
'cron.php',
//'slug.php',
'.htaccess',
'README.md',
'custom_database.php',
'templates/bootstrap2-responsive/widgets/right_mortgage.php',
'templates/bootstrap2-responsive/widgets/property_right_currency-conversions.php',
'templates/bootstrap2-responsive/widgets/property_right_qrcode.php',
'templates/bootstrap2-responsive/assets/js/places.js',

//'templates/boomerang/widgets/right_mortgage.php',
//'templates/boomerang/widgets/property_right_currency-conversions.php',
'templates/boomerang/widgets/property_right_qrcode.php',
'templates/boomerang/assets/js/places.js',

'templates/local/widgets/property_right_qrcode.php',
'templates/local/assets/js/places.js',

'templates/proper/assets/js/places.js',
'templates/proper/widgets/property_right_qrcode.php',
'templates/proper/widgets/right_mortgage.php',
'templates/realocation/widgets/property_right_qrcode.php',
'templates/realocation/assets/js/places.js',
'templates/realia/widgets/property_right_currency-conversions.php',
'templates/realia/widgets/property_right_qrcode.php',
'templates/realia/assets/js/places.js',
);

$skip_folders = array(

// [languages]

'application/language/arabic',
'application/language/dutch',
'application/language/german',
'application/language/italian',
'application/language/persian',
'application/language/portuguese',
'application/language/russian',
'application/language/serbian',
'application/language/slovenian',
'application/language/spanish',
'application/language/turkish',
'application/language/albanian',
'application/language/espana',

'system/language/arabic',
'system/language/dutch',
'system/language/german',
'system/language/italian',
'system/language/persian',
'system/language/portuguese',
'system/language/russian',
'system/language/serbian',
'system/language/slovenian',
'system/language/spanish',
'system/language/turkish',
'system/language/albanian',
'system/language/espana',

'templates/realsite/language/arabic',
'templates/realsite/language/dutch',
'templates/realsite/language/german',
'templates/realsite/language/italian',
'templates/realsite/language/persian',
'templates/realsite/language/portuguese',
'templates/realsite/language/russian',
'templates/realsite/language/serbian',
'templates/realsite/language/slovenian',
'templates/realsite/language/spanish',
'templates/realsite/language/turkish',
'templates/realsite/language/albanian',
'templates/realsite/language/espana',

'templates/boomerang/language/arabic',
'templates/boomerang/language/dutch',
'templates/boomerang/language/german',
'templates/boomerang/language/italian',
'templates/boomerang/language/persian',
'templates/boomerang/language/portuguese',
'templates/boomerang/language/russian',
'templates/boomerang/language/serbian',
'templates/boomerang/language/slovenian',
'templates/boomerang/language/spanish',
'templates/boomerang/language/turkish',
'templates/boomerang/language/albanian',
'templates/boomerang/language/espana',

'templates/local/language/arabic',
'templates/local/language/dutch',
'templates/local/language/german',
'templates/local/language/italian',
'templates/local/language/persian',
'templates/local/language/portuguese',
'templates/local/language/russian',
'templates/local/language/serbian',
'templates/local/language/slovenian',
'templates/local/language/spanish',
'templates/local/language/turkish',
'templates/local/language/albanian',
'templates/local/language/espana',


// [/languages]
'ci4_api',
'paypal_test',
'exports',
'assets/js/jquery-contact-tabs',
'assets/js/like2unlock/js',
'application/config/development',
'templates/boomerang/assets/boomerang',
'.git'
);

        $this->sql_dump();
        
        $this->generate_script($template, $skip_files, $skip_folders, 'basic');
    }
    
    public function generate_script_classifieds($template = 'boomerang')
    {
$skip_files = array(
'application/controllers/admin/ads.php',
'application/controllers/admin/booking.php',
'application/controllers/admin/promocode.php',
//'application/controllers/admin/expert.php',
//'application/controllers/admin/favorites.php',
//'application/controllers/admin/news.php',
//'application/controllers/admin/packages.php',
//'application/controllers/admin/swin_reviews.php',
'application/controllers/admin/reports.php',
'application/controllers/admin/savesearch.php',
'application/controllers/admin/showroom.php',
'application/controllers/admin/visits.php',
//'application/controllers/admin/treefield.php',
'application/controllers/paymentconsole.php',
'applicatio/controllers/paypalrest.php',
//'application/controllers/propertycompare.php',
'application/controllers/trulia.php',
'application/libraries/Clickatellapi.php',
'application/libraries/Geoplugin.php',
'application/libraries/Glogin.php',
//'application/libraries/Pdf.php',
'application/libraries/Importcsv.php',
'application/libraries/Import_google_places.php',
'application/libraries/Import_foursquare.php',
'application/libraries/Twlogin.php',
'cron.php',
//'slug.php',
'.htaccess',
'README.md',
'custom_database.php',
'templates/bootstrap2-responsive/widgets/right_mortgage.php',
'templates/bootstrap2-responsive/widgets/property_right_currency-conversions.php',
'templates/bootstrap2-responsive/widgets/property_right_qrcode.php',
'templates/bootstrap2-responsive/assets/js/places.js',



//'templates/boomerang/widgets/right_mortgage.php',
//'templates/boomerang/widgets/property_right_currency-conversions.php',
'templates/boomerang/widgets/property_right_qrcode.php',
'templates/boomerang/assets/js/places.js',

'templates/proper/assets/js/places.js',
'templates/proper/widgets/property_right_qrcode.php',
'templates/proper/widgets/right_mortgage.php',
'templates/realocation/widgets/property_right_qrcode.php',
'templates/realocation/assets/js/places.js',
'templates/realia/widgets/property_right_currency-conversions.php',
'templates/realia/widgets/property_right_qrcode.php',
'templates/realia/assets/js/places.js',
);

$skip_folders = array(

// [languages]

'application/language/arabic',
'application/language/dutch',
'application/language/german',
'application/language/italian',
'application/language/persian',
'application/language/portuguese',
'application/language/russian',
'application/language/serbian',
'application/language/slovenian',
'application/language/spanish',
'application/language/turkish',
'application/language/albanian',
'application/language/espana',

'system/language/arabic',
'system/language/dutch',
'system/language/german',
'system/language/italian',
'system/language/persian',
'system/language/portuguese',
'system/language/russian',
'system/language/serbian',
'system/language/slovenian',
'system/language/spanish',
'system/language/turkish',
'system/language/albanian',
'system/language/espana',

'templates/realsite/language/arabic',
'templates/realsite/language/dutch',
'templates/realsite/language/german',
'templates/realsite/language/italian',
'templates/realsite/language/persian',
'templates/realsite/language/portuguese',
'templates/realsite/language/russian',
'templates/realsite/language/serbian',
'templates/realsite/language/slovenian',
'templates/realsite/language/spanish',
'templates/realsite/language/turkish',
'templates/realsite/language/albanian',
'templates/realsite/language/espana',

'templates/boomerang/language/arabic',
'templates/boomerang/language/dutch',
'templates/boomerang/language/german',
'templates/boomerang/language/italian',
'templates/boomerang/language/persian',
'templates/boomerang/language/portuguese',
'templates/boomerang/language/russian',
'templates/boomerang/language/serbian',
'templates/boomerang/language/slovenian',
'templates/boomerang/language/spanish',
'templates/boomerang/language/turkish',
'templates/boomerang/language/albanian',
'templates/boomerang/language/espana',
// [/languages]

'exports',
'ci4_api',
'paypal_test',
'assets/js/jquery-contact-tabs',
'assets/js/like2unlock/js',
'application/config/development',
'templates/boomerang/assets/boomerang',
'.git'
);

        $this->sql_dump();
        
        $this->generate_script($template, $skip_files, $skip_folders, 'basic');
    }
    
    public function generate_script_jobworld($template = 'jobworld')
    {
$skip_files = array(
'application/controllers/admin/ads.php',
'application/controllers/admin/booking.php',
//'application/controllers/admin/expert.php',
//'application/controllers/admin/favorites.php',
//'application/controllers/admin/news.php',
//'application/controllers/admin/packages.php',
//'application/controllers/admin/swin_reviews.php',
'application/controllers/admin/reports.php',
'application/controllers/admin/savesearch.php',
'application/controllers/admin/showroom.php',
//'application/controllers/admin/treefield.php',
'application/controllers/paymentconsole.php',
'applicatio/controllers/paypalrest.php',
//'application/controllers/propertycompare.php',
'application/controllers/trulia.php',
'application/libraries/Clickatellapi.php',
'application/libraries/Geoplugin.php',
'application/libraries/Glogin.php',
//'application/libraries/Pdf.php',
'application/libraries/Importcsv.php',
'application/libraries/Import_google_places.php',
'application/libraries/Import_foursquare.php',
'cron.php',
//'slug.php',
'.htaccess',
'README.md',
'custom_database.php',
'templates/bootstrap2-responsive/widgets/right_mortgage.php',
'templates/bootstrap2-responsive/widgets/property_right_currency-conversions.php',
'templates/bootstrap2-responsive/widgets/property_right_qrcode.php',
'templates/bootstrap2-responsive/assets/js/places.js',

//'templates/boomerang/widgets/right_mortgage.php',
//'templates/boomerang/widgets/property_right_currency-conversions.php',
'templates/boomerang/widgets/property_right_qrcode.php',
'templates/boomerang/assets/js/places.js',
'templates/jobworld/widgets/property_right_qrcode.php',
'templates/jobworld/assets/js/places.js',

'templates/proper/assets/js/places.js',
'templates/proper/widgets/property_right_qrcode.php',
'templates/proper/widgets/right_mortgage.php',
'templates/realocation/widgets/property_right_qrcode.php',
'templates/realocation/assets/js/places.js',
'templates/realia/widgets/property_right_currency-conversions.php',
'templates/realia/widgets/property_right_qrcode.php',
'templates/realia/assets/js/places.js',
);

$skip_folders = array(

// [languages]

'application/language/arabic',
'application/language/dutch',
'application/language/german',
'application/language/italian',
'application/language/persian',
'application/language/portuguese',
'application/language/russian',
'application/language/serbian',
'application/language/slovenian',
'application/language/spanish',
'application/language/turkish',
'application/language/albanian',
'application/language/espana',

'system/language/arabic',
'system/language/dutch',
'system/language/german',
'system/language/italian',
'system/language/persian',
'system/language/portuguese',
'system/language/russian',
'system/language/serbian',
'system/language/slovenian',
'system/language/spanish',
'system/language/turkish',
'system/language/albanian',
'system/language/espana',

'templates/realsite/language/arabic',
'templates/realsite/language/dutch',
'templates/realsite/language/german',
'templates/realsite/language/italian',
'templates/realsite/language/persian',
'templates/realsite/language/portuguese',
'templates/realsite/language/russian',
'templates/realsite/language/serbian',
'templates/realsite/language/slovenian',
'templates/realsite/language/spanish',
'templates/realsite/language/turkish',
'templates/realsite/language/albanian',
'templates/realsite/language/espana',

'templates/boomerang/language/arabic',
'templates/boomerang/language/dutch',
'templates/boomerang/language/german',
'templates/boomerang/language/italian',
'templates/boomerang/language/persian',
'templates/boomerang/language/portuguese',
'templates/boomerang/language/russian',
'templates/boomerang/language/serbian',
'templates/boomerang/language/slovenian',
'templates/boomerang/language/spanish',
'templates/boomerang/language/turkish',
'templates/boomerang/language/albanian',
'templates/boomerang/language/espana',

'templates/jobworld/language/arabic',
'templates/jobworld/language/dutch',
'templates/jobworld/language/german',
'templates/jobworld/language/italian',
'templates/jobworld/language/persian',
'templates/jobworld/language/portuguese',
'templates/jobworld/language/russian',
'templates/jobworld/language/serbian',
'templates/jobworld/language/slovenian',
'templates/jobworld/language/spanish',
'templates/jobworld/language/turkish',
'templates/jobworld/language/albanian',
'templates/jobworld/language/espana',
// [/languages]
'ci4_api',
'paypal_test',
'exports',
'assets/js/jquery-contact-tabs',
'assets/js/like2unlock/js',
'application/config/development',
'templates/boomerang/assets/boomerang',
'.git'
);

        $this->sql_dump();
        
        $this->generate_script($template, $skip_files, $skip_folders, 'basic');
    }
    
    
    public function generate_script_basic($template = 'bootstrap2-responsive')
    {
$skip_files = array(
'application/models/historyads_m.php',
'application/controllers/admin/promocode.php',
'application/controllers/admin/ads.php',
'application/controllers/admin/booking.php',
'application/controllers/admin/expert.php',
//'application/controllers/admin/favorites.php',
'application/controllers/admin/news.php',
//'application/controllers/admin/packages.php',
'application/controllers/admin/swin_swin_reviews.php',
//'application/controllers/admin/reports.php',
//'application/controllers/admin/savesearch.php',
'application/controllers/admin/showroom.php',
//'application/controllers/admin/treefield.php',
'application/controllers/paymentconsole.php',
'applicatio/controllers/paypalrest.php',
'application/controllers/propertycompare.php',
'application/controllers/admin/visits.php',
'application/controllers/trulia.php',
'application/libraries/Clickatellapi.php',
'application/libraries/Geoplugin.php',
'application/libraries/Glogin.php',
'application/libraries/Pdf.php',
'application/libraries/Importcsv.php',
'application/libraries/Import_google_places.php',
'application/libraries/Import_foursquare.php',
'application/libraries/Eventful.php',
'application/libraries/Xml2u.php',
'application/libraries/Twlogin.php',
//'cron.php',
//'slug.php',
'.htaccess',
'README.md',
'custom_database.php',
'templates/bootstrap2-responsive/widgets/right_mortgage.php',
'templates/bootstrap2-responsive/widgets/property_right_currency-conversions.php',
'templates/bootstrap2-responsive/widgets/property_right_qrcode.php',
'templates/bootstrap2-responsive/assets/js/places.js',

'templates/boomerang/widgets/right_mortgage.php',
'templates/boomerang/widgets/property_right_currency-conversions.php',
'templates/boomerang/widgets/property_right_qrcode.php',
'templates/boomerang/assets/js/places.js',

'templates/selio/widgets/right_mortgage.php',
'templates/selio/widgets/property_right_currency-conversions.php',
'templates/selio/widgets/property_right_qrcode.php',
'templates/selio/assets/js/places.js',

'templates/proper/assets/js/places.js',
'templates/proper/widgets/property_right_qrcode.php',
'templates/proper/widgets/right_mortgage.php',
'templates/realocation/widgets/property_right_qrcode.php',
'templates/realocation/assets/js/places.js',
'templates/realia/widgets/property_right_currency-conversions.php',
'templates/realia/widgets/property_right_qrcode.php',
'templates/realia/assets/js/places.js',
);

$skip_folders = array(
'exports',
'ci4_api',
'paypal_test',
'assets/js/jquery-contact-tabs',
'assets/js/like2unlock/js',
'application/config/development',
'templates/boomerang/assets/boomerang',
'.git'
);

        $this->sql_dump();
        
        $this->generate_script($template, $skip_files, $skip_folders, 'basic');
    }
    
    public function generate_script_full($template = 'bootstrap2-responsive')
    {
$skip_files = array(
);

$skip_folders = array(
'exports',
'.git'
);
        
        $this->sql_dump();
        
        $this->generate_script($template, $skip_files, $skip_folders, 'full');
    }
    
    public function generate_script($template = 'bootstrap2-responsive', $skip_files=NULL, $skip_folders=NULL, $suffix='basic')
    {
        $this->load->helper('file');
        $zip = new ZipArchive;
        
        $filename_zip = 'exports/script.'.APP_VERSION_REAL_ESTATE.'.'.$suffix.'.zip';
        $filename_7zip = 'exports/script.'.APP_VERSION_REAL_ESTATE.'.'.$suffix.'.7zip.zip';
        
        if(file_exists(FCPATH.$filename_zip))
            unlink(FCPATH.$filename_zip);

        if(file_exists(FCPATH.$filename_7zip))
            unlink(FCPATH.$filename_7zip);
        
        $zip->open(FCPATH.$filename_zip, ZipArchive::CREATE);
        //$zip->setCompressionIndex(1, ZipArchive::CM_DEFLATE);
        
        $test=0;
        $log_export = '';
        //exit('test:'.ZipArchive::OPSYS_UNIX);
        $lang_path = realpath(FCPATH);
        $remove_chars = strlen(realpath(FCPATH))+1;
        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lang_path));
        foreach($directory_iterator as $filename => $path_object)
        {
            $filename = str_replace('\\', '/', $filename);
            
            if(strpos($filename, '.bac') !== FALSE || strpos($filename, 'logs/log-') !== FALSE)
            {
                $log_export.= 'SKIP: '.$filename."\r\n";
            }
            elseif($this->in_path($skip_folders, $filename))
            {
                $log_export.= 'SKIP_FOLDER: '.$filename."\r\n";
            }
            elseif($this->in_template($template, substr($filename, $remove_chars)) &&
                    $this->in_template('bootstrap2-responsive', substr($filename, $remove_chars)) )
            {
                //$log_export.= 'SKIP_TEMPLATE: '.$filename."\r\n";
            }
            elseif(is_file($filename))
            {
                $zip_filename = substr($filename, $remove_chars);
                $zip->addFile($filename, $zip_filename);
                
                $stat = stat($filename);
                $zip->setExternalAttributesName($zip_filename, ZipArchive::OPSYS_WINDOWS_NTFS, $stat['mode']);
                
            }
            elseif(is_dir($filename) && substr($filename, -2, 2) == "..")
            {
                $zip_filename = substr($filename, $remove_chars, -3);
                $zip->addEmptyDir($zip_filename);
            }
        }
        
        // remove skipped file paths from zip
        foreach($skip_files as $skip_file_path)
        {
            $zip->deleteName($skip_file_path);
            $zip->deleteName(str_replace('/', '\\', $skip_file_path));
            
            $log_export.= 'REMOVE: '.$skip_file_path."\r\n";
        }

        $ret = $zip->close();
        
        // some issues with php zip library, now we recreate everything with 7zip
        
        $output = shell_exec('"C:\Program Files\7-Zip\7z.exe" x "'.FCPATH.$filename_zip.'" -y -o"'.FCPATH.'exports/archive/"');
        $log_export.='SHELL OUTPUT: '.$output."\r\n";
        
        $output = shell_exec('"C:\Program Files\7-Zip\7z.exe" a "'.FCPATH.$filename_7zip.'" "'.FCPATH.'exports/archive/*"');
        $log_export.='SHELL OUTPUT: '.$output."\r\n";
        
        $path = FCPATH.'exports/archive/';
        
        if(PHP_OS === 'Windows')
        {
            $log_export.='rd /s /q '.$path;
            $output = shell_exec('rd /s /q '.$path);
        }
        else
        {
            $log_export.='rmdir /S /Q "'.$path.'"';
            $output = shell_exec('rmdir /S /Q "'.$path.'"');
        }
        
        unlink(FCPATH.$filename_zip);
        
        $log_export.='SHELL OUTPUT: '.$output."\r\n";
        
        file_put_contents(FCPATH.'exports/log.txt', $log_export);
        
        if($ret == true)
        {
            $this->load->helper('download');
            $data = file_get_contents(FCPATH.$filename_7zip); // Read the file's contents
            force_download($filename_7zip, $data);
        }
        else
        {
            exit('Error: '.$ret);
        }
        

    }
    
    public function fake_listings($listing_num)
    {
        echo("STARTED fake_listings, num: $listing_num<br />");
        
        $start_time = time();
        
        $this->load->model('estate_m');
        
        for($i=0;$i<$listing_num;$i++)
        {
            $data = array();
            $dynamic_data = array();
            
            $this->generate_dummy_property($data, $dynamic_data);

            $insert_id = $this->estate_m->save($data, NULL);
            $this->estate_m->save_dynamic($dynamic_data, $insert_id, FALSE);
            
            if($i % 100 == 0 && $i>0)
            {
                echo "generated: $i <br />";
            }
            
            if(time()-$start_time > 2000)
            {
                echo "generated: $i, time limit 2000s <br />";
                break;
            }
        }
        
        echo("COMPLETED<br />");
    }
    
    public function generate_sitemap()
    {
        echo("STARTED generate_sitemap<br />");
        
        $this->load->library('sitemap');
        $this->sitemap->generate_sitemap();
        
        echo("COMPLETED<br />");
    }

    private	function rand_coord() {
        $num1 = rand(-80,80);
        $num2 = rand(-150,150);
        $new_coord = $num1 . ", " . $num2;
        
        return $new_coord;
    }

    private function generate_dummy_property(&$data, &$dynamic_data)
    {

$data['gps']=$this->rand_coord();;
$data['date']=date('Y-m-d H:i:s');
$data['address']='Cestica '.substr(md5(microtime().'e'),0,5);
$data['is_featured']='';
$data['is_activated']='1';
$data['date_modified'] = date('Y-m-d H:i:s');
$dynamic_data['option10_1']='Bjelovar estate '.substr(md5(microtime()),0,5);
$dynamic_data['option8_1']='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vulputate nec neque gravida rhoncus. Donec sit amet blandit mauris, sed bibendum risus.';
$dynamic_data['option17_1']='

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vulputate nec neque gravida rhoncus. Donec sit amet blandit mauris, sed bibendum risus. Cras ut urna semper, facilisis augue sed, imperdiet nulla. Duis tristique tellus tortor, dapibus gravida sem sodales id. Nullam quis convallis libero, vitae pulvinar nisi.

Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum.

Ut erat lacus, sagittis ac leo eu, molestie mattis libero. Nam sit amet massa et magna porttitor eleifend a aliquam nunc. Pellentesque a est a augue dignissim tristique eu nec augue. Integer pretium sollicitudin tellus, quis hendrerit nunc accumsan et. Fusce bibendum a neque vel fringilla. Vivamus viverra enim at purus gravida, in elementum neque eleifend. Curabitur sodales dapibus urna, at mattis lacus eleifend non. Donec ultricies porta orci eu congue. Nulla sodales arcu a libero aliquet, nec aliquam ante sagittis.
';
$dynamic_data['option38_1']='empty';
$dynamic_data['option6_1']='apartment';
$dynamic_data['option56_1']='';
$dynamic_data['option1_1']='';
$dynamic_data['option4_1']='Rent';
$dynamic_data['option2_1']='Apartment';
$dynamic_data['option5_1']='Bjelovarska';
$dynamic_data['option7_1']='Bjelovar';
$dynamic_data['option40_1']='42208';
$dynamic_data['option3_1']='Less than 50m2';
$dynamic_data['option57_1']='40';
$dynamic_data['option39_1']='-';
$dynamic_data['option19_1']='3';
$dynamic_data['option20_1']='3';
$dynamic_data['option58_1']='7';
$dynamic_data['option36_1']='90,000.00';
$dynamic_data['option55_1']='';
$dynamic_data['option37_1']='';
$dynamic_data['option54_1']='Agent';
$dynamic_data['option53_1']='-';
$dynamic_data['option59_1']='70';
$dynamic_data['option60_1']='20';
$dynamic_data['option65_1']='0';
$dynamic_data['option64_1']='0';
$dynamic_data['option66_1']='';
$dynamic_data['option67_1']='FieldGate properties';
$dynamic_data['option74_1']='0';
$dynamic_data['option68_1']='1234567890';
$dynamic_data['option69_1']='http://www.google.com';
$dynamic_data['option70_1']='http://www.facebook.com';
$dynamic_data['option71_1']='http://www.twitter.com';
$dynamic_data['option72_1']='345 Dixon Road Toronto Ontario M9R 15G (Dixon & Kipling)';
$dynamic_data['option73_1']='0-24';
$dynamic_data['option21_1']='';
$dynamic_data['option22_1']='true';
$dynamic_data['option23_1']='';
$dynamic_data['option24_1']='true';
$dynamic_data['option25_1']='';
$dynamic_data['option28_1']='true';
$dynamic_data['option29_1']='true';
$dynamic_data['option31_1']='true';
$dynamic_data['option52_1']='';
$dynamic_data['option11_1']='true';
$dynamic_data['option30_1']='';
$dynamic_data['option27_1']='true';
$dynamic_data['option33_1']='true';
$dynamic_data['option32_1']='true';
$dynamic_data['option43_1']='';
$dynamic_data['option44_1']='600';
$dynamic_data['option45_1']='600';
$dynamic_data['option46_1']='600';
$dynamic_data['option47_1']='600';
$dynamic_data['option48_1']='';
$dynamic_data['option49_1']='';
$dynamic_data['option50_1']='';
$dynamic_data['option51_1']='';
$dynamic_data['option9_1']='';
$dynamic_data['option12_1']='';
$dynamic_data['option42_1']='';
$dynamic_data['slug_1']='bjelovar-estate'.substr(md5(microtime()),0,5);
$dynamic_data['option10_2']='Bjelovar nekretnina '.substr(md5(microtime()),0,5);
$dynamic_data['option8_2']='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vulputate nec neque gravida rhoncus. Donec sit amet blandit mauris, sed bibendum risus.';
$dynamic_data['option17_2']='

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vulputate nec neque gravida rhoncus. Donec sit amet blandit mauris, sed bibendum risus. Cras ut urna semper, facilisis augue sed, imperdiet nulla. Duis tristique tellus tortor, dapibus gravida sem sodales id. Nullam quis convallis libero, vitae pulvinar nisi.

Nam eget est facilisis, porta mi ac, ultricies enim. Proin nisi diam, eleifend ac eleifend in, dapibus in orci. Vestibulum elementum lectus non nisl venenatis, tempus molestie nisi tempus. Pellentesque facilisis nibh nec purus blandit, id aliquam lorem fermentum.

Ut erat lacus, sagittis ac leo eu, molestie mattis libero. Nam sit amet massa et magna porttitor eleifend a aliquam nunc. Pellentesque a est a augue dignissim tristique eu nec augue. Integer pretium sollicitudin tellus, quis hendrerit nunc accumsan et. Fusce bibendum a neque vel fringilla. Vivamus viverra enim at purus gravida, in elementum neque eleifend. Curabitur sodales dapibus urna, at mattis lacus eleifend non. Donec ultricies porta orci eu congue. Nulla sodales arcu a libero aliquet, nec aliquam ante sagittis.
';
$dynamic_data['option38_2']='empty';
$dynamic_data['option6_2']='apartment';
$dynamic_data['option56_2']='';
$dynamic_data['option1_2']='';
$dynamic_data['option4_2']='Najam';
$dynamic_data['option2_2']='Stan';
$dynamic_data['option5_2']='Bjelovarska';
$dynamic_data['option7_2']='Bjelovar';
$dynamic_data['option40_2']='42208';
$dynamic_data['option3_2']='Manje od 50m2';
$dynamic_data['option57_2']='40';
$dynamic_data['option39_2']='-';
$dynamic_data['option19_2']='3';
$dynamic_data['option20_2']='3';
$dynamic_data['option58_2']='7';
$dynamic_data['option36_2']='90,000.00';
$dynamic_data['option55_2']='';
$dynamic_data['option37_2']='';
$dynamic_data['option54_2']='Agent';
$dynamic_data['option53_2']='-';
$dynamic_data['option59_2']='70';
$dynamic_data['option60_2']='20';
$dynamic_data['option65_2']='0';
$dynamic_data['option64_2']='0';
$dynamic_data['option66_2']='';
$dynamic_data['option67_2']='FieldGate properties 2';
$dynamic_data['option74_2']='0';
$dynamic_data['option68_2']='1234567890';
$dynamic_data['option69_2']='http://www.google.com';
$dynamic_data['option70_2']='http://www.facebook.com';
$dynamic_data['option71_2']='http://www.twitter.com';
$dynamic_data['option72_2']='My company description';
$dynamic_data['option73_2']='0-24';
$dynamic_data['option21_2']='';
$dynamic_data['option22_2']='true';
$dynamic_data['option23_2']='';
$dynamic_data['option24_2']='true';
$dynamic_data['option25_2']='';
$dynamic_data['option28_2']='true';
$dynamic_data['option29_2']='true';
$dynamic_data['option31_2']='true';
$dynamic_data['option52_2']='';
$dynamic_data['option11_2']='true';
$dynamic_data['option30_2']='';
$dynamic_data['option27_2']='true';
$dynamic_data['option33_2']='true';
$dynamic_data['option32_2']='true';
$dynamic_data['option43_2']='';
$dynamic_data['option44_2']='600';
$dynamic_data['option45_2']='600';
$dynamic_data['option46_2']='600';
$dynamic_data['option47_2']='600';
$dynamic_data['option48_2']='';
$dynamic_data['option49_2']='';
$dynamic_data['option50_2']='';
$dynamic_data['option51_2']='';
$dynamic_data['option9_2']='';
$dynamic_data['option12_2']='';
$dynamic_data['option42_2']='';
$dynamic_data['slug_2']='bjelovar-nekretnina'.substr(md5(microtime()),0,5);;
$dynamic_data['agent']='';


$this->load->model('option_m');
if(!isset($this->data['options_lang']))
{
    // Get all options
    foreach($this->option_m->languages as $key=>$val){
        $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
    }
    $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, 1);
    
    $options_data = array();
    foreach($this->option_m->get() as $key=>$val)
    {
        $options_data[$val->id][$val->type] = 'true';
    }
    
    $this->data['options_data'] = $options_data;
}

$options_data = &$this->data['options_data'];


$data['search_values'] = $data['address'];
foreach($dynamic_data as $key=>$val)
{
    $pos = strpos($key, '_');
    $option_id = substr($key, 6, $pos-6);
    $language_id = substr($key, $pos+1);
    
    if(!isset($options_data[$option_id]['TEXTAREA']) && !isset($options_data[$option_id]['CHECKBOX'])){
        $data['search_values'].=' '.$val;
    }
    
    // TODO: test check, values for each language for selected checkbox
    if(isset($options_data[$option_id]['CHECKBOX'])){
        if($val == 'true')
        {
            foreach($this->option_m->languages as $key_lang=>$val_lang){
                foreach($this->data['options_lang'][$key_lang] as $key_option=>$val_option){
                    if($val_option->id == $option_id && $language_id == $key_lang)
                    {
                        $data['search_values'].=' true'.$val_option->option;
                    }
                }
            }
        }
    }
}

    }
    
    public function remove_listings()
    {
        $this->load->model('estate_m');
        
        $listings = $this->estate_m->get();
        $count = 0;
        foreach ($listings as $listing) {
            
            $this->estate_m->delete($listing->id);
            $count++;
        }
        
        echo("COMPLETED, REMOVED: {$count} listings<br />");
    }
    

}