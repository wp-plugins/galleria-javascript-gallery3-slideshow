<?php

/*

Plugin Name: Galleria Javascript Slideshow fed from Menalto Gallery3 Album

Plugin URI: http://www.gregwhitehead.us/

Description: Galleria Javascript Slideshow (http://galleria.io/) that is fed from an external Menalto Gallery3 (http://galleryproject.org/) album rss feed.  Creates a one page javascript slideshow with autoplay with time being able to be set via slidespeed or set to false for no autoplay.  Other settings able to be set through shortcode call. Includes all galleria javascript files needed.  [g3gs albumnum="81" slidespeed="3000" preload="2" showinfo="false" imagecrop="false" transition="fade" clicknext="true" pauseoninteraction="true" gallerypath="/g3/"]

Version: 1.0

Author: Greg Whitehead

Author URI: http://www.gregwhitehead.us/

*/



function gallery3galleriaslideshow_method() {

	$pluginUrl = plugin_dir_url( __FILE__ );

	wp_enqueue_script(

		'custom-script',

		$pluginUrl . '/galleria/galleria-1.2.8.min.js',

		array( 'jquery' )

	);

}



add_action( 'wp_enqueue_scripts', 'gallery3galleriaslideshow_method' );



function gallery3galleriaslideshow($atts) {

	$pluginUrl = plugin_dir_url( __FILE__ );



	extract(shortcode_atts(array(

		'albumnum' => '1',
		'slidespeed' => '2000',
		'preload' => '2',

		'showinfo' => 'false',

		'imagecrop' => 'false',

		'transition' => 'fade',

		'clicknext' => 'true',

		'pauseoninteraction' => 'true',
		
		'backgroundcolor' => '#fff',

		'gallerypath' => '/gallery/',

	), $atts));

		

	$newHtml = '<style>

	#galleria{

				position: relative;

				height:600px;

				margin-top:30px;

				background-color: '.$backgroundcolor.';

				-webkit-box-shadow: 0px 0px 12px 3px rgba(153, 153, 153, .4);

				-moz-box-shadow: 0px 0px 12px 3px rgba(153, 153, 153, .4);

				box-shadow: 0px 0px 12px 3px rgba(153, 153, 153, .4);

			}
		.galleria-container {
				background-color: '.$backgroundcolor.';
		}
	 

	</style>

	

		<div class="content">

			<div id="galleria"><img style="position:absolute;top:20px;right:20px;" src="'.$pluginUrl.'/galleria/themes/classic/classic-loader.gif"/></div>

			<div id="theButton" class="play"><a href="#"></a></div>

		</div>

		<script>

			var gallery, images = [], tmpImage, tmpBigImage ;

			jQuery(document).ready(function(){

				jQuery(\'title\').text(\'Galleria Classic Theme\');

				Galleria.loadTheme(\''.$pluginUrl.'/galleria/themes/classic/galleria.classic.min.js\');

				getItems(\''.$gallerypath.'index.php/rss/feed/gallery/album/'. $albumnum.'\');

			});

			function getItems(earl){

				jQuery.get(earl, {}, function(data){

				var next = jQuery(data).find(\'atom\\\\:link[rel="next"]\').attr(\'href\');

				//console.log(earl);

				jQuery(data).find(\'item\').each(function(){

					tmpImage = jQuery(this).children(\'media\\\\:group\').children(\'media\\\\:content\').first().attr(\'url\');

					tmpBigImage = jQuery(this).children(\'media\\\\:group\').children(\'media\\\\:content\').last().attr(\'url\');

					if (tmpImage == \'\' || tmpImage == null) tmpImage = jQuery(this).children(\'media\\\\:content\').attr(\'url\');

					if (tmpBigImage == \'\' || tmpBigImage == null) tmpBig = jQuery(this).children(\'media\\\\:content\').attr(\'url\');

					images.push({

						image: tmpImage,

						thumb: jQuery(this).children(\'media\\\\:thumbnail\').attr(\'url\'),

						big: tmpBigImage ,

						title: jQuery(this).children(\'title\').text(),

						description: jQuery(this).children(\'description\').text(),

						link: jQuery(this).children(\'link\').text()

					});

				});

				if (next != undefined) {

					getItems(next);

				} else {

					jQuery(\'#galleria\').galleria({

						data_source: images,

						showInfo: '.$showinfo.',

						imageCrop: '.$imagecrop.',

						transition: \''.$transition.'\',

						clicknext:'.$clicknext.',

				

					autoplay:'.$slidespeed.',

				

					pauseOnInteraction:'.$pauseoninteraction.',

				

					preload: '.$preload.',

				

				

					});

					jQuery(\'#theButton, #pure\').show(\'slow\');

					gallery = Galleria.get(0);

					jQuery(\'.play a\').hover(function(){jQuery(this).addClass(\'over\');},function(){jQuery(this).removeClass(\'over\');});

					jQuery(\'.play a\').on(\'click\', function(e){

						e.preventDefault();

						jQuery(\'#pure\').remove();

						jQuery(this).parent().toggleClass(\'pause\').toggleClass(\'play\');

						gallery.playToggle();

					});

				}

				});

				return

			}

		</script>';

		

		return $newHtml; 

}



add_shortcode ('g3gs','gallery3galleriaslideshow');

	?>