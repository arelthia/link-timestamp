var lts = {
	settings: ltsettings, //plugin settings
	media: undefined,
	linkTo: undefined,
	isHTML5: false,
	isYoutube: false,

	doHTML5lts: function() {
		lts.media.removeEventListener('canplaythrough', lts.doHTML5lts);
		lts.media.currentTime = lts.linkTo;
		lts.media.play();
	},

	doYoutubelts: function() {
		lts.media.seekTo(lts.linkTo);
		lts.media.playVideo();
	},

	doVimeolts: function() {
		lts.media.setCurrentTime(lts.linkTo);
	}

};


var LinkTS = function(time) {
	var audio 		= document.getElementsByTagName('audio'),
		video 		= document.getElementsByTagName('video'),
		iframe      = document.getElementsByTagName('iframe'),
		timeArray 	= time.split(':').reverse(),
		seconds 	= parseInt(timeArray[0]),
		minutes   	= timeArray.length > 1 ? parseInt(timeArray[1]) : 0,
		hours	 	= timeArray.length > 2 ? parseInt(timeArray[2]) : 0;

	lts.linkTo = seconds + (minutes * 60) + (hours * 3600);

	if (lts.media) {
		lts.doSkip();
		return;
	}

	if ((parseInt(lts.settings.link_audio) && audio.length) ||
		(parseInt(lts.settings.link_video) && video.length))
	{
		lts.doSkip = lts.doHTML5lts;

		if (parseInt(lts.settings.link_audio) && audio.length) {
			lts.media = audio[0];
		} else {
			lts.media = video[0];
		}

		lts.media.addEventListener('canplaythrough', lts.doHTML5lts);
		lts.media.load();
		lts.media.play();
		return;
	}

	if (parseInt(lts.settings.link_youtube && iframe.length)) {

		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('youtube') !== -1) {

				lts.doSkip = lts.doYoutubelts;

				iframe[0].id = 'lts-youtube-player';
				lts.media = new YT.Player('lts-youtube-player', {
					events: {
						onReady: lts.doYoutubelts
					}
				});
				return;
			}
		}
	}

	if (parseInt(lts.settings.link_vimeo && iframe.length)) {
		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('vimeo') !== -1) {
				lts.doSkip = lts.doVimeolts;
				iframe[0].id = 'lts-vimeo-player';
				lts.media = new Vimeo.Player('lts-vimeo-player');
				lts.media.on('play', lts.doVimeolts );
				lts.media.play();
				return;
			}
		}
	}

	console.log('No audio or video found on page');
	return;
};

jQuery(document).ready( function($) {
    $('.ps_lts_tslink').css('cursor', 'pointer');
	$('body').on('click','.ps_lts_tslink', function(){
		timeclicked = $(this).data("time");
		LinkTS(timeclicked);
	});

});