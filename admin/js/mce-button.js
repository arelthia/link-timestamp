(function() {
	tinymce.PluginManager.add('lts_mce_button', function( editor, url ) {
		editor.addButton('lts_mce_button', {
			title: 'Insert Link for Time Stamp',
            image: url + '/icon.png',
			onclick : function() {
			var stext = (editor.selection.getContent({format : 'text'}) != '') ? editor.selection.getContent({format : 'text'}) : '00:00';	
           	editor.windowManager.open( {
           		title: 'Link Settings',
           		body: [
           			{
           				type: 'textbox',
						name: 'timestamp',
						label: 'Time',
						value: '00:00',
						tooltip: 'Enter time as hh:mm:ss for example 01:10:25 or mm:ss for example 00:45.'
           			},
					{
           				type: 'textbox',
						name: 'timetext',
						label: 'Text to link',
						value: stext
           			}
           		],
				onsubmit: function( e ) {
					editor.insertContent( '[linktimestamp time=' + e.data.timestamp + ']' + e.data.timetext + '[/linktimestamp]');
					//editor.insertContent('<a class="ps_lts_tslink" data-time="' + e.data.timestamp + '">' + e.data.timetext + '</a>')
				}
           	});	//end editor.windowManager.open
    
         	}//end onclick
		});//end addbutton

	}); //end tinymce.PluginManager.add
})();
