import React from 'react';
import ReactDOM from 'react-dom';
import EditList from './editlist';
import config from '../config';
import RouteComponent from '../utils/routecomponent';

let StepsList = RouteComponent(Object.assign({}, EditList, {
  loadSteps(offset, count){
    let steps = [];
    for(let i=offset; i<offset + count; i++)
      steps.push(i);
    return steps;
  },
  editItem(item){
    this.context.router.push(config.path('edit/step/'+item.id));
  },
  removeItem(item){
  },
  addItem(){
    this.context.router.push(config.path('edit/step'));
  }

}));

export default StepsList;
