var lts = {
	settings: ltsettings, //plugin settings
	media: undefined,
	linkTo: undefined,
	isHTML5: false,
	isYoutube: false,
	isVimeo: true,
	isLibsyn: false,
	isDynamic: false,

	doHTML5lts: function() {
		isHTML5 = true;
		lts.media.removeEventListener('canplaythrough', lts.doHTML5lts);
		lts.media.currentTime = lts.linkTo;
  		lts.media.play();
		
		
	},

	doYoutubelts: function() {
		isYoutube = true;
		lts.media.seekTo(lts.linkTo);
		lts.media.playVideo();
	},

	doVimeolts: function() {
		isVimeo = true;
		lts.media.setCurrentTime(lts.linkTo).then(function() {
			return lts.media.play();
		});
	},

	doLibsynlts: function() {
		isLibsyn = true;
		lts.media.setCurrentTime(lts.linkTo);
		//lts.media.play();
	}

};

var LinkTS = function(time) {
	var audio 		= document.getElementsByTagName('audio'),
		video 		= document.getElementsByTagName('video'),
		iframe      = document.getElementsByTagName('iframe'),
		mejs 		= document.getElementsByClassName('smart-track-player'),
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

	/*start youtube support*/
	if (parseInt(lts.settings.link_youtube && iframe.length)) {

		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('youtube') !== -1) {

				lts.doSkip = lts.doYoutubelts;

				iframe[i].id = 'lts-youtube-player';
				//location.href = '#' + iframe[i].id;
				lts.media = new YT.Player('lts-youtube-player', {
					events: {
						onReady: lts.doYoutubelts
					}
				});
				return;
			}
		}
	}


	/*start vimeo support*/
	if (parseInt(lts.settings.link_vimeo && iframe.length)) {

		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.includes('vimeo')) {
				lts.doSkip = lts.doVimeolts;
				iframe[i].id = 'lts-vimeo-player';
				lts.media = new Vimeo.Player('lts-vimeo-player');
				lts.media.on('play', lts.doVimeolts );
				lts.media.play();
				return;
			}
		}
	}

	/*start soundmanager support for smart podcast player*/
	if (parseInt(lts.settings.link_spp && mejs.length)) {
		var listSoundIds = soundManager.soundIDs; 
	    var soundId = soundManager.soundIDs[0];
    
	    if (listSoundIds[0] == null) {
	       	if (document.querySelector('.spp-play-pause') !== null) {
  				document.querySelector( '.spp-play-pause').click();
			}else{
	       	 	document.querySelector( ".spp-play").click();
	      	}
	      	soundId = soundManager.soundIDs[0];
	      
	      	soundManager.getSoundById(soundId).setPosition(lts.linkTo*1000);
	      	mejs.className += "spp-playing";
	      	return;
	    }         
	    else if (soundManager.getSoundById(soundId).paused) {
	        soundManager.getSoundById(soundId).setPosition(lts.linkTo*1000);
	        setTimeout(function(){
	          soundManager.resume(soundId);
	        },500);  
	        mejs.className += "spp-playing";
	        return;
	    } 
	    else{ 
	      	soundManager.getSoundById(soundId).setPosition(lts.linkTo*1000);
	      	return;
	  }
	}   

	/*start soundcloud*/
	if (parseInt(lts.settings.link_sc && iframe.length)) {
		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('soundcloud') !== -1) {
				var soundCloudIframe = document.querySelector('iframe'),
				soundCloudWidget = SC.Widget(soundCloudIframe);
				soundCloudWidget.bind(SC.Widget.Events.PLAY, function() {

					soundCloudWidget.seekTo(lts.linkTo*1000);
					soundCloudWidget.unbind(SC.Widget.Events.PLAY);

				});  
			
				soundCloudWidget.play();
				return;
			}
		}	
	}


		/*start libsyn support*/
	if (parseInt(lts.settings.link_libsyn && iframe.length)) {

		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('libsyn') !== -1) {
				lts.doSkip = lts.doLibsynlts;
				iframe[i].id = 'lts-libsyn-player';
				playerframe = document.getElementById('lts-libsyn-player');
				var lsplayer = new playerjs.Player(playerframe);
				lts.media = lsplayer;
				
				lts.media.on('ready', lts.doLibsynlts);
				
				return;
			}
		}
	}

		/*start spreaker support*/
	if (parseInt(lts.settings.link_spreaker && iframe.length)) {

		for (var i = 0; i < iframe.length; i++) {
			if (iframe[i].src.search('spreaker') !== -1) {
				lts.doSkip = function(){
					lts.media.seek(lts.linkTo * 1000);
				};
				iframe[i].id = iframe[i].hasAttribute('id') ? iframe[i].id : 'lts-spreaker-player';		
				
				lts.media = SP.getWidget(iframe[i].id);

				//lts.media = SP.getWidget(iframe[i]);
				
				lts.media.seek(lts.linkTo * 1000);

				if(lts.isDynamic != true){
					lts.media.play();
				}
				

				//lts.media.play();

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
		lts.isDynamic = false;
		timeclicked = $(this).data("time");
		LinkTS(timeclicked);
	});
});

//if link to timestamp on another page is enabled
window.addEventListener('load', function(){
  	if( lts.isLibsyn ){
		return;
	}

	const queryString = window.location.search;
	if(queryString != ''){
		const ltsUrlParams = new URLSearchParams(queryString);
		if (ltsUrlParams.has('ltstime')){
			lts.isDynamic = true;
			timeclicked = ltsUrlParams.get('ltstime');
			LinkTS(timeclicked);
			
		}

	}

});
