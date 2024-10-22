<?php

namespace LibreNMS\Plugins;

use App\Models\Ipv4Mac;
use LibreNMS\Util\Mac;
use App\Models\Port;
use Illuminate\Support\Facades\Validator;

class ConnectionInformation {
  public static function device_overview_container($device) {

    $device_id = intval($device["device_id"]);
    $ip = $device["ip"];

    // Start our UI drawing
    echo('<div class="row"><div class="col-md-12"><div class="panel panel-default panel-condensed device-overview">');
    echo('div class="panel-heading">');
    echo('<i class="fa fa-network-wired fa-lg icon-theme" aria-hidden="true"></i>');
    echo('<strong> Connection Information</strong></div><div class="panel-body">');

    if (!empty($ip)) {

      $arp = Ipv4Mac::where('ipv4_address', $ip)->get();

      $macRaw = $arp[0]["mac_address"];

      echo('<div class="row"><div class="col-sm-4">Mac</div>');
      echo('<div class="col-sm-8">'.Mac::parse($macRaw).'</div></div>');

      $macParsed = Mac::parse($macRaw)->hex();

      $macRules = [
        'macAddress' => 'required|string|regex:/^[0-9a-fA-F]{12}$/',
      ];

      $validate = Validator::make(['macAddress' => $macParsed], $macRules);
      // validate makes sure it's valid and should fail if not

      $ports = Port::whereHas('fdbEntries', function ($fdbDownlink) use ($macParsed) {
        $fdbDownlink->where('mac_address', $macParsed);
      })
        ->withCount('fdbEntries')
        ->orderBy('fdb_entries_count')
        ->get();

      echo('<div class="row"><div class="col-sm-4">Switch Port</div>');
      echo('<div class="col-sm-8">'.$ports->first()["ifName"].'</div></div>');

      $switchDeviceId = $ports->first()["device_id"];
      $switchDeviceQuery = "
SELECT *
FROM devices
WHERE device_id = $switchDeviceId";

      $switchDeviceQueryResult = dbFetchRows($switchDeviceQuery);

      echo('<div class="row"><div class="col-sm-4">Switch IP Address</div>');
      echo('<div class="col-sm-8"><a href="ssh://'.$switchDeviceQueryResult[0]["hostname"].'" class="interface-upup">'.$switchDeviceQueryResult[0]["hostname"].'</a></div></div>');

      echo('<div class="row"><div class="col-sm-4">Switch Name</div>');
      echo('<div class="col-sm-8">'.$switchDeviceQueryResult[0]["sysName"].'</div></div>');
    }
    echo('</div></div></div></div>');
  }
}
?>
