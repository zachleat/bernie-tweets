<?php $SUBDIR = "/bernie/"; ?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Bernie’s Posterboard Tweets</title>
		<style>
		* {
			box-sizing: border-box;
		}
		body {
			background-color: black;
			/* courtesy of http://www.nbcnews.com/news/us-news/bernie-sanders-makes-big-statement-oversized-trump-tweet-n703296 */
			background-image: url(<?php echo $SUBDIR; ?>img/original.jpg);
			background-size: 100% auto;
			background-repeat: no-repeat;
			background-position: 0 0;
			font-family: sans-serif;
			margin: 0;
			padding: 0;
			overflow: hidden;
		}
		/* Form is an enhancement */
		form {
			display: none;
		}
		.js form {
			display: block;
		}
		.embed {
			display: none;
			position: absolute;
			left: 51.7%;
			top: 0;
			width: 500px;
			height: 373px;
			overflow: hidden;
			background-color: #fff;
			transform-origin: 0 0;
			z-index: 2;
			transform: rotateX( 0 ) rotateZ( 1.9deg ) scale( .575 );
			/*outline: 4px solid rgba( 255, 0, 0, .5);*/
		}
		.js .embed {
			display: block;
		}
		twitterwidget::shadow .EmbeddedTweet {
			border: none;
		}
		.capture {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			color: #fff;
			text-transform: uppercase;
			font-size: .6875em; /* 12px */
			font-weight: 400;
			padding: .5em; /* 6px / 12 */
			cursor: pointer;
			color: #fff;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		.capture:before {
			content: " ";
			position: absolute;
			left: 0;
			top: 0;
			width: 34em;
			height: 2em;
			background-color: rgba(0, 0, 0, .2);
			z-index: -1;
		}
		.capture span,
		.capture a {
			color: #ddd;
			font-weight: 400;
		}
		.capture a {
			margin-left: .5em;
		}
		.captured {
			display: none;
		}
		.active-tweet {
			display: none;
			position: absolute;
			top: calc( 50% - 2.5em );
			left: 0;
			width: 100%;
			height: 5em;
			z-index: 3;
			text-align: center;
			color: #fff;
			background-color: rgba( 0, 0, 0, .4 );
			font-size: 2em;
		}
		.active-tweet label {
			display: block;
			width: 90%;
		}
		.url-value {
			font-size: 1em;
			width: 100%;
			margin-top: .25em; /* 4px /16 */
			text-align: center;
		}
		.js .embed,
		.captured:checked ~ .active-tweet {
			display: -webkit-flex;
			display: flex;
			-webkit-flex-direction: row /* works with row or column */
			flex-direction: row;
			-webkit-align-items: center;
			align-items: center;
			-webkit-justify-content: center;
			justify-content: center;
		}
		.active-tweet input[type="submit"] {
			display: none;
		}
		</style>
		<script>
		document.documentElement.className += " js";
		</script>
	</head>
	<body>
		<form>
			<label class="capture" for="capture">Bernie’s Posterboard Tweets: <a href="<?php echo $SUBDIR; ?>realDonaldTrump/266038556504494082">Sample</a> <a href="<?php echo $SUBDIR; ?>realDonaldTrump/596338364187602944">Original</a> <a href="<?php echo $SUBDIR; ?>zachleat/785495065913274369">Classic</a></label>
			<input type="checkbox" id="capture" class="captured">
			<div class="active-tweet">
				<label>
					URL or Tweet ID
					<input type="text" name="url-value" id="url-value" class="url-value" value="https://twitter.com/zachleat/status/785495065913274369">
				</label>
				<input type="submit" value="Change">
			</div>
		</form>
		<div class="embed" id="embed"></div>
		<script>
		(function() {
			function update() {
				// 27.9527559% of image/viewport width
				// 498 static width
				// 498 * scaleX / windowWidth = .2795
				var viewportWidth = window.innerWidth
					|| document.documentElement.clientWidth
					|| document.body.clientWidth;

				var embed = document.getElementById( "embed" );
				var scaleX = .279527559 * viewportWidth / 498;

				// aspect ratio of image: 1776/963
				// height of image: viewportWidth / aspectratio
				var translateYAdjustor = 120;
				var imageAspectRatio = 1776 / 963;
				var translateY = viewportWidth * ( translateYAdjustor / 1440 ) / imageAspectRatio;

				embed.style.transform = "rotateX( 0 ) rotateZ( 1.9deg ) translateY( " + translateY + "px ) scale( " + scaleX + " )";
			}

			window.addEventListener( "DOMContentLoaded", update, false );
			window.addEventListener( "resize", update, false );

			function newtweet( id ) {
				var embed = document.getElementById( "embed" );
				embed.innerHTML = "";
				twttr.widgets.createTweet(
					id,
					embed,
					{}
				);
			}

			function onchange( event ) {
				var val = this.value;
				var user = val.match( /([^\/]+)\/status\/\d{2,}/ );
				var id = val.match( /\d{2,}/ );

				if( id ) {
					newtweet( id[ 0 ] );
				}
				if( user ) {
					history.replaceState( {}, "", "/" + user[ 1 ] + "/" + id[ 0 ] );
				}

				if( event.type === "input" ) {
					this.removeEventListener( "change", onchange );
				}
			}

			window.addEventListener( "DOMContentLoaded", function() {
				var input = document.getElementById( "url-value" );
				input.addEventListener( "input", onchange, false );
				input.addEventListener( "change", onchange, false );

				var form = document.getElementsByTagName( "form" );
				if( form.length ) {
					form[ 0 ].addEventListener( "submit", function( event ) {
						event.preventDefault();

						document.getElementById( "capture" ).checked = false;
					}, false );
				}
			}, false );

			function initialize() {
<?php
				if( !empty( $_GET[ "id"] ) ) {
?>
				var user = "<?php echo htmlspecialchars( $_GET[ "user"] ); ?>";
				var id = "<?php echo htmlspecialchars( $_GET[ "id"] ); ?>";
				var input = document.getElementById( "url-value" );
				input.value = "https://twitter.com/" + user + "/status/" + id;

				newtweet( id );
<?php
				}
?>
			}
			window.WeekendAtBernies = initialize;
		})();
		</script>
		<script async src="//platform.twitter.com/widgets.js" charset="utf-8" onload="WeekendAtBernies()"></script>
	</body>
</html>