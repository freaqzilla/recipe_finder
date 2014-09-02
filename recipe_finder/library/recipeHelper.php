<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class recipeHelper {

    public function check($items, $recipe_array) {

        date_default_timezone_set('Australia/Sydney');

        $current_date = time();

        foreach ($recipe_array as $recipe_item) {

            //initialize variables
            $dish = $recipe_item['name'];
            $dates[$dish] = 0;
            $ingredientFound[$dish] = 0;

            for ($i = 0; $i < count($recipe_item['ingredients']); $i++) {

                foreach ($items as $item) {

                    $item_date = strtotime(str_replace('/', '-', $item[3]));

                    if ($item[0] == $recipe_item['ingredients'][$i]['item'] &&
                            $item[2] == $recipe_item['ingredients'][$i]['unit'] &&
                            $item[1] >= $recipe_item['ingredients'][$i]['amount'] && $current_date < $item_date) {

                        $ingredientFound[$dish] ++;


                        if ($item_date < $dates[$dish] || $dates[$dish] == 0) {

                            // get the ingredient with nearest use by date
                            $dates[$dish] = $item_date;
                        }

                        // check if all ingredients are available
                        if ($ingredientFound[$dish] == count($recipe_item['ingredients'])) {

                            $nearest_dates[$dish] = $dates[$dish];
                        }
                    }
                }
            }
        }

        asort($nearest_dates);

        return key($nearest_dates);
    }

}
