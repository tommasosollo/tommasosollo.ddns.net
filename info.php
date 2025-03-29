<?php
echo "<h1>Client Device Info</h1>";

// Get the client's IP address
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
echo "<h4>Client IP: " . htmlspecialchars($clientIP) . "</h4>";

// Get the client's user agent (device type, OS, browser)
$clientUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
echo "<h4>User Agent: " . htmlspecialchars($clientUserAgent) . "</h4>";

// Try to detect the client's operating system
function getOS($userAgent) {
    $osArray = [
        '/windows nt 10/i'    => 'Windows 10',
        '/windows nt 6.3/i'   => 'Windows 8.1',
        '/windows nt 6.2/i'   => 'Windows 8',
        '/windows nt 6.1/i'   => 'Windows 7',
        '/windows nt 6.0/i'   => 'Windows Vista',
        '/windows nt 5.1/i'   => 'Windows XP',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/linux/i'            => 'Linux',
        '/ubuntu/i'           => 'Ubuntu',
        '/iphone/i'           => 'iPhone',
        '/ipad/i'             => 'iPad',
        '/android/i'          => 'Android',
    ];
    
    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            return $value;
        }
    }
    return "Unknown OS";
}

$clientOS = getOS($clientUserAgent);
echo "<h4>Client Operating System: " . htmlspecialchars($clientOS) . "</h4>";

// Try to detect the client's browser
function getBrowser($userAgent) {
    $browserArray = [
        '/chrome/i'  => 'Google Chrome',
        '/firefox/i' => 'Mozilla Firefox',
        '/safari/i'  => 'Apple Safari',
        '/edge/i'    => 'Microsoft Edge',
        '/opera/i'   => 'Opera',
        '/msie/i'    => 'Internet Explorer',
    ];
    
    foreach ($browserArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            return $value;
        }
    }
    return "Unknown Browser";
}

$clientBrowser = getBrowser($clientUserAgent);
echo "<h4>Client Browser: " . htmlspecialchars($clientBrowser) . "</h4>";

// Get the client's preferred language
$clientLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Unknown';
echo "<h4>Preferred Language: " . htmlspecialchars($clientLang) . "</h4>";

// Get client screen resolution (requires JavaScript)
echo "<script>
    document.write('<h4>Screen Resolution: ' + screen.width + 'x' + screen.height + '</h4>');
</script>";
?>
