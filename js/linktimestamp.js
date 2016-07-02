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
	}

};

LinkTS = function(time) {
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
	} else if (parseInt(lts.settings.link_youtube && iframe.length)) {
		// Inspect the iframes, looking for a src with youtube in the URI
		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('youtube') !== -1) {
				// Set up the JS interface
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

	//console.log('No media player found!');
	return;
}