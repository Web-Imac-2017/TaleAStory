import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config'
import RouteComponent from '../utils/routecomponent';
import {User} from '../model/user';

export default RouteComponent({
  contextTypes : {user: React.PropTypes.objectOf(User)},

  getInitialState(){
    if(this.props.params.stepid){

    }
    return {
      imgpath : '',
      body : '',
      question : '',
      title : ''
    };
  },
	handleChange(event) {
    this.setState({
      imgpath : this.refs.imgpath.value,
      body : this.refs.body.value,
      question : this.refs.question.value,
      title : this.refs.question.value
    });

    //image
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

    reader.readAsDataURL(file);
	},

	handleSubmit(event) {
		event.preventDefault();

    var form = ReactDOM.findDOMNode(this).getElementsByClassName('form')[0];

    fetch('/users', {
      method: 'POST',
      body: new FormData(form)
    }).then(function(response){
      return response.json()
    }).then(function(json){
      this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
    });

	},

  handleImage(){
    this.refs.imgpath.click();
  },

  returnSteps(){
    this.context.router.push(config.path('profils/admin/steps'));
  },

  render(){
    let unavailable = true;
    if(this.context.user)
      if(this.context.user.isAdmin)
        unavailable = false;
      
    if(unavailable)
      return <div onClick={this.unselect}>
                <div className="columnsContainer">
                  <div className="content contentProfil contentSteps">
                    <div className="contentRight">
                      unavailable
                    </div>
                  </div>
                </div>
              </div>


    let image;
    if(this.state.img){
      image = <div className="image" onClick={this.handleImage}>
                <img src={this.state.imgpath} alt="image de la péripétie"></img>
              </div>
    }
    else{
      image = <div className="image empty" onClick={this.handleImage}>
                <img src={config.imagePath('default_image_tiny.png')} alt="image de la péripétie"></img>
              </div>
    }
		return  <div className="form-screen editing">
    				  <div className="content">
                <div className="block">
                  <button onClick={this.returnSteps}>&lt;</button>
                </div>
    						<div className="block form">
                  <h1 className="element pageTitle">Objet</h1>
                  <div>
                    {image}
                    <form className="element" onSubmit={this.handleSubmit} method="post" encType="multipart/form-data">
                      <input name="image" type="file" accept='image/*' value={this.state.imgpath}
                                   onChange={this.handleChange} ref="imgpath"
                                   multiple={false} style={{display:"none"}}/>
                                 <span><input name="title" type="text" placeholder="Nom de la réussite"
                        value={this.state.title} onChange={this.handleChange} ref="title" /></span>
                      <textarea name="body" type="text" placeholder="Description" value={this.state.body}
                        onChange={this.handleChange} ref="body" />
                      <span className="button" ><input className="submit" type="submit" value="Enregistrer"/></span>
                    </form>
                  </div>
    						</div>
    					</div>
            </div>

    }
});
