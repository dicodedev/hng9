<?php
$file = "teams.csv";
$new_file_destination = "filename.output.csv";
$newarray = array();

$CSVfp = fopen($file, "r+");
if ($CSVfp !== FALSE) {
    $added_title = false;
    $count = 0;
    $max_count = 22; //team name + 20 members
    while (!feof($CSVfp)) {
        $data = fgetcsv($CSVfp, 1000, ",");
        // print_r($data);

        //add the team name if the title has been added
        if (!$count && $added_title) array_push($newarray, [$data[0], '', '', '', '', '']);

        //add item if the title has been added and you are not on the gap line
        if ($added_title && $count && ($count < ($max_count)) && !empty($data)) {
            //get values
            $serial_number = $data[0];
            $filename = $data[1];
            $des = $data[2];
            $gender = $data[3];
            $UUID = $data[4];
            //create json data
            $array = array(
                'format' => 'CHIP-0007',
                'name' => '',
                'description' => $des,
                'minting_tool' => 'Team x',
                'sensitive_content' => false,
                'series_number' => $serial_number,
                'series_total' => 526,
                'attributes' =>
                array(
                    0 =>
                    array(
                        'trait_type' => 'gender',
                        'value' => $gender,
                    ),
                ),
                'collection' =>
                array(
                    'name' => 'Zuri NFT Tickets for Free Lunch',
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
            if (isset($data[1]) && !empty($data[1])) {
                array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', $hash)]);
            }else{
                array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), '']);
            }
        }

        if ($added_title && !($count === ($max_count - 1))) {
            $count++;
        }

        //gotten to the gap, reset counter
        if ($count === ($max_count)) {
            $count = 0;
        }

        //add the file title
        if (!$added_title) {
            array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', "sha256")]);
            // $count++;
            $added_title = true;
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