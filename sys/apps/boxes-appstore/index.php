<?php
$apps = json_decode(file_get_contents('https://boxes.sevenworks.eu.org/apps.json'), true);

echo '<html><head><title>App Store</title></head><body style="background-color: #ffffff; padding: 20px; font-family: Arial;"><h1>Boxes App Store</h1><hr>';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'install') {
    $zipurl = $_POST['zip'];
    file_put_contents('../' . basename($zipurl), file_get_contents($zipurl));

    $zip = new ZipArchive;
    if ($zip->open('../' . basename($zipurl)) === TRUE) {
        $zip->extractTo('../');
        $zip->close();
        echo '<p>App installed successfully!</p>';
    } else {
        echo '<p>App failed to install, Please try again later.</p>';
    }
}

echo '<div style="display: flex; flex-wrap: wrap;">';
foreach ($apps as $app) {
    echo '<div style="width: 20%; margin: 1%; background-color: #f0f0f0; padding: 15px; border-radius: 10px; text-align: center;">';
    echo '<img src="' . $app['icon'] . '" style="width: 50px; height: 50px; border-radius: 10%;">';
    echo '<h3>' . $app['name'] . '</h3>';
    echo '<p>' . $app['description'] . '</p>';
    echo '<form method="post" action="?action=install">';
    echo '<input type="hidden" name="zip" value="' . $app['zip'] . '">';
    echo '<button type="submit">Install</button>';
    echo '</form>';
    echo '</div>';
}
echo '</div>';

echo '</body></html>';
?>
