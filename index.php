<?php
ini_set('display_errors', 'On');

$apps = [];
$folders = glob('./sys/apps' . '/*', GLOB_ONLYDIR);

foreach ($folders as $folder) {
    $name = $folder . '/name.txt';
    $icon = $folder . '/favicon.ico';

    if (file_exists($name) && file_exists($icon)) {
        $appName = trim(file_get_contents($name));
        $appIcon = $icon;
        $app = './sys/apps/' . basename($folder) . '/index.html';
        $apps[] = [
            'name' => $appName,
            'icon' => $appIcon,
            'app' => $app,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    #splash {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #000;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    #splashimg {
      width: 200px;
      height: auto;
    }

    #background, .icon {
      background-size: cover;
    }

    #bar, .icon {
      display: flex;
    }

    #home, .icon {
      cursor: pointer;
    }

    #bar, .name {
      color: #fff;
    }

    #app, #background {
      width: 100%;
      position: fixed;
      left: 0;
    }

    body {
      margin: 0;
      font-family: Verdana, sans-serif;
    }

    #background {
      background-color: #000;
      top: 0;
      height: 100%;
      z-index: -1;
    }

    #bar {
      background-color: #333;
      padding: 5px;
      justify-content: space-between;
      align-items: center;
    }

    #clock {
      flex: 1;
      font-size: 12px;
    }

    #home {
      flex: 1;
      text-align: right;
    }

    #menu {
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 20px;
      margin-top: 40px;
    }

    .icon {
      width: 120px;
      height: 120px;
      flex-direction: column;
      align-items: center;
    }

    .name {
      margin-top: 5px;
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    #app {
      display: none;
      height: calc(100% - 0px);
      top: 30px;
      border: none;
    }
  </style>
  <title>Boxes OS</title>
</head>
<body>
  <div id="splash">
    <img id="splashimg" src="data:image/octet-stream;base64,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">
  <h1>Boxes</h1>
  </div>
  <div id="background"></div>
  <div id="bar">
    <div id="clock">12:00:00 AM</div>
    <div id="home" onclick="home()">Home</div>
  </div>
  <div id="menu">
    <?php
    foreach ($apps as $app) {
      echo '<div class="icon" onclick="run(\'' . $app['app'] . '\')">';
      echo '<img width="60px" src="' . $app['icon'] . '">';
      echo '<div class="name">' . $app['name'] . '</div>';
      echo '</div>';
    }
    ?>
  </div>
  <iframe id="app" style="background-color:white;" src="" frameborder="0"></iframe>
  <script>
    function run(appURL) {
      document.getElementById('splash').style.display = 'none'; // Hide splash screen
      document.getElementById('menu').style.display = 'none';
      document.getElementById('app').src = appURL;
      document.getElementById('app').style.display = 'block';
    }

    function home() {
      document.getElementById('menu').style.display = 'grid';
      document.getElementById('app').style.display = 'none';
    }

    function showTime() {
      let time = new Date();
      let hour = time.getHours();
      let min = time.getMinutes();
      let sec = time.getSeconds();
      am_pm = "AM";

      if (hour > 12) {
        hour -= 12;
        am_pm = "PM";
      }
      if (hour == 0) {
        hr = 12;
        am_pm = "AM";
      }

      hour = hour < 10 ? "0" + hour : hour;
      min = min < 10 ? "0" + min : min;
      sec = sec < 10 ? "0" + sec : sec;

      let currentTime = hour + ":" + min + ":" + sec + " " + am_pm;
      document.getElementById("clock").innerHTML = currentTime;
    }

    showTime();
    setInterval(showTime, 1000);

    setTimeout(function () {
      document.getElementById('splash').style.display = 'none';
    }, 2000);
  </script>
</body>
</html>