<?php

class Zend_Controller_Action_Helper_recipeHelper extends Zend_Controller_Action_Helper_Abstract {

   
    public function check($items, $recipe_array) {

        date_default_timezone_set('Australia/Sydney');

        $current_date = time();

        foreach ($recipe_array as $recipe_item) {
           
            $dish_name = $recipe_item['name'];
            $dates[$dish_name] = 0;
            $ingredientFound[$dish_name] = 0;
  
            for ($i = 0; $i < count($recipe_item['ingredients']); $i++) {

                foreach ($items as $item) {
                    $item_date = strtotime(str_replace('/', '-', $item[3]));
                    if ($item[0] == $recipe_item['ingredients'][$i]['item'] &&
                            $item[2] == $recipe_item['ingredients'][$i]['unit'] &&
                            $item[1] >= $recipe_item['ingredients'][$i]['amount'] && $current_date < $item_date) {
                        $ingredientFound[$dish_name] ++;
                        if ($item_date < $dates[$dish_name] || $dates[$dish_name] == 0) {
                            $dates[$dish_name] = $item_date;
                        }
                        if ($ingredientFound[$dish_name] == count($recipe_item['ingredients'])) {

                            $earliest_dates[$dish_name] = $dates[$dish_name];
                        }
                    }
                }
            }
        }

        asort($earliest_dates);

        return key($earliest_dates);
    }
}