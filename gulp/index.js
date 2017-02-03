// Via fs on récupère l'ensemble des tasks Gulp
// Les tasks seront lancées dans default.js

var fs    = require('fs');

var tasks = fs.readdirSync('./gulp/tasks');
tasks.forEach(function(task) {
  require('./tasks/' + task);
})
