'use strict';

import domready  from 'domready';
import React from 'react';
import ReactDOM from 'react-dom';
import TweenMax from './greenshock/TweenMax.js';
import TweenLite from './greenshock/TweenMax.js';
import idGen from './utils/idGenerator';
import AppRouter from './app/router';

domready(() => {
		ReactDOM.render(AppRouter, document.getElementById('root'));
});
