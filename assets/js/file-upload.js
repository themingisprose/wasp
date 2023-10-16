// File upload
let fileUploader = document.getElementsByClassName('wasp-file-uploader');

for( let i = 0; i < fileUploader.length; i++ ){
	let item 	= fileUploader[i];
	let btn		= item.getElementsByClassName('insert-file-button');
	let clear	= item.getElementsByClassName('clear-file-button');

	btn[0].addEventListener('click', function(){
		let urlID 	= this.getAttribute('data-url');
		let inputID = this.getAttribute('data-input');
		let url		= document.getElementById(urlID);
		let input	= document.getElementById(inputID);

		// Configuration of the media manager new instance
		mediaFrame = wp.media({
			title: file_frame_l10n.file_frame_windows_title,
			button:{
				text: file_frame_l10n.file_frame_button_title,
			}
		})

		let theUploader = function(){
			let selection = mediaFrame.state().get('selection');

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

	clear[0].addEventListener('click', function(){
		let urlID 	= this.getAttribute('data-url');
		let inputID = this.getAttribute('data-input');
		let url		= document.getElementById(urlID);
		let input	= document.getElementById(inputID);
		url.value = '';
		input.value = '';
	})
}
