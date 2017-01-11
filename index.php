<?php $SUBDIR = "/bernie/"; ?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Bernie’s Tweets</title>
		<style>
		* {
			box-sizing: border-box;
		}
		body {
			background-color: black;
			/* courtesy of http://www.nbcnews.com/news/us-news/bernie-sanders-makes-big-statement-oversized-trump-tweet-n703296 */
			background-image: url(<?php echo $SUBDIR; ?>img/bg-rerendered.jpg);
			background-size: 100% auto;
			background-repeat: no-repeat;
			background-position: 0 0;
			font-family: sans-serif;
			margin: 0;
			padding: 4.5% 0 0 0;
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
			position: relative;
			left: 51.7%;
			top: 0;
			width: 500px;
			height: 373px;
			overflow: hidden;
			background-color: #fff;
			transform-origin: 0 0;
			z-index: 2;
			transform: rotateX( 0 ) rotateZ( 1.9deg );
			/*outline: 4px solid rgba( 255, 0, 0, .5);*/
		}
		.js .embed {
			display: block;
		}
		/* Twitter widget overrides */
		twitterwidget::shadow .EmbeddedTweet {
			border: none !important;
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
			width: 26em;
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
		@media ( max-width: 63.9375em ) { /* 1023px */
			body {
				background-position: 0 1.5em;
			}
			.embed {
				top: 1.5em;
			}
		}
		</style>
		<script>
		document.documentElement.className += " js";
		</script>
	</head>
	<body>
		<form>
			<label class="capture" for="capture">Bernie’s Tweets: <a href="<?php echo $SUBDIR; ?>realDonaldTrump/266038556504494082">Sample</a> <a href="<?php echo $SUBDIR; ?>realDonaldTrump/596338364187602944">Original</a> <a href="<?php echo $SUBDIR; ?>zachleat/785495065913274369">Classic</a></label>
			<input type="checkbox" id="capture" class="captured">
			<div class="active-tweet">
				<label>
					URL or Tweet ID
					<input type="text" name="url-value" id="url-value" class="url-value" value="https://twitter.com/zachleat/status/785495065913274369">
				</label>
				<input type="submit" value="Change">
			</div>
		</form>
		<div class="embed" id="embed">
			<div class="scale" id="scale"></div>
		</div>
		<script>
		(function() {
			function update( elementHeight ) {
				var embed = document.getElementById( "embed" );
				var scale = document.getElementById( "scale" );

				// 27.9527559% of image/viewport width
				// 498 static width
				// 498 * scaleX / windowWidth = .2795
				var scaleX = .279527559 * document.body.clientWidth / 498;

				elementHeight = parseInt( elementHeight, 10 );
				if( elementHeight && elementHeight > 373 ) {
					var scaleY = ( 373 / elementHeight );
					scale.style.transform = "scale( " + scaleY + " )";
				}

				embed.style.transform = "rotateX( 0 ) rotateY( 2deg ) rotateZ( 2.1deg ) scale( " + scaleX + ")";
			}

			window.addEventListener( "DOMContentLoaded", update, false );
			window.addEventListener( "resize", update, false );

			function newtweet( id ) {
				var scale = document.getElementById( "scale" );
				scale.innerHTML = "";
				twttr.widgets.createTweet(
					id,
					scale,
					{}
				).then(function( el ) {
					var height = el.offsetHeight;
					update( height );
				});
			}

			function onchange( event ) {
				var val = this.value;
				var user = val.match( /([^\/]+)\/status\/\d{6,}/ );
				var id = val.match( /\d{6,}/ );

				if( id ) {
					newtweet( id[ 0 ] );
				}
				if( user ) {
					history.replaceState( {}, "", "<?php echo $SUBDIR; ?>" + user[ 1 ] + "/" + id[ 0 ] );
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
				} else {
?>
				newtweet( "785495065913274369" );
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