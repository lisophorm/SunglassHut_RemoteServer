
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Masonry / sortable combo demo - jsFiddle demo by desandro</title>
  
  <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.1.js'></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
  <script type="text/javascript" src="jquery.ui.touch-punch.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="/css/normalize.css">
  <link rel="stylesheet" type="text/css" href="/css/result-light.css">
  
  	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.3"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.2" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
  
     <script src="hammer/hammer.js"></script>
    <script src="hammer/jquery.specialevent.hammer.js"></script>
    <script src="js/fullscreen.js"></script>     
    
      <script type='text/javascript' src="js/jquery.masonry.min.js"></script>
  <style type='text/css'>
  body {
	  font-family:Verdana, Geneva, sans-serif;
  }
    #masonry {
       
}

#masonry > li {
    width: 140px;
    display:block;
    background: #555;
    margin-bottom: 10px;
}

#masonry > li.bigun {
	width: 250px;
	height: 250px;
	background: #159;
}
#masonry > li.small {
	width: 120px;
	height: 120px;
	background: #159;
}

#masonry > li.huge {
	width: 510px;
	height: 510px;
	background: #ccffaa;
}

#masonry > li li {
    display:block;
    height:0px;
}

.card-sortable-placeholder {
    background: #aaa !important;
}

.dragging { opacity: .8; background: #ff6903 !important; }
  #masonry .layout-card {
	height: 120px;
	width: 120px;
}

.score {
	position: absolute;
	z-index: 2;
	bottom: 4px;
	right: 4px;
	overflow: hidden;
	width: 30px;
	padding-top: 2px;
	padding-left: 4px;
	padding-bottom: 2px;
	padding-left: 4px;
	background-color: rgba(255,255,255,0.3);
}
.scoretext {
	font-size: 10px;
	color: #FFF;
	text-align: center;
	font-weight: bold;
	background-color: #666;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding-top: 2px;
	padding-right: 5px;
	padding-bottom: 2px;
	padding-left: 5px;
}

.score_smal {
	position: absolute;
	z-index: 2;
	bottom: 4px;
	right: 4px;
	overflow: hidden;
	width: 15px;
	padding-top: 2px;
	padding-left: 4px;
	padding-bottom: 2px;
	padding-left: 4px;
	background-color: rgba(255,255,255,0.3);
}
.scoretext_smal {
	font-size: 7px;
	color: #FFF;
	text-align: center;
	font-weight: bold;
	background-color: #666;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding-top: 2px;
	padding-right: 5px;
	padding-bottom: 2px;
	padding-left: 5px;
}


  </style>
  

 <script src="modernizr-transitions.js"></script>
<script src="../SpryAssets/SpryDOMUtils.js" type="text/javascript"></script>
<script src="masonry01.js" type="text/javascript"></script>
</head>
<body>
<button class="sexyButton" id="button1">Launch Fullscreen</button>
<div id="masonry-wrapper" style="margin: 0 auto;background-color:#cc00aa;">
<ul id="masonry" style="width:100%">

  
</ul>
</div>
  
</body>


</html>

