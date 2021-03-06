import React from 'react';
import ReactDOM from 'react-dom';
import EditList from './editlist';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Requester} from '../utils/interfaceback'

let StepsList = RouteComponent(Object.assign({}, EditList, {
  loadSteps(offset, count, filter){
    return Requester.choiceList(offset, count, filter);
  },
  editItem(item){
    this.context.router.push(config.path('edit/choice/'+item.id));
  },
  removeItem(item){
  },
  addItem(){
    this.context.router.push(config.path('edit/choice'));
  }

}));

export default StepsList;
