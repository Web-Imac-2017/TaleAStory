function editPicture(inputFile, divimg){

	  var file = inputFile.files[0];
  	var reader = new FileReader();
  	let that = this;
    
  	reader.addEventListener("load", function () {
  		var image = new Image();
    	image.height = 100;
    	image.title = file.name;
    	image.src = this.result;
      image.className = "bigProfil";
    	divimg.classList.remove('empty');

    	while (divimg.firstChild) {
    		divimg.removeChild(divimg.firstChild);
    	}

    	divimg.appendChild( image );

    }, false);

     reader.readAsDataURL(file);
 }

 export default editPicture;