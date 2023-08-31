// Media upload

let mediaUploader = document.getElementsByClassName('media-uploader');

for( item of mediaUploader ){
	let button 	= item.getElementsByTagName('button');
	var mediaFrame;

	button[0].addEventListener('click', function(event){
		event.preventDefault();

		var wrapperID 	= event.target.getAttribute('data-wrapper');
		var inputID 	= event.target.getAttribute('data-input');
		wrapper			= document.getElementById(wrapperID);
		input			= document.getElementById(inputID);

		// Check for media manager instance
		if(mediaFrame){
			mediaFrame.open();
			return;
		}

		// configuration of the media manager new instance
		mediaFrame = wp.media({
			title: media_frame_l10n.media_frame_windows_title,
			multiple: 'add',
			button:{
				text: media_frame_l10n.media_frame_button_title,
			}
		})

		var theUploader = function(){

			var selection = mediaFrame.state().get('selection');

			if( ! selection ){
				return;
			}

			var ids = ( '' != input.value ) ? input.value.split(',') : [];

			selection.forEach(function(item){

				ids.push(item.attributes.id);

				var thumbnail = document.createElement('img');
				thumbnail.setAttribute('src', item.attributes.sizes.full.url);
				thumbnail.setAttribute('style', 'display: flex; flex-direction: column; margin: .5rem');
				thumbnail.setAttribute('height', 150);
				thumbnail.setAttribute('width', 150);
				wrapper.appendChild(thumbnail);
			});

			input.value = ids.toString();
		}

		// closing event for media manger
		mediaFrame.on('close');
		// media selection event
		mediaFrame.on('select', theUploader);
		// showing media manager
		mediaFrame.open();
	})
}

// Remove image

let mediaRemover = document.getElementsByClassName('img-remover');
Array.from(mediaRemover).forEach(function(item){
	item.onclick = function(){

		let parent 		= item.closest('.media-uploader');
		let input 		= parent.getElementsByTagName('input');
		let thumbnailID = item.getAttribute('data-remove');
		let wrapper 	= document.getElementById('thumbnail-'+ thumbnailID);
		var ids 		= [];

		var value = input[0].value.split(',');
		wrapper.remove();

		for( i in value ){
			if( value[i] != thumbnailID ){
				ids.push(value[i]);
			}
		}
		input[0].value = ids.toString();

	}
});
