<?php
include('includes/functions.php');
/* Checks if user is already logged in */
session_start();
checkCookies();
if(isset($_SESSION["login"]) && $_SESSION["login"]) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Monitor | <?php echo title(); ?></title>
        <script src="https://www.google.com/jsapi" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="includes/js/chart.js" type="text/javascript"></script>
        <script src="includes/js/nouislider.min.js" type="text/javascript"></script>
        <script src="includes/js/circles.min.js" type="text/javascript"></script>
        <script src="includes/js/slider.js" type="text/javascript"></script>
        <meta name="theme-color" content="#009688">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="includes/json/manifest.json">
        <meta name="apple-mobile-web-app-title" content="Arduino Sensor">
        <link rel="icon" sizes="192x192" href="includes/images/icons/launcher-icon-4x.png">
        <link rel="apple-touch-icon" href="includes/images/icons/launcher-icon-ios.png">
        <link href="includes/css/nouislider.css" type="text/css" rel="stylesheet">
        <link id="stylesheet" href="includes/css/main.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css" rel="stylesheet">
        <script>
            $(document).ready(function() {
                /* Wait for line chart to load */
                $("#temp_chart").html("<h3>Loading...</h3>");
                setInterval(function(){
                    var minute = new Date().getMinutes();
                    var hour = new Date().getHours();
                    var day = new Date().getDate();
                    var month = new Date().getMonth() + 1;
                    var year = new Date().getFullYear();
                    drawChart(year, month, day, hour, minute);
                }, 5000);
                $.ajax({
                    url: "tempscale.php",
                    data: "",
                    dataType: "json",
                    success: function(data) {
                        var lowTemp = data[0];
                        var highTemp = data[1];
                        createSlider(lowTemp, highTemp);
                    }
                });
                /* Everything automatically refreshes every 5 seconds */
            });
        </script>
        <script>
            /* Checks if the device is running the webapp */
            function isRunningStandalone() {
                return ((window.matchMedia('(display-mode: standalone)').matches) || (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)));
            }
            /* Changes CSS to webapp version also keeps user signed in */
            if (isRunningStandalone()) {
                document.getElementById("stylesheet").setAttribute("href", "includes/css/mainMobile.css");
                $(window).scroll(function(){
                    $(".navbar").css("top",Math.max(8,55-$(this).scrollTop()));
                });
                $(document).ready(function() {
                    $.get("includes/latesttemp.php", function(temp) {
                        var myCircle = Circles.create({
                            id:                  'circles-1',
                            radius:              100,
                            value:               temp,
                            maxValue:            35,
                            width:               10,
                            text:                function(value){return value + '°C';},
                            colors:              ['#e0f2f1', '#009688'],
                            duration:            1000,
                            wrpClass:            'circles-wrp',
                            textClass:           'circles-text',
                            valueStrokeClass:    'circles-valueStroke',
                            maxValueStrokeClass: 'circles-maxValueStroke',
                            styleWrapper:        true,
                            styleText:           true
                        });
                    });
                    setInterval(function(){
                        $.get("includes/latesttemp.php", function(temp) {
                            var myCircle = Circles.create({
                                id:                  'circles-1',
                                radius:              100,
                                value:               temp,
                                maxValue:            35,
                                width:               10,
                                text:                function(value){return value + '°C';},
                                colors:              ['#e0f2f1', '#009688'],
                                duration:            0,
                                wrpClass:            'circles-wrp',
                                textClass:           'circles-text',
                                valueStrokeClass:    'circles-valueStroke',
                                maxValueStrokeClass: 'circles-maxValueStroke',
                                styleWrapper:        true,
                                styleText:           true
                            });
                        });
                    }, 5000);
                    /* Adds 40px at top of page which is size 
                     * of status bar on iOS to stop overlapping
                     */
                    if (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)) {
                        document.getElementById("ios-statusbar").style.background = "#00796b";
                        document.getElementById("ios-statusbar").style.height = "40px";
                    }
                });
            }
        </script>
        <!-- For iOS to stop opening new tabs on webapp -->
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
    </head>
    <body>
        <header>
            <div id="ios-statusbar"></div>
            <div class="topbar">
                <div class="container">
                    <div class="mobile nav-btn">
                        <i class="material-icons">&#xE5D2;</i>
                    </div>
                    <div class="logo">
                        <a class="desktop" href="index.php">Arduino Monitor</a>
                        <span class="mobile">Monitor</span>
                    </div>
                    <div class="mobile more-vert">
                        <i class="material-icons">&#xE5D4;</i>
                    </div>
                    <div class="logged-in">Hello, <?php /* Users full name */ echo findUser(); ?> | <span><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            </div>
            <div class="navbar">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a class="active" href="monitor.php">HOME</a></li>
                            <li><a href="settings.php">SETTINGS</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <div class="container flex">
            <div class="main-heading">
                <div class="desktop main-left">
                    <h1>Monitor</h1>
                    <h4>Get temperatures from Arduino</h4>
                </div>
                <div class="desktop switch">
                    <h2>Auto-update</h2>
                    <label>
                        <input type="checkbox" checked>
                        <span class="lever"></span>
                    </label>
                </div>
            <div class="mobile temp-update">
                <div class="circle" id="circles-1"></div>
            </div>
            <div id="temp_chart"></div>
            <div class="temp-settings">
                <h2>Temperature Slider</h2>
                <h3>Set minimum and maximum temperatures</h3>
                <form action="tempscale.php" method="POST" autocomplete="off">
                    <div id="temp_slider" class="temp-slider"></div>
                    <input type="number" name="low" id="low" min="0" max="60">
                    <input type="number" name="high" id="high" min="0" max="60">
                </form>
            </div>
        </div>
    </body>
</html>
<?php
} else {
    /* If not logged in redirect to login page */
    header("Location: index.php");
}
?>
