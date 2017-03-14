import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import ReactDOM from 'react-dom';
import {Achievement} from '../model/achievement'
import {Item} from '../model/item'
import {Step} from '../model/step'

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

  static requestError(error) {

  }

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
            }, Requester.requestError
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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

    static signOut(){
      makeGuest();
    }  

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
              }, Requester.requestError
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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

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
              }, Requester.requestError
            ).then(
              function(json){
                let obj = JSON.parse(json.message);
                let items = [];
                for (let i=0; i<obj.length; i++) {
                    let it = new Item( obj[i].IDItem, obj[i].Name, obj[i].ImgPath, obj[i].Brief );
                    items[i] = it;
                }
                return items;
            });
    } 

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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

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
              }, Requester.requestError
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
              }, Requester.requestError
            ).then(
              function(json){
                let obj = JSON.parse(json.message);
                let achievements = [];
                for (let i=0; i<obj.length; i++) {
                    let ach = new Achievement( obj[i].IDPlayer, obj[i].IDAchievement, obj[i].isRead );
                    achievements[i] = ach;
                }
                return achievements;
            });
    } 

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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 


    /* Renvoie une liste de 'count' steps, à partir de la step 'start'.
    * sinon count n'est pas précisé, il est fixé à 10
    */
    /*
    static stepList(start, count){
      let url = config.path('step/list/' + start);
      if(typeof count !== "undefined") {
        url += '/' + count;
      } else {
        url += '/' + 10;
      }
      return fetch(url, {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    }
    */

    /* Renvoie une liste de 'count' steps, à partir de la step 'start'.
    * sinon count n'est pas précisé, il est fixé à 10
    */
    static stepList(start, count){
      let url = config.path('step/list/' + start);
      if(typeof count !== "undefined") {
        url += '/' + count;
      } else {
        url += '/' + 10;
      }
      return fetch(url, {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                let obj = JSON.parse(json.message);
                let steps = [];
                for (let i=0; i<obj.length; i++) {
                    let step = new Step( obj[i].IDStep, obj[i].ImgPath, obj[i].Body, obj[i].Question, obj[i].IDType, obj[i].Title );
                    steps[i] = step;
                }
                return steps;
            });
    }


/*
    fetch(config.path('/step/list?count=2&start=1'), {
                    method: 'post',
                    headers: {
                      'Content-Type' : 'application/json'
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({
                      "nameFilter" : "JeNeSuisPasUnTitre",
                    })
                  }
*/
    // OK mais si dans pastStep le couple existe déjà, hard crash
    /* 
    * _answer est le text de la réponse
    */
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
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    }

    // En voie d'obsolecence
    /*
    static stepAdd(body, question, idType, title){
      return fetch(config.path('addstep'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  Body: body,
                  Question: question,
                  IDType: idType,
                  Title: title
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    }*/


    // A tester avec un form - il semble que le php n'attend pas un 'form' ?
    static addStep(form){
      return fetch(config.path('addstep'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

  static deleteChoice(_IDChoice){
      return fetch(config.path('deletechoice'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  IDChoice: _IDChoice
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

    // A tester avec un form
    static updateChoice(form){
      return fetch(config.path('updatechoice'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

  // hard crash
  static deleteStep(_idstep){
      return fetch(config.path('deletestep'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  idstep: _idstep
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    }

    // A tester avec un form
    static updateStep(form){
      return fetch(config.path('updatestep'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

    static stepListByTitle(_nameFilter){
      return fetch(config.path('step/list'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  nameFilter : _nameFilter
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                let obj = JSON.parse(json.message);
                let steps = [];
                for (let i=0; i<obj.length; i++) {
                    let step = new Step( obj[i].IDStep, obj[i].ImgPath, obj[i].Body, obj[i].Question, obj[i].IDType, obj[i].Title );
                    steps[i] = step;
                }
                return steps;
            });
    }

    // A tester avec un form - Pas fini côté php
    static addItem(form){
      return fetch(config.path('additem'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

    // A tester avec un form
    static updateItem(form){
      return fetch(config.path('updateitem'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

    // ok mais renvoie que l'item a été supprimé (sans erreur) même si l'item n'existait pas
  static deleteItem(_IDItem){
      return fetch(config.path('deleteitem'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  IDItem: _IDItem
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

    // A tester avec un form - il semble que le php n'attend pas un 'form' ?
    static addAchievement(form){
      return fetch(config.path('addachievement'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

  static deleteAchievement(_IDAchievement){
      return fetch(config.path('deleteachievement'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  IDAchievement: _IDAchievement
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    }

    // A tester avec un form - Pas fini côté php
    static updateAchievement(form){
      return fetch(config.path('updateachievement'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

    // hard crash
    static signOut(){
      return fetch(config.path('signout/'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin"
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

  static deletePlayer(_IDPlayer){
      return fetch(config.path('deleteplayer'), {
                method: 'post',
                headers: {
                  'Content-Type' : 'application/json'
                 },
                credentials: "same-origin",
                body: JSON.stringify({
                  IDPlayer: _IDPlayer
                })
              }
            ).then(
              function(response){
                return response.json();
              }, Requester.requestError
            ).then(
              function(json){
                return json;
            });
    } 

    // A tester avec un form
    static updatePlayer(form){
      return fetch(config.path('updateplayer'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
    }

    // A tester avec un form
    static addChoice(form){
      return fetch(config.path('addchoice'), {
        method: 'POST',
        body: new FormData(form)
      }).then(
        function(response){
          return response.json();
        }, Requester.requestError
      ).then(
        function(json){
          return json;
          //this.context.router.push(config.path('profils/admin/steps/' + json.result.id));
       }
      );
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


}

document.requester = Requester;
document._requesterValues = {};
export {Requester};