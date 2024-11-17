## LibreNMS ConnectionInformation

A LibreNMS Plugin to provide data about how devices are connected to the network.

Displays the following data:
  * Mac Address
  * Switch Port
  * Switch IP Address
  * Switch Name

## Install

1. Copy the `ConnectionInformation` directory to your `librenms/html/plugins` directory.
2. In LibreNMS go to `Overview`->`Plugins`->`Plugin Admin`
3. Click enable on `Connection Information`

## Usage

When visiting a device overview page the `Connection Information` box will appear if any data was able to be found.
