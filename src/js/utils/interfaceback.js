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

  // !!!!!!!!! appel à makeGuest, qui ne fonctionne pas
  static signOut(){
    makeGuest();
  }  

  // !!!!!!!!! fonction php makeguest semble buguée
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