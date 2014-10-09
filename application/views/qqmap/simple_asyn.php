
<!DOCTYPE html>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>腾讯地图</title>

<style type="text/css">

*{

    margin:0px;

    padding:0px;

}

body, button, input, select, textarea {

    font: 12px/16px Verdana, Helvetica, Arial, sans-serif;

}

p{

    width:603px;

    padding-top:3px;

    overflow:hidden;

}

.btn{

    width:142px;

}

</style>

<script>

//document.domain = 'qq.com';



function init() {

  var myLatlng = new qq.maps.LatLng(39.916527,116.397128);

  var myOptions = {

    zoom: 8,

    center: myLatlng,

    mapTypeId: qq.maps.MapTypeId.ROADMAP

  }

  var map = new qq.maps.Map(document.getElementById("container"), myOptions);

}

  

function loadScript() {

  var script = document.createElement("script");

  script.type = "text/javascript";

  script.src = "http://map.qq.com/api/js?v=2.exp&callback=init";

  document.body.appendChild(script);

}

  

window.onload = loadScript;

</script>

</head>

<body>

<div style="width:603px;height:300px" id="container"></div>

<p>创建一个简单的地图。</p>

</body>

</html>

