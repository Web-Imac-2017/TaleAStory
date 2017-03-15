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
      return <div className="list">
              {steps}
             </div>
  }

}

class ItemNav extends React.Component{
  constructor(props){
    super(props);
    this.resizeHandle = this.resizeHandle.bind(this);
    this.next = this.next.bind(this);
    this.previous = this.previous.bind(this);
    this.searchHandler = this.searchHandler.bind(this);


    let offset = 0;
    if(this.props.id){
      offset = Math.round(this.props.id * 0.1) * 10;
    }

    this.state = {
      selectedItem:null,
      offset:offset,
      count:this.stepCount(),
      search:''
    };

    if(this.props.loadSteps){
      this.steps = this.props.loadSteps(this.state.offset, this.state.count);
    }
    else {
      this.steps = [];
    }

  }

  componentDidMount(){
    this.context.addResizeHandler(this.resizeHandle);
  }
  componentWillUnmount(){
    this.context.removeResizeHandler(this.resizeHandle);
  }
  shouldComponentUpdate(nextProps, nextState){
    if(!this.props.loadSteps)
      return true;
    if(this.state.offset != nextState.offset || this.state.count != nextState.count)
      this.steps = this.props.loadSteps(nextState.offset, nextState.count);
    return true;
  }

  stepCount(){
    let _return = 9;
    if(window.innerHeight < 600)
      _return -= 3;
    if(window.innerWidth < 700)
      _return -= Math.round(_return * 0.333);
    return _return;
  }

  resizeHandle(){
    this.setState({count: this.stepCount()});
  }

  getChildContext() {
    let that = this;
    return {
      setSelectedItem : function(selectedItem){
        if(that.state.selectedItem)
          ReactDOM.findDOMNode(that.state.selectedItem).classList.remove('selected');
        if(selectedItem)
          ReactDOM.findDOMNode(selectedItem).classList.add('selected');
        that.setState({selectedItem:selectedItem});
        if(that.props.selectHandler){
          that.props.selectHandler(selectedItem);
        }
      }
    };
  }

  next(){
    this.setState((prevState) => ({offset: prevState.offset+prevState.count}));
  }

  previous(){
    this.setState((prevState) => ({offset: Math.max(prevState.offset-prevState.count,0)}));
  }

  searchHandler(){
    this.setState({search: this.refs.search});
  }

  render(){
    this.children = <List steps={this.steps} key={this.state.offset}/>
    return <div className="listNav">
              <div>
                <form className="element">
                    <input name="search" type="text" placeholder="Recherche"
                            ref='search'
                            value={this.state.search} onChange={this.searchHandler}/>
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
  }
}

ItemNav.contextTypes = {
                      user : AppContextTypes.user,
                      addResizeHandler : AppContextTypes.addResizeHandler,
                      removeResizeHandler : AppContextTypes.removeResizeHandler
                     }
ItemNav.childContextTypes =
 List.contextTypes =
 ListItem.contextTypes = {
   setSelectedItem : React.PropTypes.func
 };
export default ItemNav;
