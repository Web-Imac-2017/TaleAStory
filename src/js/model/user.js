import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class User{
  constructor(id, pseudo, imgpath, isAdmin = false){
    this.id = id;
    this.pseudo = pseudo;
    this.imgpath = imgpath;
    this.isAdmin = isAdmin;
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
    let id = GlobalBack.get('userID');
    if(id){
      _currentuser = new User(id, GlobalBack.get('userPseudo'),
                                  GlobalBack.get('userImgPath'),
                                 GlobalBack.get('isAdmin'));
      return new Promise((resolve, reject) =>
                        {
                            resolve(_currentuser);
                        });
    }
    return fetch(config.path('currentuser'), {
                        method: 'get',
                        headers: {
                            'Accept': 'application/json'
                        }
                      }
          ).then(function(response){
            return _currentuser;
            //return response.json();
          }).then(function(json){
            _currentuser = json;
            return _currentuser;
          });
  }
  else{
    return updateCurrentUser();
  }
}

function updateCurrentUser(){
  if(_currentuser == null)
    return currentUser();
  return fetch(config.path('currentuser'), {
                      method: 'get',
                      headers: {
                          'Accept': 'application/json'
                      }
                    }
        ).then(function(response){
          return _currentuser;
          //return response.json()
        }).then(function(json){
          _currentuser = new User(json.id, json.pseudo, json.imgpath);
          return _currentuser;
        });
}

export {User, LoggedUser, Guest, currentUser, updateCurrentUser};
export default User;
