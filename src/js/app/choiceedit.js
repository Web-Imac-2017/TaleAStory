import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config'
import RouteComponent from '../utils/routecomponent';
import Slider from 'rc-slider';
import ItemNav from './itemnav'
import Dialog from '../utils/dialog'
import TransitionGroup from 'react-addons-transition-group';


export default RouteComponent({

  getInitialState(){
    if(this.props.params.stepid){

    }
    this.sliders = [1,2,3].map((slider, index) =>
                                  <span key={index}>
                                    {slider}
                                    <Slider />
                                  </span>
                              );
    return {
      imgpath : '',
      transitiontext : '',
      question : '',
      answer : ''
    };
  },
	handleChange(event) {
    this.setState({
      transitiontext : this.refs.transitiontext.value,
      question : this.refs.question.value,
      answer : this.refs.question.value,
      search : this.refs.search.value,
      selectedItem : null
    });
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
  setStartStep(){
  },
  setEndStep(){
  },
  loadSteps(offset, count){
    let steps = [];
    for(let i=offset; i<offset + count; i++)
      steps.push(i);
    return steps;
  },
  selectHandler(item){
    this.setState({selectedItem:item});
  },
  cancelDialog(){
    this.setState({selectedItem:null});
  },
  setStart(){
    let that = this;
    this.refs.startDialog.show({
      title: 'Depart',
      body: <ItemNav loadSteps={that.loadSteps} selectHandler={that.selectHandler}/>,
      actions: [
        Dialog.Action(
          'Annuler',
          that.cancelDialog,
          'button btn-cancel'
        ),
        Dialog.Action(
          'OK',
          that.setStartStep,
          'button btn-confirm'
        ),
      ],
      bsSize: 'large',
      onHide: (dialog) => {
        dialog.hide()
      }
    });
  },

  setEnd(){
    let that = this;
    this.refs.endDialog.show({
      title: 'Arrivée',
      body: <ItemNav/>,
      actions: [
        Dialog.Action(
          'Annuler',
          that.cancelDialog,
          'button btn-cancel'
        ),
        Dialog.Action(
          'OK',
          that.setEndStep,
          'button btn-confirm'
        ),
      ],
      bsSize: 'large',
      onHide: (dialog) => {
        dialog.hide()
      }
    });
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
		return  <div className="form-screen editing">
    				  <div className="content large">
                <div className="block">
                  <button onClick={this.returnSteps}>&lt;</button>
                </div>
    						<div className="block form">
                  <h1 className="element pageTitle">Choice</h1>
                  <div>
                    <div className="item" onClick={this.setStart}>
                      <h3>Péripétie Départ</h3>
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="inputs">
                      <form className="element" onSubmit={this.handleSubmit} method="post" encType="multipart/form-data">
                        <span><input name="answer" type="text" placeholder="Réponse"
                          value={this.state.answer} onChange={this.handleChange} ref="answer" /></span>
                        <textarea name="transitiontext" type="text" placeholder="Texte de transition" value={this.state.transitiontext}
                          onChange={this.handleChange} ref="transitiontext" />
                      </form>
                      <div className="sliders">
                        {this.sliders}
                      </div>
                    </div>
                    <div className="item" onClick={this.setEnd}>
                      <h3>Péripétie Arrivée</h3>
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                  </div>
    						</div>
    					</div>
              <Dialog id="startDialog" ref='startDialog' className="editDialog"/>
              <Dialog id="endDialog" ref='endDialog' className="editDialog"/>
            </div>

    }
});
