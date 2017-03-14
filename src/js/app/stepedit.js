import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config'
import RouteComponent from '../utils/routecomponent';

export default RouteComponent({

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

    var form = ReactDOM.findDOMNode(this).getElementsByTagName('form')[0];
    fetch(config.path('addstep'), {
      method: 'POST',
      body: new FormData(form)
    }).then(function(response){
      return response.json()
    }).then(function(json){
      console.log(json);
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
                      <h1 className="element pageTitle">Péripétie</h1>
                      <div>
                        {image}
                        <form className="element" onSubmit={this.handleSubmit} method="post" encType="multipart/form-data">
                          <input name="image" type="file" accept='image/*' value={this.state.imgpath}
                                       onChange={this.handleChange} ref="imgpath"
                                       multiple={false} style={{display:"none"}}/>
                         <span>
                            <select name="idtype">
                              <option value="2">Décision</option>
                              <option value="4">Enigme</option>
                            </select>
                         </span>
                          <span><input name="title" type="text" placeholder="Titre de la péripétie"
                            value={this.state.title} onChange={this.handleChange} ref="title" /></span>
                         <textarea name="body" type="text" placeholder="Que fait le personnage ?" value={this.state.body}
                            onChange={this.handleChange} ref="body" />
                          <span><input name="question" type="text" placeholder="Question au joueur"
                           value={this.state.question} onChange={this.handleChange} ref="question" /></span>
                         <span className="button" ><input className="submit" type="submit" value="Enregistrer"/></span>
                        </form>
                      </div>
                                </div>
                            </div>
                </div>

        }
});
