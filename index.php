<?php
$url = ltrim(htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, 'UTF-8'), "/");
$db = new SQLite3('url.db');
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS shorter(
id integer primary key,
url text,
origin text
)
EOT;
$db->exec($sql);
$db->query("INSERT INTO shorter VALUES (0, 'example', 'http://fugafu.ga/')");

$sql = 'SELECT * FROM shorter';
$res = $db->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>OpenShorter</title>
</head>

<body>
	<?php if ($url == "") { ?>
		<div class="loading" id="loading-div">
			<div class="loading-child">
				<img src="/fox.gif" style="left: 50%;position: absolute;top: -50%;transform: translate(-50%, -50%);">
				<p style="text-align: center; display: block;font-size: 34px;"><label style="color: #cc528b">Open</label><label style="color: #65ace4">Shorter</label></p>
			</div>
		</div>
		<?php
		@ob_flush();
		@flush();
		sleep(2.5);
		?>
		<script>
			var fadeOut = function(element, time, callback) {
				var fadeTime = (time) ? time : 400,
					keyFrame = 30,
					stepTime = fadeTime / keyFrame,
					minOpacity = 0,
					stepOpacity = 1 / keyFrame,
					opacityValue = 1;

				if (!element) return;

				element.setAttribute('data-fade-stock-display', element.style.display.replace('none', ''));

				var setOpacity = function(setNumber) {
					if ('opacity' in element.style) {
						element.style.opacity = setNumber;
					} else {
						element.style.filter = 'alpha(opacity=' + (setNumber * 100) + ')';

						if (navigator.userAgent.toLowerCase().match(/msie/) &&
							!window.opera && !element.currentStyle.hasLayout) {
							element.style.zoom = 1;
						}
					}
				};

				if (!callback || typeof callback !== 'function') callback = function() {};

				setOpacity(1);

				var sId = setInterval(function() {
					opacityValue = Number((opacityValue - stepOpacity).toFixed(12));

					if (opacityValue < minOpacity) {
						opacityValue = minOpacity;
						element.style.display = 'none';
						clearInterval(sId);
					}

					setOpacity(opacityValue);

					if (opacityValue === minOpacity) callback();
				}, stepTime);

				return element;
			};
			fadeOut(document.getElementById("loading-div"), 250);
		</script>
		<?php
		$db->close(); ?>
	<?php } ?>
</body>
<?php
$db->close(); ?>

</html>