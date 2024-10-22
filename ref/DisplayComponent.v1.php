// An initial look at this when still attempting to collect the actual components
// var_dump($value) is helpful

<?php

namespace LibreNMS\Plugins;

class DisplayComponent {
  public static function device_overview_container($device) {
    $device_id = intval($device["device_id"]);

    $component_query = "
SELECT *
FROM component
WHERE device_id = $device_id";

    $output = dbFetchRows($component_query);

    foreach($output as $line) {
      if ($line["type"] == "SWITCH_FINDER:PORT") {
        echo('<div class="container-fluid">Port: '.$line["label"].'</div>');
      }
      if ($line["type"] == "SWITCH_FINDER:SWITCH") {
        echo('<div class="container-fluid">Switch: '.$line["label"].'</div>');
      }
    }
  }
}
