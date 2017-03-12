import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config';
import {AppContextTypes} from './app'
import RouteComponent from '../utils/routecomponent';
import TransitionGroup from 'react-addons-transition-group';

class ListItem extends React.Component{
  constructor(props){
    super(props);
    this.handleClick = this.handleClick.bind(this);
    this.id = this.props.id;
  }

  handleClick(e){
    this.context.setSelectedItem(this);
    e.stopPropagation();
  }

  render(){
    return <div className="item" onClick={this.handleClick}>
              <div className="insideItem">
                <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                <img className="element" src={config.imagePath('wave_large.png')}/>
                <h3 className="userName">Un trophée</h3>
              </div>
            </div>
  }
}

class List extends React.Component{

  constructor(props){
    super(props);
  }

  componentWillEnter(callback){
    let dom = ReactDOM.findDOMNode(this);
    TweenLite.fromTo(dom, 1, {scale:0, opacity:0}, {scale:1, opacity:1})
             .eventCallback("onComplete", () => { callback();});
  }

  componentWillAppear(callback){
    this.componentWillEnter(callback);
  }

  componentWillLeave(callback){
    let dom = ReactDOM.findDOMNode(this);
    TweenLite.fromTo(dom, 1, {scale:1, opacity:1}, {scale:0, opacity:0})
             .eventCallback("onComplete", () => { callback();});
  }

  render(){
      let steps = this.props.steps ?
                    this.props.steps.map((step, index) =>
                      <ListItem key={step} id={step}/>
                    ) : null;
      console.log(this.props.steps);
      return <div className="list">
              {steps}
             </div>
  }

}

let StepsList = RouteComponent({
  contextTypes : { user : AppContextTypes.user},
  getInitialState(){
    this.lastoffset = -1;
    return {
              selectedItem:null,
              offset:0,
              count:9,
              steps:[1,2,3,4,5,6,7],
              search:''
           };
  },
  getChildContext() {
    let that = this;
    return {
      setSelectedItem : function(selectedItem){
        if(that.state.selectedItem)
            ReactDOM.findDOMNode(that.state.selectedItem).classList.remove('selected');
        if(selectedItem)
          ReactDOM.findDOMNode(selectedItem).classList.add('selected');
        that.setState({selectedItem:selectedItem});
      }
    };
  },
  unselect(e,p){
    if(this.state.selectedItem){
        ReactDOM.findDOMNode(this.state.selectedItem).classList.remove('selected');
        this.setState({selectedItem:null});
    }

  },
  edit(e){
    if(this.state.selectedItem){
      this.context.router.push(config.path('edit/step/'+this.state.selectedItem.id));
      e.stopPropagation();
    }
  },
  remove(e){
    if(this.state.selectedItem){
      e.stopPropagation();
    }
  },
  add(e){
    e.stopPropagation();
    this.context.router.push(config.path('edit/step'));
  },

  next(){
    this.setState((prevState) => {
        let next = prevState.offset+prevState.count;
        let steps = [];
        for(let i=next; i<next + prevState.count; i++)
          steps.push(i);
        return {offset: next, steps:steps};
    });
  },

  previous(){
    this.setState((prevState) => {offset: Math.max(prevState.offset-prevState.count,0)});
  },

  handleChange(event) {
		this.state[name] = event.target.search.value;
	},
  handleSubmit(event) {
		event.preventDefault();
	},
  render(){
    this.children = <List steps={this.state.steps} key={this.state.offset}/>
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

    return  <div onClick={this.unselect}>
              <div className="columnsContainer">
                <div className="content contentProfil contentSteps">
                  <div className="contentRight">
                    <div className="listNav">
                      <div>
                        <form className="element" onSubmit={this.handleSubmit}>
                            <input name="search" type="text" placeholder="Recherche"
                                        value={this.state.search} onChange={this.handleChange} ref="username" />
                            <button className="submit"/>
          							</form>
                      </div>
                      <TransitionGroup>
                        {this.children}
                      </TransitionGroup>
                      <div className="buttons">
                        <button className="button" onClick={this.previous}>&lt;</button>
                        <button className="button" onClick={this.next}>&gt;</button>
                      </div>
                    </div>
                    <div className="options">
                      <div className={"item" + (this.state.selectedItem ? "" : " disable")} onClick={this.edit}>
                        <div className="insideItem">
                          <img className="picto element" src={config.imagePath('editProfil.svg')}/>
                          <h3 className="userName">Éditer</h3>
                        </div>
                      </div>
                      <div className={"item" + (this.state.selectedItem ? "" : " disable")} onClick={this.remove}>
                        <div className="insideItem">
                          <img className="picto element" src={config.imagePath('remove.svg')}/>
                          <h3 className="userName">Supprimer</h3>
                        </div>
                      </div>
                      <div className="item" onClick={this.add}>
                        <div className="insideItem">
                          <img className="picto element" src={config.imagePath('add.svg')}/>
                          <h3 className="userName">Créer</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  }
});

StepsList.childContextTypes =
  List.contextTypes =
  ListItem.contextTypes = {
    setSelectedItem : React.PropTypes.func
  };

export default StepsList;
