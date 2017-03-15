import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack, Requester} from '../utils/interfaceback';


class User{
  constructor(id, mail, pseudo, imgpath, isAdmin = false){
    this.id = id;
    this.pseudo = pseudo;
    this.login = this.mail = mail;
    this.imgpath = imgpath;
    this.isAdmin = isAdmin;
    this.stats = [
      {label: 'fatigue', value: 100},
      {label: 'force', value: 10},
      {label: 'faim', value: 50}
    ];
  }

  updateCurrentStep(){
    let that = this;
    return Requester.currentUserStep().then(function(result){
      that.currentStep = result;
    });
  }
}

class LoggedUser extends User{
  constructor(){
    super();
  }
}

class Guest extends User{
  constructor(){
    super(-1, 'Guest', 'default_tiny.png');
  }
}

let _currentuser = null;
function currentUser(){
  if(_currentuser == null){
    let id = GlobalBack.get('id');
    if(id){
      _currentuser = new User(id, GlobalBack.get('mail'),
                                  GlobalBack.get('pseudo'),
                                  GlobalBack.get('imgpath'),
                                 GlobalBack.get('admin'));
      return new Promise((resolve, reject) =>
                        {
                            resolve(_currentuser);
                        });
    }
    return Requester.currentUser();
  }
  else{
    return updateCurrentUser();
  }
}

function updateCurrentUser(){
  if(_currentuser == null)
    return currentUser();
  return fetch(config.path('currentuser/getcurrentuser'), {
                      method: 'get',
                      headers: {
                          'Accept': 'application/json'
                      }
                    }
        ).then(function(response){
          return _currentuser;
          //return response.json()
        }).then(function(json){
          _currentuser = new User(json.id, json.mail, json.pseudo, json.imgpath);
          return _currentuser;
        });
}

export {User, LoggedUser, Guest, currentUser, updateCurrentUser};
export default User;
