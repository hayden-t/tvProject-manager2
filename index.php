<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Video Manager</title>
	<link href="jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet">
	<script src="jquery-ui-1.12.1/external/jquery/jquery.js"></script>
	<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
	<style>
	#calendar{display:flex;text-align:center;border-bottom:thin solid lightgrey;}
	#calendar .day{flex-grow:1;}
	#calendar .slot{border:thin solid lightgrey;border-bottom:none;height:50px;cursor:pointer;position:relative;}
	#calendar .slot:hover{background:lightgrey;}
	#calendar .show{position: absolute;width: 100%;background: yellow;height: 100px;top:0;z-index:10;}
	</style>
	<script>
	
	$( document ).ready(function() {
			$('#calendar .slot').click(function(){
				var time = $(this).html();
				var showSize = 2;
				var nextSlots = $(this).nextAll();
				if(nextSlots.length >= showSize - 1 && $(nextSlots[showSize-2]).find(".show").length == 0){
					var show = $(this).find(".show");
					if(show.length == 0){
						$(this).append("<div class='show'>"+time+": Flipper</div>");
					}else{
						if(window.confirm("Remove show? "+$(show).html())){
							$(this).find(".show").remove();
						}
					}
				}else{
					//no room
				}
			})
	});
	</script>	
</head>
<body>

<pre>
<?php 


require_once('getID3/getid3/getid3.php');
require_once 'getID3/getid3/extension.cache.mysqli.php';
$getID3 = new getID3;
$getID3 = new getID3_cached_mysqli('localhost', 'getid3', 'root', '');
$getID3->encoding = 'UTF-8';
$getID3->options_audiovideo_quicktime_ReturnAtomData = false;

	$media = 'G:\video\Flipper - Complete TV-series (1964 - 1995)';
	$currentDir = $media;

	$dirs=[];
	foreach (new DirectoryIterator($currentDir) as $subDirectory) {
		if($subDirectory->isDot()) continue;
		
		if($subDirectory->isDir()){
			
			$episodes = 0;
			$longest = 0;
			foreach (new DirectoryIterator($subDirectory->getPathname()) as $item) {
				if($item->isDot()) continue;
				if(!$item->isDir()){
				
					$file_meta = $getID3->analyze($item->getPathname());
					if(!isset($file_meta['video']))continue;//not media
					$length = intval($file_meta['playtime_seconds']/60);
					if($length > $longest)$longest = $length;
					$episodes++;

				}
			}
			$dirs[] = ['name' => $subDirectory->getPathname(), 'items'=>$episodes, 'longest'=>$longest];
		}
	}
	var_dump($dirs);
?>
</pre>

<div id="calendar">
<?php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; 

foreach($days as $day){

	echo "<div class='day'>";
		echo "<div class='title'>".$day."</div>";
		for($hour = 6; $hour <= 22; $hour++){
			$timeString = sprintf('%02d00', $hour);
			$time = new DateTime($timeString);
			echo "<div class='slot' data-time='".$timeString."'>".$time->format('h:i a')."</div>";
			
			$timeString = sprintf('%02d30', $hour);
			$time = new DateTime($timeString);	
			echo "<div class='slot' data-time='".$timeString."'>".$time->format('h:i a')."</div>";			
		}
		
	echo "</div>";
}

?>	
</div>

</body>
</html>