<?php 
//print_r($_GET); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head><title>Chekhra :: Geofence</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>

<link href="css/tracker_styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/mapStyle.css" />

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ" type="text/javascript"></script>
<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js?adsense=pub-1227201690587661" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript">
var myMap = null;
var localSearch = null;
var myQueryControl = null;
var pts= new Array();
var c=0;
//	http://www.3rdcrust.com/search/searchmap.html
function displayMap(){
  myMap = new GMap2(document.getElementById("map"));
  myMap.setCenter(new GLatLng(17.385044,78.486671), 14);
	myMap.addControl(new GSmallMapControl());
	myMap.addControl(new GMenuMapTypeControl());
	myMap.enableScrollWheelZoom();
	myMap.enableContinuousZoom();
	myMap.addControl(new GScaleControl());
	myMap.addControl(new GOverviewMapControl());
  localSearch = new google.maps.LocalSearch();//{externalAds : document.getElementById("ads")});
  myMap.addControl(localSearch);
  myQueryControl = new QueryControl(localSearch);
  myMap.addControl(myQueryControl);

  GEvent.addListener(myMap, "click", function(overlay, point) {
    if (point) {
	if(c==0)
	{
		pts[c] = point.y.toFixed(6) + '#' + point.x.toFixed(6) ;
		c++;
	}
	else
	{
	 	pts[c] = point.y.toFixed(6) + '#' + point.x.toFixed(6) ;
		c++;
	}
      singleClick = !singleClick;
	  fillDiv(pts);
      setTimeout("if (singleClick) createCircle(new GLatLng("+ point.y + ", " + point.x +"), 250);", 300);
    }
  });
}

</script>

<style type="text/css">
  @import url("gsearch.css");
  @import url("gmlocalsearch.css");

  div#GQueryControl {
    background-color: white;
    width: 155;
  }
</style>

</head>
<body onLoad="displayMap();" style="background:#fff">
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="corner_mid">
    <td width="22"><img src="images/corner_LT.gif" width="22" height="20" /></td>
    <td width="900">&nbsp;</td>
    <td width="22"><img src="images/corner_RT.gif" width="22" height="20" /></td>
  </tr>
  
  <tr>
    <td class="corner_mid_left">&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="popup_map">
      
      <tr>
        <td><div id="map_div"><div id="map"></div></div><div id="map_panel">
           <ul>
       	  <div class="buttons">
<!--            <div class="buttonB" onclick="clearAll()">Clear all</div>
            <div class="buttonB" onclick="undoPoint()">Undo last</div>
            <div class="button_box" onclick="addIntermediate()" >Medium Pt.</div>

            <div class="button_cur" onClick="addClosing()">Closing Pt.</div>
-->            <div class="buttonB" onClick="sendBackData()">Done</div>
          </div>
          <li><input type="text" name="address" id="address" value="Hyderabad" /></li>
          <li><input name="submit" type="submit" value="Show Address" class="blue_btn" onClick="locateAddress();" /></li>
			<div id="route">Route points:<br></div>
          </ul>
          <span class="map_logo"><img src="images/map_logo.png" width="70" height="21" /><br />
          � 2008 - 2009,Chekhra.com</span>
        </div></td>
      </tr>
    </table></td>
    <td class="corner_mid_right">&nbsp;</td>
  </tr>
  <tr class="corner_mid_bot">
    <td><img src="images/corner_LB.gif" width="22" height="20" /></td>
    <td>&nbsp;</td>
    <td><img src="images/corner_RB.gif" width="22" height="20" /></td>
  </tr>
</table>
	<!--<div id="map" style="width: 100%; height: 95%;"></div>
	<div style="font-family: arial, sans-serif;">Made by <a href="http://www.3rdcrust.com">3rdCrust.com</a></div>
	div id="QueryControl"></div>
	<div id="ads">
	</div -->
<script>
function sendBackData()
{
if(pts.length ==0 || c==0)
{
	alert('Make proper Geocode');
}
else
{
var browser=navigator.appName;
var b_version=navigator.appVersion;
var version=parseFloat(b_version);

//document.write("Browser name: "+ browser);
//document.write("<br />");
//document.write("Browser version: "+ version);

if(browser=='Microsoft Internet Explorer')
{
	final1=document.getElementById('queriesDiv').innerHTML.split("</FONT>");
	//alert(final1.length);
		for(l=0;l<(final1.length-1);l++)
		{
			final2=final1[l].split('">');
			//alert(final2[1]);
	        //final3=final2[1].split('</font></span>');
			//alert(final2[2]+" "+pts[l]);
			if(l==0)
				fin="@"+final2[1]+"#"+pts[l]+"$";
			else
				fin+="@"+final2[1]+"#"+pts[l]+"$";
		}
		alert(fin);
		opener.document.getElementById('<?php echo $_GET[id1]; ?>').value=fin;		//latlng;
		//window.close();
}else if(browser=='Netscape')
{
		final1=document.getElementById('queriesDiv').innerHTML.split("</font>");
	for(l=0;l<(final1.length-1);l++)
	{
		final2=final1[l].split('">');
			//alert(final2[2]);
//		final3=final2[1].split('</font></span>');
		//alert(final2[2]+" "+pts[l]);
		if(l==0)
			fin="@"+final2[2]+"#"+pts[l]+"$";
		else
			fin+="@"+final2[2]+"#"+pts[l]+"$";
	}
		alert(fin);
		opener.document.getElementById('<?php echo $_GET[id1]; ?>').value=fin;		//latlng;
		//window.close();

}
}
}
  var metric = false;
  var singleClick = false;
  var queryCenterOptions = new Object();
  var queryLineOptions = new Object();

queryCenterOptions.icon = new GIcon();
queryCenterOptions.icon.image = "images/centerArrow.png";
queryCenterOptions.icon.iconSize = new GSize(20,20);
queryCenterOptions.icon.shadowSize = new GSize(0, 0);
queryCenterOptions.icon.iconAnchor = new GPoint(10, 10);
queryCenterOptions.draggable = true;
queryCenterOptions.bouncy = false;

queryLineOptions.icon = new GIcon();
queryLineOptions.icon.image = "images/resizeArrow.png";
queryLineOptions.icon.iconSize = new GSize(25,20);
queryLineOptions.icon.shadowSize = new GSize(0, 0);
queryLineOptions.icon.iconAnchor = new GPoint(12, 10);
queryLineOptions.draggable = true;
queryLineOptions.bouncy = false;

function fillDiv(data)
{
	for( u=0;u<data.length;u++)
	{
	if(u==0)
		document.getElementById("route").innerHTML=data[u];	
	else document.getElementById("route").innerHTML+="<br>"+data[u];
	}
}
function createCircle(point, radius) {
  singleClick = false;
  geoQuery = new GeoQuery();
  geoQuery.initializeCircle(radius, point, myMap);
  myQueryControl.addGeoQuery(geoQuery);
  geoQuery.render();
}

function destination(orig, hdng, dist) {
  var R = 6371; // earth's mean radius in km
  var oX, oY;
  var x, y;
  var d = dist/R;  // d = angular distance covered on earth's surface
  hdng = hdng * Math.PI / 180; // degrees to radians
  oX = orig.x * Math.PI / 180;
  oY = orig.y * Math.PI / 180;

  y = Math.asin( Math.sin(oY)*Math.cos(d) + Math.cos(oY)*Math.sin(d)*Math.cos(hdng) );
  x = oX + Math.atan2(Math.sin(hdng)*Math.sin(d)*Math.cos(oY), Math.cos(d)-Math.sin(oY)*Math.sin(y));

  y = y * 180 / Math.PI;
  x = x * 180 / Math.PI;
  return new GLatLng(y, x);
}

function distance(point1, point2) {
  var R = 6371; // earth's mean radius in km
  var lon1 = point1.lng()* Math.PI / 180;
  var lat1 = point1.lat() * Math.PI / 180;
  var lon2 = point2.lng() * Math.PI / 180;
  var lat2 = point2.lat() * Math.PI / 180;

  var deltaLat = lat1 - lat2
  var deltaLon = lon1 - lon2

  var step1 = Math.pow(Math.sin(deltaLat/2), 2) + Math.cos(lat2) * Math.cos(lat1) * Math.pow(Math.sin(deltaLon/2), 2);
  var step2 = 2 * Math.atan2(Math.sqrt(step1), Math.sqrt(1 - step1));
  return step2 * R;
}

function GeoQuery() {

}

GeoQuery.prototype.CIRCLE='circle';
GeoQuery.prototype.COLORS=["#0000ff", "#00ff00", "#ff0000"];
var COLORI=0;

GeoQuery.prototype = new GeoQuery();
GeoQuery.prototype._map;
GeoQuery.prototype._type;
GeoQuery.prototype._radius;
GeoQuery.prototype._dragHandle;
GeoQuery.prototype._centerHandle;
GeoQuery.prototype._polyline;
GeoQuery.prototype._color ;
GeoQuery.prototype._control;
GeoQuery.prototype._points;
GeoQuery.prototype._dragHandlePosition;
GeoQuery.prototype._centerHandlePosition;


GeoQuery.prototype.initializeCircle = function(radius, point, map) {
    this._type = this.CIRCLE;
    this._radius = radius;
    this._map = map;
    this._dragHandlePosition = destination(point, 90, this._radius/1000);
    this._dragHandle = new GMarker(this._dragHandlePosition, queryLineOptions);
    this._centerHandlePosition = point;
    this._centerHandle = new GMarker(this._centerHandlePosition, queryCenterOptions);
    this._color = this.COLORS[COLORI++ % 3];
    map.addOverlay(this._dragHandle);
    map.addOverlay(this._centerHandle);
    var myObject = this;
    GEvent.addListener (this._dragHandle, "dragend", function() {myObject.updateCircle(1);});
    GEvent.addListener (this._dragHandle, "drag", function() {myObject.updateCircle(1);});
    GEvent.addListener(this._centerHandle, "dragend", function() {myObject.updateCircle(2);});
    GEvent.addListener(this._centerHandle, "drag", function() {myObject.updateCircle(2);});
}

GeoQuery.prototype.updateCircle = function (type) {
    this._map.removeOverlay(this._polyline);
    if (type==1) {
      this._dragHandlePosition = this._dragHandle.getPoint();
      this._radius = distance(this._centerHandlePosition, this._dragHandlePosition) * 1000;
      this.render();
    } else {
      this._centerHandlePosition = this._centerHandle.getPoint();
      this.render();
      this._dragHandle.setPoint(this.getEast());
    }
}

GeoQuery.prototype.render = function() {
  if (this._type == this.CIRCLE) {
    this._points = [];
    var distance = this._radius/1000;
    for (i = 0; i < 72; i++) {
      this._points.push(destination(this._centerHandlePosition, i * 360/72, distance) );
    }
    this._points.push(destination(this._centerHandlePosition, 0, distance) );
    //this._polyline = new GPolyline(this._points, this._color, 6);
    this._polyline = new GPolygon(this._points, this._color, 1, 1, this._color, 0.2);
    this._map.addOverlay(this._polyline)
    this._control.render();
  }
}

GeoQuery.prototype.remove = function() {
  this._map.removeOverlay(this._polyline);
  this._map.removeOverlay(this._dragHandle);
  this._map.removeOverlay(this._centerHandle);
}

GeoQuery.prototype.getRadius = function() {
    return this._radius;
}

GeoQuery.prototype.getHTML = function() {
  return "<SPAN><FONT color='"+ this._color + "'>" + this.getDistHtml() + "</FONT></SPAN>";
}

GeoQuery.prototype.getDistHtml = function() {
  result = "<IMG SRC='images/close.gif' onclick='myQueryControl.remove(" + this._control.getIndex(this) + ")'/>";
  if (metric) {
    if (this._radius < 1000) {
      result +=  this._radius.toFixed(1);
    } else {
      result +=  (this._radius / 1000).toFixed(1);
    }
  } else {
    var radius = this._radius * 3.2808399;
    if (radius < 5280) {
      result +=  radius.toFixed(1);
    } else {
      result +=  (radius / 5280).toFixed(1);
    }
  }
  return result;   
}

GeoQuery.prototype.getNorth = function() {
  return this._points[0];
}

GeoQuery.prototype.getSouth = function() {
  return this._points[(72/2)];
}

GeoQuery.prototype.getEast = function() {
  return this._points[(72/4)];
}

GeoQuery.prototype.getWest = function() {
  return this._points[(72/4*3)];
}

function QueryControl (localSearch) {
  this._localSearch = localSearch;
}

QueryControl.prototype = new GControl();
QueryControl.prototype._geoQueries ;
QueryControl.prototype._mainDiv;
QueryControl.prototype._queriesDiv;
QueryControl.prototype._minStar;
QueryControl.prototype._minPrice;
QueryControl.prototype._maxPrice;
QueryControl.prototype._timeout;
QueryControl.prototype._localSearch;

QueryControl.prototype.initialize = function(map) {
  this._mainDiv = document.createElement("div");
  this._mainDiv.id = "GQueryControl";
  titleDiv = document.createElement("div");
  titleDiv.id = "GQueryControlTitle";
  titleDiv.appendChild(document.createTextNode("Filter"));
  this._mainDiv.appendChild(titleDiv);
  this._queriesDiv = document.createElement("div");
  this._queriesDiv.id = "queriesDiv";
  this._mainDiv.appendChild(this._queriesDiv);

  map.getContainer().appendChild(this._mainDiv);
  this._geoQueries = new Array();
  return this._mainDiv;
}

QueryControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(50, 10));
}

QueryControl.prototype.addGeoQuery = function(geoQuery) {
  this._geoQueries.push(geoQuery);
  geoQuery._control = this;
  newDiv = document.createElement("div");
  newDiv.innerHTML = geoQuery.getHTML();
  this._queriesDiv.appendChild(newDiv);
 
}

QueryControl.prototype.render = function() {
  for (i = 0; i < this._geoQueries.length; i++) {
    geoQuery = this._geoQueries[i];
    this._queriesDiv.childNodes[i].innerHTML = geoQuery.getHTML();
  }
  if (this._timeout == null) {
    this._timeout = setTimeout(myQueryControl.query, 1000);
  } else {
    clearTimeout(this._timeout);
    this._timeout = setTimeout(myQueryControl.query, 1000);
  }
}

QueryControl.prototype.query = function() {
  listMarkers = myQueryControl._localSearch.markers.slice();
  for (i = 0; i < listMarkers.length; i++) {
    marker = listMarkers[i].marker;
    result = listMarkers[i].resultsListItem;
    listImage = marker.getIcon().image;
    inCircle = true;
    for (j = 0; j < myQueryControl._geoQueries.length; j++) {
      geoQuery = myQueryControl._geoQueries[j];
      dist = distance(marker.getLatLng(), geoQuery._centerHandlePosition); 
      if (dist > geoQuery._radius / 1000) {
        inCircle = false;
        break;
      }
    }
    if (inCircle) {
      marker.setImage(listImage);
      result.childNodes[1].style.color = '#0000cc';
    } else {
      var re = new RegExp(".*(marker.\.png)");
      marker.setImage(listImage.replace(re, "img/$1"));
      result.childNodes[1].style.color = '#b0b0cc';
    }
  }
}
function removeByElement(arrayName,arrayElement)
{
for(var i=0; i<arrayName.length;i++ )
 { 
	if(i==arrayElement)
		arrayName.splice(i,1); 
  } 
  return arrayName;
}
QueryControl.prototype.remove = function(index) {
  this._geoQueries[index].remove();
  this._queriesDiv.removeChild(this._queriesDiv.childNodes[index]);
  delete this._geoQueries[index];
  this._geoQueries.splice(index,1); 
  fillDiv(removeByElement(pts,index));
  this.render();
}

QueryControl.prototype.getIndex = function(geoQuery) {
  for (i = 0; i < this._geoQueries.length; i++) {
    if (geoQuery == this._geoQueries[i]) {
      return i;
    }
  }
  return -1;
}
function locateAddress() {
      var address = document.getElementById("address").value;
      var geocoder = new GClientGeocoder();
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
              myMap.setCenter(point, 13);
              var marker = new GMarker(point);
              myMap.addOverlay(marker);
              marker.openInfoWindowHtml(address);
              start = point;
            }
          }
        );
      }
    }

</script>
</body>
</html>
