<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function uploadFileAction() {

//turn the front view off 
        $this->_helper->viewRenderer->setNoRender(true);

//include helper class
        include_once 'recipeHelper.php';

        $data = array();

        
// get the uploaded files
        
        $file = $_FILES['upload_field'];

        $file_name = basename($file['name'][0]);

        $file_name2 = basename($file['name'][1]);

        $ext = end((explode(".", $file_name)));

        $ext2 = end((explode(".", $file_name2)));


        if ($ext == 'csv' && $ext2 == 'txt') {

            $temp_csv = $file['tmp_name'][0];
            $temp_json = $file['tmp_name'][1];
        } elseif ($ext2 == 'csv' && $ext == 'txt') {

            $temp_csv = $file['tmp_name'][1];
            $temp_json = $file['tmp_name'][0];
        }
        
        else{
            
            echo 'Invalid file or files';
            exit;
            
        }

        if (!empty($temp_csv)) {

            $handle_csv = fopen($temp_csv, 'r');

            if ($handle_csv !== FALSE) {
                while (!feof($handle_csv)) {

                    $data_csv[] = fgetcsv($handle_csv, 1000, ",");
                    if (($key = array_search(false, $data)) !== false) {
                        unset($data[$key]);
                    }
                }
            }

            fclose($handle_csv);

            if (!empty($temp_json)) {

                $data_json = file_get_contents($temp_json);

                $recipe_array = json_decode($data_json, true);
            }
            
            else
                {
                
                echo 'JSON text file is empty';
                exit;
                
                }


            $helperObj = new recipeHelper();

            $result[] = $helperObj->check($data_csv, $recipe_array);

            if (in_array(null, $result)) {
                
                echo('Order Takeout');
                
            } else {

                echo('Recommended Recipe:' . $result[0]);
            }
        } else {
            
            echo 'CSV file is empty';
                        exit;

            
        }
    }

}
