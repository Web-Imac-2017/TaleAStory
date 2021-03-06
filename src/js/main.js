'use strict';
import {GlobalBack} from './utils/interfaceback';
import domready  from 'domready';
import React from 'react';
import ReactDOM from 'react-dom';
import AppRouter from './app/router';
import webGL from './webgl/webgl.js';

var bg_anim;

domready(() => {
		webGL.runWebGL();
		ReactDOM.render(AppRouter, document.getElementById('root'));
});
