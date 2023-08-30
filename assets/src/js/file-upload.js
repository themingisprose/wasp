// File upload

let fileUploader = document.getElementsByClassName('file-uploader');

for( item of fileUploader ){
	let button 	= item.querySelector('.insert-file-button');
	let clear 	= item.querySelector('.clear-file-button');
	var mediaFrame;

	button.addEventListener('click', function(event){
		event.preventDefault();

		var urlID 	= event.target.getAttribute('data-url');
		var inputID = event.target.getAttribute('data-input');
		var url		= document.getElementById(urlID);
		var input	= document.getElementById(inputID);

		// Check for media manager instance
		if(mediaFrame){
			mediaFrame.open();
			return;
		}

		// configuration of the media manager new instance
		mediaFrame = wp.media({
			title: file_frame_l10n.file_frame_windows_title,
			button:{
				text: file_frame_l10n.file_frame_button_title,
			}
		})

		var theUploader = function(){

			var selection = mediaFrame.state().get('selection');

			if( ! selection ){
				return;
			}

			selection.forEach(function(item){
				input.value = item.attributes.id;
				url.value = item.attributes.url;
			});

		}

		// closing event for media manger
		mediaFrame.on('close');
		// media selection event
		mediaFrame.on('select', theUploader);
		// showing media manager
		mediaFrame.open();
	})

	clear.addEventListener('click', function(event){
		event.preventDefault();

		var urlID 	= event.target.getAttribute('data-url');
		var inputID = event.target.getAttribute('data-input');
		var url		= document.getElementById(urlID);
		var input	= document.getElementById(inputID);

		url.value = '';
		input.value = '';
	})
}
