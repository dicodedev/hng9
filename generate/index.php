<?php
$file = "teams.csv";
$new_file_destination = "filename.output.csv";
$newarray = array();

$CSVfp = fopen($file, "r+");
if ($CSVfp !== FALSE) {
    $count = 0;
    while (!feof($CSVfp)) {
        $data = fgetcsv($CSVfp, 1000, ",");
        if ($count && !empty($data)) {
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
                'description' => '',
                'minting_tool' => 'Team x',
                'sensitive_content' => false,
                'series_number' => '',
                'series_total' => 526,
                'attributes' =>
                array(
                    0 =>
                    array(
                        'trait_type' => 'gender',
                        'value' => '',
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
                            'value' => 'Rewards for accomplishments during HNGi9.',
                        ),
                    ),
                ),
            );
            $json = json_encode($array);

            //hash json
            $new_file_name = $filename . "." . hash('sha256', $json) . ".csv";
            array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', $new_file_name)]);
        }
        if (!$count) {
            array_push($newarray, [str_replace('"', '', $data[0]), str_replace('"', '', $data[1]), str_replace('"', '', $data[2]), str_replace('"', '', $data[3]), str_replace('"', '', $data[4]), str_replace('"', '', "sha256")]);
            $count++;
        };
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
echo "<h2>Done generating!!</h2><a download href='".$new_file_destination."'>Click here</a> to download csv file";