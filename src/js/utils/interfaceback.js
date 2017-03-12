import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import ReactDOM from 'react-dom';

class GlobalBack{
  static get(field){
    if(typeof document._globalBackValues == "undefined")
      return null;
    if(!(field in document._globalBackValues))
      return null;
    return document._globalBackValues[field];
  }
  static set(field, value){
     document._globalBackValues[field] = value;
  }
  static setObject(fields){
    for (let key in fields){
      if (fields.hasOwnProperty(key)) {
         GlobalBack.set(key, fields[key]);
       }
    }
  }
}
document.globalBack = GlobalBack;
document._globalBackValues = {};
export {GlobalBack};


class Requester {

  static currentUser(){
    return fetch(config.path('currentuser/getcurrentuser'), {
              method: 'post',
              headers: {
                'Content-Type' : 'application/json'
               },
              credentials: "same-origin"
            }
          ).then(
            function(response){
              return response.json();
            }
          ).then(
            function(json){
              return json;
          });
  }  

  static signIn(log, pass){
      return fetch(config.path('signin'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  login: log,
                  pwd: pass
                })
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // message d'error OK si mauvais mail, sinon hard crash
  static signUp(_pseudo, _login, _mail, _pwd){
      return fetch(config.path('signup'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  pseudo: _pseudo,
                  login: _login,
                  mail: _mail,
                  pwd: _pwd
                })
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!! appel à makeGuest, qui ne fonctionne pas
    static signOut(){
      makeGuest();
    }  

    // !!!!!!!!! buguée
    static makeGuest(){
      return fetch(config.path('makeguest'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!! message: "null" code:0 status:"ok"
    static currentUserStats(){
      return fetch(config.path('currentuser/stats/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!! message: "null" code:0 status:"ok"
    static currentUserItems(){
      return fetch(config.path('currentuser/items/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!! semble ok
    static currentUserStep(){
      return fetch(config.path('currentuser/currentstep/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!! message: "null" code:0 status:"ok"
    static currentUserStory(){
      return fetch(config.path('currentuser/story/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!!! hard error
    static currentUserAchievements(){
      return fetch(config.path('currentuser/achievements/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // !!!!!!!!!! hard error
    static currentUserUnreadAchievements(){
      return fetch(config.path('currentuser/unreadachievements/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // Réponse vide ?
    static stepCount(){
      return fetch(config.path('step/count/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 


    // A tester
    static stepList(start, count){
      return fetch(config.path('step/list/'+start+'/'+count+'/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    }

    // A tester - hard error
    static stepList(start){
      return fetch(config.path('step/list/'+start+'/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    } 

    // hard error
    static currentStepResponse(_answer){
      return fetch(config.path('currentstep/response'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  answer: _answer
                })
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    }

    // A tester
    static stepAdd(body, question, answer){
      return fetch(config.path('addstep'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  Body: body,
                  Question: question,
                  IDType: answer
                })
              }
            ).then(
              function(response){
                return response.json();
              }
            ).then(
              function(json){
                return json;
            });
    }

    static test(that){ /* == ancien 'componentDidMount' )*/
      fetch(config.path('connexion'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  yolo : "bonjour",
                  lol : 5
                })
              }
            ).then(
              function(response){
                return response.json();
              },
              function(error) {
                that.setState({ text : error.message});
              }
            ).then(
              function(json){
                let dom = ReactDOM.findDOMNode(that);
                dom.innerHTML = JSON.stringify(json);
                that.setState({ text : JSON.stringify(json)});
                console.log(json);
            });
      }  

    static test2(){
      fetch(config.path('connexion'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  yolo : "bonjour",
                  lol : 5
                })
              }
            ).then(
              function(response){
                console.log(response);
                response = JSON.stringify({
                  yolo : "bonjour",
                  lol : 5
                });
                return response;
              }
            ).then(
              function(json){
                console.log(json);
            });
      }

    static test3(){
      return fetch(config.path('connexion'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                console.log(response);
                return response.json();
              }
            ).then(
              function(json){
                console.log(json);
                return json;
            });
    }  


}

document.requester = Requester;
document._requesterValues = {};
export {Requester};