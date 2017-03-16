import React from 'react';
import ReactDOM from 'react-dom';
import EditList from './editlist';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Requester} from '../utils/interfaceback'

let StepsList = RouteComponent(Object.assign({}, EditList, {
  loadSteps(offset, count, filter){
    return Requester.itemList(offset, count, filter);
  },
  editItem(item){
    this.context.router.push(config.path('edit/item/'+item.id));
  },
  removeItem(item){
  },
  addItem(){
    this.context.router.push(config.path('edit/item'));
  }

}));

export default StepsList;
