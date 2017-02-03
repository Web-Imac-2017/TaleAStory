// Un simple fichier pour faire des log d'erreur dans la console.

var chalk = require('chalk');     // Allows for coloring for logs
var gutil = require('gulp-util'); // Provides gulp utilities, including log

module.exports = function(err) {
  if (err.fileName) {
    // Regular js error
    gutil.log(chalk.red(err.name)
      + ': ' + chalk.yellow(err.fileName.replace(__dirname + '/src/js/', ''))
      + ', ' + 'Line ' + chalk.magenta(err.lineNumber)
      + ' & ' + 'Column ' + chalk.magenta(err.columnNumber || err.column)
      + '\n>> ' + chalk.blue(err.description));
  } else if (err.plugin == "gulp-sass") {
    // Regular sass error
    gutil.log(chalk.red(err.name)
      + ': ' + chalk.yellow(err.file)
      + ', ' + 'Line ' + chalk.magenta(err.line)
      + ' & ' + 'Column ' + chalk.magenta(err.column)
      + '\n>> ' + chalk.blue(err.messageOriginal));
  } else {
    // Browserify error..
    gutil.log(chalk.red(err.name)
      + ': '
      + chalk.yellow(err.message));
    gutil.log(err);
  }
}
