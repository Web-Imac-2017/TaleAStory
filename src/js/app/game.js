import React from 'react';
import TweenLite from '../greenshock/TweenMax.js';
import ReactDOM from 'react-dom'
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import TransitionGroup from 'react-addons-transition-group';
import {ProgressBar} from 'react-bootstrap';
import webGL from '../webgl/webgl.js';

class GameComponent extends React.Component{
  componentWillEnter(callback){
	if(webGL.bg_anim != null){
		webGL.bg_anim.move(0,0,1);
    }
    let dom = ReactDOM.findDOMNode(this);
    let that = this;
    this.animation = TweenLite.fromTo(dom, 1.3,{opacity:0, scale:0},{opacity:1,scale:1})
                              .eventCallback("onComplete", callback);
  }


  componentWillAppear(callback){
    this.componentWillEnter(callback);
  }
  componentWillLeave(callback){
    let dom = ReactDOM.findDOMNode(this);
    let that = this;
    this.animation = TweenLite.fromTo(dom, 0.7,{opacity:1, scale:1},{opacity:0, scale:2})
                              .eventCallback("onComplete", callback);
  }
  render(){
    return <div className="game-component"></div>
  }
}

class Decision extends GameComponent{
  constructor(props){
    super(props);
    this.state = {
      current : 0
    }
    this.childs = null;
    this.nextText = this.nextText.bind(this);
    this.handleSkip = this.handleSkip.bind(this);
    this.selectAnswer = this.selectAnswer.bind(this);
  }
  componentWillMount(){
      setTimeout((that) => {that.nextText()}, 1700, this);
  }
  nextText(){

    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-text');
    if(this.state.current >= this.childs.length){
      dom.getElementsByClassName('skip')[0].style = "display:none";
      let childs = dom.getElementsByTagName('button');
      for(let i = 0; i<childs.length; i++){
        TweenLite.fromTo(childs[i], 1, {opacity:0},{opacity:1});
      }
    }
    else{


      this.animation = TweenLite.fromTo(this.childs[this.state.current], 2,
                                        {opacity:0},{opacity:1})
                                .eventCallback("onComplete",this.nextText);

      this.setState((prevState, props) => ({
        current: prevState.current + 1
      }));

    }
  }

  handleSkip(){
    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-text');
    this.state.current = this.childs.length;
    this.animation.kill();
    for(let i=0; i<this.childs.length;i++){
      this.childs[i].style = "opacity: 1";
    }

    setTimeout(this.nextText, 10);
  }

  selectAnswer(nextStep){
    if(this.props.callback)
      this.props.callback(nextStep);
  }

  render(){
    const texts = this.props.children ?
                    this.props.children.map((text, index) => {
                      if(text.props.className == "text")
                      {
                        return <p key={index} className="story-text">
                                { text.props.children }
                              </p>
                      }
                    }) : null;
    const question = this.props.children ?
                    this.props.children.map((text, index) => {
                      if(text.props.className == "question")
                      {
                        return <p key={index} className="story-text">
                              {text.props.children}
                              </p>
                      }
                    }) : null;
    const answers = this.props.children ?
                    this.props.children.map((text, index) => {
                      if(text.props.className == "answer")
                      {
                        return <button key={index} className="button story-answer"
                                      onClick={() => { this.selectAnswer(text.props.onClick());}}>
                                    { text.props.children }
                              </button>
                      }
                    }) : null;
    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>
              {texts}
              {question}
              {answers}
              <button id="skip" className="button small skip" onClick={this.handleSkip}>
                Skip
              </button>
            </div>
          </div>
  }
}

class StoryComponent extends GameComponent{
  constructor(props){
    super(props);
    this.state = {
      current : 0
    }
    this.childs = null;
    this.nextText = this.nextText.bind(this);
    this.handleSkip = this.handleSkip.bind(this);
    this.handleNext = this.handleNext.bind(this);
  }

  componentDidMount(){
      setTimeout(this.nextText, 1000);
  }

  nextText(){
    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-text');
    if(this.state.current >= this.childs.length){
      dom.getElementsByClassName('button')[0].style = "display:none";
      dom.getElementsByClassName('button')[1].style = "";
    }
    else{
		if(webGL.bg_anim != null){
			webGL.bg_anim.setLandscape(true);
		 }
      this.animation = TweenLite.fromTo(this.childs[this.state.current], 2,
                                        {opacity:0},{opacity:1})
                                .eventCallback("onComplete",this.nextText);
      this.setState((prevState, props) => ({
        current: prevState.current + 1
      }));
    }
  }

  handleSkip(){
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-text');
    this.state.current = this.childs.length;
    this.animation.kill();
    for(let i=0; i<this.childs.length; i++)
      this.childs[i].style = "opacity: 1";
    setTimeout(this.nextText, 10);
  }
  handleNext(){
    if(this.props.callback)
      this.props.callback();
  }


  render(){
    const texts = this.props.children ?
                    this.props.children.map((text, index) =>
                      <p key={index} className="story-text" {...text.props}>
                        {text.props.children}
                      </p>
                    ) : null;
    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>
               {texts}
               <button id="skip" className="button small" onClick={this.handleSkip}>
                 Skip
               </button>
               <button id="next" className="button small" style={{display:"none"}} onClick={this.handleNext}>
                 Next
               </button>
             </div>
          </div>
  }
}

export default RouteComponent({
  getInitialState(){
	  // webGL.bg_anim.setLandscape(true);
	  if(webGL.bg_anim != null){
		    webGL.bg_anim.unMuteAll();
	  }
	  TweenLite.fromTo(document.getElementById('analyser'), 1.3,{opacity:0},{opacity:1});

    return {
      currentStats : [
        {label: 'Fatigue', value: 100},
        {label: 'Force', value: 10},
        {label: 'Faim', value: 50}
      ],
      currentStep : null
    };
  },
  componentWillMount(){
    //get the first step
	if(webGL.bg_anim != null){
		webGL.bg_anim.unMuteAll();
		document.getElementById('analyser').style = 'width:3%';
		TweenLite.fromTo(document.getElementById('analyser'), 1.3,{opacity:0},{opacity:1});
		var links = document.getElementsByTagName("a");
		var color;
    color = webGL.bg_anim.getColor();
		for(var i=0;i<links.length;i++)
		{
			if(links[i].href)
			{
				TweenLite.to(links[i], 0.5,{color:"rgb("+Math.floor(255*color[0]+30)+","+Math.floor(255*color[1]+30)+","+Math.floor(255*color[2]+30)+")"});
			}
		}


		// webGL.bg_anim.getColor();

	}
    let component = (<StoryComponent callback={this.nextStep}>
                        <p>
                          Bienvenue dans TaleAstory, vous allez bientôt écrire une histoire,
                          votre histoire... Vous allez être confronté à des décisions,
                          cliquez simplement sur l'action qui vous aimeriez effectuer pour
                          passer à l'étape suivante. Vous serez aussi amené à résoudre des
                          enigmes, resolvez-les pour vous débloquer d'une situation.
                        </p>
                        <p>
                          Bien... il est temps pour vous de commencer votre journée...
                        </p>
                      </StoryComponent>);
    this.setState({currentStep : component, currentStepID : 1});
  },



  componentWillUnmount(){
	  if(webGL.bg_anim != null){
		  webGL.bg_anim.setLandscape(false);
		  webGL.bg_anim.mute();
		  TweenLite.fromTo(document.getElementById('analyser'), 1.3,{opacity:1},{opacity:0}).eventCallback("onComplete", function(){
				document.getElementById('analyser').style= 'width:0%';
		  });

	  }
  },
  nextStep(){
    //get the next step
    let component = (<Decision callback={this.nextTransition}>
                        <p className="text">
                          Mes yeux sont fermés. Je reste là, couché.
                           J’aimerais qu’il soit plus tard, avoir plus de temps pour me reposer.
                           Mais je sens les rayons du soleil contre ma peau.
                        </p>
                        <p className="question">
                          Le vent se lève...
                        </p>
                        <p className="answer" onClick={() => 1}>
                          Encore quelques minutes
                        </p>
                        <p className="answer" onClick={() => 2}>
                          J’ouvre les yeux
                        </p>
                      </Decision>);
    this.setState({currentStep : component, currentStepID : 2, currentStats : [
      {label: 'Fatigue', value: 50},
      {label: 'Force', value: 30},
      {label: 'Faim', value: 50}
    ]});
  },
  nextTransition(nextStep){
    //get the transition text
    let component;
    if(nextStep == 1){
      let component = (<Decision callback={this.nextTransition}>
                          <p className="text">
                            Mes yeux sont fermés. Je reste là, couché.
                             J’aimerais qu’il soit plus tard, avoir plus de temps pour me reposer.
                             Mais je sens les rayons du soleil contre ma peau.
                          </p>
                          <p className="question">
                            Le vent se lève...
                          </p>
                          <p className="answer" onClick={() => 1}>
                            Encore quelques minutes
                          </p>
                          <p className="answer" onClick={() => 2}>
                            J’ouvre les yeux
                          </p>
                        </Decision>);
      this.setState({currentStep : component, currentStepID : 2.5});
     }
    else{
      let component = (<StoryComponent>
                          <p>
                            Après quelques hésitations, j’ouvre finalement les yeux.
                            C’est une belle journée. Je souris en regardant autour de moi.
                            Je récupère mes affaires et me prépare à partir.
                            Bizarrement, mon arc est resté armé toute la nuit.
                            Je devrais faire plus attention à mes armes…
                            Je le débande et utilise la branche en canne improvisée.
                             Je noue la corde autour de ma taille à l’aide d’un faux noeud.
                             Je pourrais donc m’armer facilement en cas de besoin.
                          </p>
                          <p>
                            Il est temps de partir, le vent se lève. Mon voyage me mène vers l’est, contre le vent.
                            Parfait pour chasser, les proies ne me sentiront pas venir.
                          </p>
                        </StoryComponent>);
      this.setState({currentStep : component, currentStepID : 1});
    }

  },

  render(){
    this.children = this.state.currentStep ?
                      React.cloneElement(this.state.currentStep, {
                        key: this.state.currentStepID
                      }) : null;
    let statsDisplay = this.state.currentStats.map((stat, index) =>
      <span key={stat.label}>
        <h4>{stat.label}</h4>
        <ProgressBar now={stat.value}/>
      </span>
    )
    return  <div className="game-container">
              <TransitionGroup>
                {this.children}
              </TransitionGroup>
              <div className="stats">
                {statsDisplay}
              </div>
            </div>
  }
});
