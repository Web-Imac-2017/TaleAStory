// la task default est la task par défaut de Gulp.
// Il faut obligatoirement qu'une task default soit présente pour que Gulp fonctionne.

var gulp = require('gulp');

// Dans l'array, ajouter les tasks que vous voulez lancer.
gulp.task('default', ['watchJS','watchSASS', 'watchOTHER', 'watchHTML','watchIMAGE']);
//gulp.task('default', ['watchOTHER']);
