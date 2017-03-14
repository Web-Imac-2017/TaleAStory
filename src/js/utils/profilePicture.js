function editPicture(){

	console.log("gros prout");

	var file = this.refs.imgpath.files[0];
  	var reader = new FileReader();
  	let that = this;
    
  	reader.addEventListener("load", function () {
  		var image = new Image();
    	image.height = 100;
    	image.title = file.name;
    	image.src = this.result;
    	let divimg = ReactDOM.findDOMNode(that).getElementsByClassName('image')[0];
    	divimg.classList.remove('empty');

    	while (divimg.firstChild) {
    		divimg.removeChild(divimg.firstChild);
    	}

    	divimg.appendChild( image );

    }, false);
 
 }

 export default editPicture;