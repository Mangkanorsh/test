<?php
// Include the config.php file
require_once 'config.php';

// Fallback function to test socket connection
function testSocketConnection($ip, $port, $timeout = 5) {
    $socket = @fsockopen($ip, $port, $errno, $errstr, $timeout);
    if ($socket) {
        echo "Socket connection to $ip:$port successful!<br>";
        fclose($socket);
        return true;
    } else {
        echo "Socket connection failed: $errstr ($errno)<br>";
        return false;
    }
}

// Test connection to ZKTeco device
try {
    // Attempt to connect
    $zk = connectToDevice();
    
    if ($zk) {
        echo "Successfully connected to ZKTeco device at " . DEVICE_IP . ":" . DEVICE_PORT . "!<br>";
        
        // Optional: Fetch and display some device info to confirm functionality
        $device_name = $zk->getDeviceName();
        echo "Device Name: " . ($device_name ?: "Unknown") . "<br>";
        
        // Disconnect from the device
        disconnectFromDevice($zk);
    } else {
        echo "Failed to connect to ZKTeco device at " . DEVICE_IP . ":" . DEVICE_PORT . ".<br>";
        // Fallback to socket test for network diagnosis
        testSocketConnection(DEVICE_IP, DEVICE_PORT);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    // Fallback to socket test
    testSocketConnection(DEVICE_IP, DEVICE_PORT);
}
?>