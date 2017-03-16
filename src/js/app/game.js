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
import User from '../model/user';
import Dialog from '../utils/dialog';
import {Requester} from '../utils/interfaceback';

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

	componentDidMount(){
	  if(webGL.bg_anim != null){
  		webGL.bg_anim.unMuteAll();
  		document.getElementById('analyser').style = 'width:3%';
  		TweenLite.fromTo(document.getElementById('analyser'), 1.3,{opacity:0},{opacity:1});

  		// webGL.bg_anim.getColor();

  	}

  }

  makeBody(){
    return (this.props.children ?
                    this.props.children.map((text, index) => {
                      if(!text)
                        return null;
                      if(!text.length){
                        if(text.props.className == "text")
                          return <p key={index} className="story-text story-step">
                                    { text.props.children }
                                  </p>
                      }
                      else if(text[0].props.className == "text")
                      {
                        let childs = text.map(
                          (text2,index2) => (<p key={index*10+index2} className="story-text story-step">
                                              { text2.props.children }
                                            </p>)
                        );
                        return <div key={index}>
                                  {childs}
                               </div>;
                      }
                      return null;
                    }) : null);
  }

  makeAnswers(){
    let that = this;
    return (this.props.children ?
                    this.props.children.map((text, index) => {
                      if(!text)
                        return null;
                      if(!text.length){
                        return null;
                      }
                      if(text[0].props.className == "answer")
                      {
                        let childs = text.map(
                          (text2,index2) => (
                            <button key={index*10+index2} className="button story-answer" style={{display:"none"}}
                                        onClick={() => { that.selectAnswer(text2.props.onClick());}}>
                                      { text2.props.children }
                            </button>)
                        );
                        return <div key={index}>
                                  {childs}
                               </div>;
                      }
                      return null;
                    }) : null);
  }

  makeImages(){
    return this.props.children ?
                this.props.children.map((text, index) =>{
                    if(!text)
                      return null;
                    if(text.length){
                      return null;
                    }
                    if(text.props.className == "img")
                    {
                      return <div key={index}>
                              <img key={index} className="story-img  story-step"
                                  src={config.imagePath(text.props.children)}/>
                             </div>;
                    }
                    return null;
                }) : null;
  }

  makeQuestion(){
    return this.props.children ?
                  this.props.children.map((text, index) => {
                    if(!text)
                      return null;
                    if(text.length)
                      return null;
                    if(text.props.className == "question")
                    {
                      return <p key={index} className="story-text story-step">
                            {text.props.children}
                            </p>
                    }
                  }) : null;
  }

  handleSkip(){
    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-step');
    this.state.current = this.childs.length;
    this.animation.kill();
    for(let i=0; i<this.childs.length;i++){
      this.childs[i].style = "opacity: 1";
    }
    setTimeout(this.nextText, 10);
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


  updateColor(){
	  if(webGL.bg_anim != null){
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

			links = document.getElementsByClassName("progress-bar");
			for(var i=0;i<links.length;i++)
			{
				TweenLite.to(links[i], 0.5,{backgroundColor:"rgb("+Math.floor(255*color[0]+30)+","+Math.floor(255*color[1]+30)+","+Math.floor(255*color[2]+30)+")"});
			}
	  }
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
      this.childs = dom.getElementsByClassName('story-step');
    if(this.state.current >= this.childs.length){

      let childs = dom.getElementsByTagName('button');
      for(let i = 0; i<childs.length; i++){
        TweenLite.fromTo(childs[i], 1, {opacity:0},{opacity:1});
		childs[i].style="";
      }
	  dom.getElementsByClassName('skip')[0].style = "display:none";
    }
    else{
		if(this.state.current==0){
			this.updateColor();
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
    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null)
      this.childs = dom.getElementsByClassName('story-step');
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
    const texts = this.makeBody();
    const question = this.makeQuestion();
    const answers = this.makeAnswers();

	  const imag = this.makeImages();
    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>

			<div className="decision">
				<div className="story-left">
					{imag}
				</div>
				<div className="story-right">
					{texts}
					{question}
				</div>
			</div>
			<div className="story-bottom" >
				<button id="skip" className="button small skip" onClick={this.handleSkip}>
					Passer
				  </button>
				   {answers}

			</div>




            </div>
          </div>
  }
}














class EnigmaComponent extends GameComponent{
  constructor(props){
    super(props);
    this.state = {
      current : 0,
      answer : '',
    }
    this.childs = null;
    this.nextText = this.nextText.bind(this);
    this.handleSkip = this.handleSkip.bind(this);
    this.handleChange = this.handleChange.bind(this);
    this.handleResponse = this.handleResponse.bind(this);
  }

  componentDidMount(){
      setTimeout(this.nextText, 1000);
  }

  nextText(){
    let dom = ReactDOM.findDOMNode(this);
    if(this.childs == null){
      this.childs = dom.getElementsByClassName('story-step');
	}
    if(this.state.current >= this.childs.length){
      dom.getElementsByClassName('button')[0].style = "display:none";
      dom.getElementsByClassName('form')[0].style = "";
    }
    else{
		if(this.state.current==0){
			this.updateColor();
		}
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

  handleResponse(event){
    event.preventDefault();
    if(this.props.callback)
      this.props.callback({answer : this.state.answer});
    return false;
  }

  handleChange(){
    this.setState({answer : this.refs.answer.value});
  }

  render(){
  const texts = this.makeBody();
	const imag = this.makeImages();
  const question = this.makeQuestion();

    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>
			<div className="enigma">
				<div className="story-left">
					{imag}
				</div>
				<div className="story-right">
					{texts}
          {question}
				</div>
			</div>
			<div className="story-bottom" >
				   <button id="skip" className="button small" onClick={this.handleSkip}>
					 Passer
				   </button>
				   <form className="form story-form" style={{display:"none"}} onSubmit={this.handleResponse}>
					<span>
						<input type="text" name="answer" placeholder="Votre réponse" onChange={this.handleChange}
                    value={this.state.answer} ref='answer'/>
					</span>
					<span className="submit">
						<input type="submit" value="Entrer"/>
					</span>
				   </form>
			</div>
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
	  //this.updateColor();
    }
    else{
		if(this.state.current==1){
			this.updateColor();
		}
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
    const texts = this.makeBody();
    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>
               {texts}
               <button id="skip" className="button small" onClick={this.handleSkip}>
                 Passer
               </button>
               <button id="next" className="button small" style={{display:"none"}} onClick={this.handleNext}>
                 Suivant
               </button>
             </div>
          </div>
  }
}

class EndComponent extends GameComponent{
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
	  //this.updateColor();
    }
    else{
		if(this.state.current==1){
			this.updateColor();
		}
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
    this.context.router.push(config.path(''));
  }


  render(){
    const texts = this.makeBody();
    let superDom = super.render();
    return <div className="game-wrapper">
            <div {...superDom.props}>
               {texts}
               <button id="skip" className="button small" onClick={this.handleSkip}>
                 Passer
               </button>
               <button id="next" className="button small" style={{display:"none"}} onClick={this.handleNext}>
                 Accueil
               </button>
             </div>
          </div>
  }
}

EndComponent.contextTypes = {
  router : React.PropTypes.object
};


export default RouteComponent({
  contextTypes : {user: React.PropTypes.objectOf(User), dialog : React.PropTypes.func},
  getInitialState(){
	  // webGL.bg_anim.setLandscape(true);
	  if(webGL.bg_anim != null){
		    webGL.bg_anim.unMuteAll();
	  }

	  TweenLite.fromTo(document.getElementById('analyser'), 1.3,{opacity:0},{opacity:1});
    return {
      currentStats : [],
      currentStep : null,
      currentStepID : -1,
      currentType : -1
    };
  },
  updateStats(){
    let that = this;
    Requester.currentUserStats().then(function(result){
      let stats = JSON.parse(result.message);
      let currentStats = [];
      Object.keys(stats).map(function(key, index) {
         currentStats.push({label:key, value:stats[key]});
      });
      that.setState({currentStats:currentStats});
    });
  },
  start(){
    let that = this;
    Requester.currentUserStart().then(function(){
      that.update();
    });
  },
  updateStep(){
    let that = this;
    Requester.currentUserStep().then(function(result){
      let component;
      let currentStep;


      if(!result){
        let childs = [
            "Bienvenue dans TaleAstory, vous allez bientôt écrire une histoire, \
            votre histoire... Vous allez être confronté à des décisions, \
            cliquez simplement sur l'action qui vous aimeriez effectuer pour \
            passer à l'étape suivante. Vous serez aussi amené à résoudre des \
            enigmes, resolvez-les pour vous débloquer d'une situation."
          ,
          "Bien... il est temps pour vous de commencer votre journée..."
        ];
        childs = childs.map((text,index)=> (<p className="text" key={index}>
                                            {text}
                                          </p>)
        );
        component = (<StoryComponent callback={that.start}>
                        {childs}
                      </StoryComponent>);
        currentStep = 0;
        that.setState({currentStep : component, currentStepID : currentStep});
      }
      else{
        if(result.IDType == 4){
          let step = result;
          Requester.currentStepAnswers().then(function(result){
            let answers = JSON.parse(result.message)[0];
            let texts = step.Body.split('\n').map((text, index) => {
              return <p className="text" key={index}>
                      {text}
                    </p>
            });
            if(answers != null){
              answers = answers.map(
                (text, index)=> (<p className="answer" key={text.IDChoice}
                                    onClick={()=>{ return {
                                                            'id': text.IDNextStep,
                                                           'text':text.TransitionText,
                                                           'answer':text.Answer
                                                         };}}>
                                  {text.Answer}
                                </p>)
              );
            }
            let image = step.ImgPath ?
                        <p className="img">
                          {step.ImgPath}
                        </p> : null;
            let question = step.Question ?
                          <p className="question">
                            {step.Question}
                          </p> : null;
            component = (<Decision callback={that.nextTransition}>
                                {texts}
                                {question}
                                {image}
                                {answers}
                              </Decision>);
            currentStep = step.IDStep;
            that.setState({currentStep : component, currentStepID : currentStep, currentType : step.IDType});

          });
        }
        else if(result.IDType == 2){
          let step = result;
          let texts = step.Body.split('\n').map((text, index) => {
            return <p className="text" key={index}>
                    {text}
                  </p>
          });
          let image = step.ImgPath ?
                      <p className="img">
                        {step.ImgPath}
                      </p> : null;
          let question = step.Question ?
                        <p className="question">
                          {step.Question}
                        </p> : null;
          component = (<EnigmaComponent callback={that.nextTransition}>
                          {texts}
                          {question}
                          {image}
                      </EnigmaComponent>);
          currentStep = step.IDStep;
          that.setState({currentStep : component, currentStepID : currentStep, currentType : step.IDType});
        }
        else if(result.IDType == 7){
          let step = result;
          let texts = step.Body.split('\n').map((text, index) => {
            return <p className="text" key={index}>
                    {text}
                  </p>
          });
          let image = step.ImgPath ?
                      <p className="img">
                        {step.ImgPath}
                      </p> : null;
          component = (<EndComponent callback={that.nextTransition}>
                          {texts}
                          {image}
                      </EndComponent>);
          currentStep = step.IDStep;
          that.setState({currentStep : component, currentStepID : currentStep, currentType : step.IDType});
        }
      }
    });
  },
  update(){
    this.updateStats();
    this.updateStep();
  },
  componentDidMount(){
      this.update();
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
  nextTransition(nextStep){
    let that = this;
    Requester.currentStepResponse(nextStep.answer).then(function(result){
      if(result.status == "ok"){
        result=JSON.parse(result.message);
        let texts = result.text.split('\n').map((text, index) => {
          return <p className="text" key={index}>
                  {text}
                </p>
        });
        let component = <StoryComponent callback={that.update}>
                          {texts}
                        </StoryComponent>;
        that.setState({currentStep : component, currentStepID : result.id-0.5});
      }
      else{
        result=JSON.parse(result.message);
        that.context.dialog({
          title: result.title,
          body: result.body,
          actions: [
            Dialog.Action(
              'Ok',
              () => {},
              'button btn-confirm'
            )
          ],
          bsSize: 'medium',
          onHide: (dialog) => {
            dialog.hide();
          }
        });
      }
    });

  },

  render(){
    this.children = this.state.currentStep ?
                      React.cloneElement(this.state.currentStep, {
                        key: this.state.currentStepID
                      }) : null;
    let height = (100 / this.state.currentStats.length) + '%';
    let statsDisplay = this.state.currentStats.map((stat, index) =>
      <span key={stat.label} style={{'height':height}}>
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
