<?php

date_default_timezone_set("Europe/Stockholm");
function checkFormat() {

    //Lägger csv filen i en array
    $csv = array_map('str_getcsv', file('data.csv'));
    $totalMas = [];

    //funktionens alla argument läggs i array
    $landskoderna = func_get_args();

    // Alla functioner som är anropade loopas igenom med landskoderna
    foreach ($landskoderna as $x => $lk) {
        $feltext = TRUE;

        // Arrayerna loopas och sen loopas arrayerna inuti också
        foreach ($csv as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (strpos($value2, $lk) && preg_match("/^#[A-Z]{2}\d{6}$/", $value2)) {

                    //Lägger alla raders uträkningar i en tom variabel
                    $utrakMas = $value[1] * $value[2];

                    //Variablen pushas in i en tom array
                    array_push($totalMas, $utrakMas);

                    $feltext = FALSE;
                }
            }
        }

        //Om $feltext är TRUE,  skrivs ett felmeddelande ut
        if ($feltext) {
            $status = "Failure";
            echo "Landskoden: " . $lk ." finns ej  Status: " . $status;

        //Om det är FALSE så skrivs Status ut,de skapas en fil med landskoden och datum samt tid.
        } else {
            $status = "Success";
            $finalMas = array_sum($totalMas);

            //Värdena skrivs ut
            echo "Status: " . $status . " Landskod: " . $lk . " Totalsumma: " . $finalMas;

            //Värdena läggs till i en variabel med det rätta formatet
            $string = $status . ", " . $lk . ", " . $finalMas;
            $fileHandle = fopen($lk . "-" . date("Ymd") . "-" . date("His") . ".csv", "w+");

            fwrite($fileHandle, $string);
            fclose($fileHandle);
        }
    }
}
echo checkFormat('SE');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
