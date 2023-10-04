// Media upload
let mediaUploader = document.getElementsByClassName('wasp-media-uploader');

for( let i = 0; i < mediaUploader.length; i++ ){
	item 	= mediaUploader[i];
	let btn = item.getElementsByClassName('insert-media-button');

	btn[0].addEventListener('click', function(){
		var wrapperID 	= this.getAttribute('data-wrapper');
		var inputID 	= this.getAttribute('data-input');
		wrapper			= document.getElementById(wrapperID);
		input			= document.getElementById(inputID);

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
				thumbnail.setAttribute('src', item.attributes.sizes.thumbnail.url);
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

for( let i = 0; i < mediaRemover.length; i++ ){
	let item = mediaRemover[i]
	item.addEventListener('click', function(){
		let parent 		= item.closest('.wasp-media-uploader');
		let input 		= parent.getElementsByTagName('input');
		let thumbnailID = item.getAttribute('data-thumbnail-id');
		let wrapperID 	= item.getAttribute('data-remove');
		let wrapper 	= document.getElementById(wrapperID);
		var ids 		= [];

		var value = input[0].value.split(',');
		wrapper.remove();

		for( i in value ){
			if( value[i] != thumbnailID ){
				ids.push(value[i]);
			}
		}
		input[0].value = ids.toString();
	})
};
