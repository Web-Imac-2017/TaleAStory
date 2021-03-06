import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config'
import RouteComponent from '../utils/routecomponent';
import {Requester} from '../utils/interfaceback';

export default RouteComponent({

  getInitialState(){
    let that = this;
    if(this.props.params.id){
      Requester.getStep(this.props.params.id).then(
        function(step){
          that.setState({
            body : step.Body,
            question : step.Question,
            title : step.Title,
            idtype : step.IDType,
            id : step.id
          });
        }
      );
    }

    Requester.stepTypes().then(function(result){
      result = JSON.parse(result.message);
      that.setState({types : result});
    })
    return {
      id : null,
      imgpath : '',
      body : '',
      question : '',
      title : '',
      idtype : 0,
      types : []
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
    if(this.refs.imgpath.files.length){
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
    }
	},

	handleSubmit(event) {
		event.preventDefault();
    let that = this;
    var form = ReactDOM.findDOMNode(this).getElementsByTagName('form')[0];
    if(!this.state.id){
      fetch(config.path('addstep'), {
        method: 'POST',
        body: new FormData(form),
        credentials: "same-origin"
      }).then(function(response){
        return response.json()
      }).then(function(json){
        that.context.router.push(config.path('profils/admin/steps/'));
      });
    }
    else{
      fetch(config.path('updatestep'), {
        method: 'POST',
        body: new FormData(form),
        credentials: "same-origin"
      }).then(function(response){
        return response.json()
      }).then(function(json){
        that.context.router.push(config.path('profils/admin/steps/'));
      });
    }


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
    let select = this.state.types.map((value,index) =>
      <option key={value.IDType} value={value.IDType}>{value.Name}</option>
    );
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
                        <input name="idstep" type="hidden" value={this.state.id}/>
                        <input name="image" type="file" accept='image/*' value={this.state.imgpath}
                                       onChange={this.handleChange} ref="imgpath"
                                       multiple={false} style={{display:"none"}}/>
                        <span>
                          <select name="idtype" ref="idtype" value={this.state.idtype}>
                            {select}
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
