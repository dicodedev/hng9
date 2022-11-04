<?php
$file = "teams.csv";
$new_file_destination = "filename.output.csv";
$newarray = array();

$CSVfp = fopen($file, "r+");
if ($CSVfp !== FALSE) {
    $added_title = 0;
    $count = 0;
    $team;
    $max_count = 19; //team name + 20 members
    while (!feof($CSVfp)) {
        $data = fgetcsv($CSVfp, 1000, ",");
        if (isset($data[1]) && !empty($data[1])) {
            //add the team name if the title has been added
            if (!$count && $added_title) {
                $team = $data[0];
            }

            //add item if the title has been added and you are not on the gap line
            if ($added_title && ($count <= ($max_count)) && !empty($data)) {
                //get values
                $serial_number = $data[1];
                $filename = $data[2];
                $name = $data[3];
                $des = $data[4];
                $gender = $data[5];
                $attributes = explode(";", $data[6]);
                $UUID = $data[7];

                $attributes_array = array(
                    array(
                        'trait_type' => 'gender',
                        'value' => $gender,
                    ),
                );
                foreach ($attributes as $key => $value) {
                    array_push($attributes_array, array(
                        'trait_type' => trim(explode(":", $value)[0]),
                        'value' => trim(explode(":", $value)[1])
                    ));
                }

                //create json data
                $array = array(
                    'format' => 'CHIP-0007',
                    'name' => $name,
                    'description' => $des,
                    'minting_tool' => $team,
                    'sensitive_content' => false,
                    'series_number' => $serial_number,
                    'series_total' => $serial_number,
                    'attributes' => $attributes_array,
                    'collection' =>
                    array(
                        'name' => $filename,
                        'id' => $UUID,
                        'attributes' =>
                        array(
                            0 =>
                            array(
                                'type' => 'description',
                                'value' => $des,
                            ),
                        ),
                    ),
                );
                $json = json_encode($array);

                //hash json
                $hash = hash('sha256', $json);
                if (!$count && $added_title) {
                    $first_col = str_replace('"', '', $data[0]);
                } else {
                    $first_col = '';
                }
                array_push($newarray, [$first_col, str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', $data[5]), str_replace('"', '', $data[6]), str_replace('"', '', $data[7]), str_replace('"', '', $hash)]);
            }

            if ($added_title && !($count === ($max_count))) {
                $count++;
            } else {
                $count = 0;
            }

            //add the file title
            if (!$added_title) {
                array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', $data[5]), str_replace('"', '', $data[6]), str_replace('"', '', $data[7]), str_replace('"', '', "sha256")]);
                $added_title++;
            }
        }
    }

    // Open a file in write mode ('w')
    $fp = fopen($new_file_destination, 'w');
    // Loop through file pointer and a line
    foreach ($newarray as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
}
fclose($CSVfp);
//display 
echo "<h2>Done generating!!</h2><a download href='" . $new_file_destination . "'>Click here</a> to download csv file";