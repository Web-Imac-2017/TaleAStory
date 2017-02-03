// Dans ce fichier, on va créer la task pour build les fichiers scss.
// La configuration est similaire à celle du build pour les fichiers js

var gulp         = require('gulp');              // Base gulp package
//var minify       = require('@aquafadas/gulp-php-minify');   // Minify PhP
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files

var mapError     = require('../error');
var config       = require('../config');

var configPhp = {
  srcPath    : './src/php/',         // Les fichiers à watch
  outputDir : config.outputDir + '/php',     // Le dossier ou le build sera généré
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('phpAll', function() {
  return buildPhp(configPhp.srcPath + '*.php');
});

gulp.task('watchPHP', ['phpAll'], function(){
	gulp.watch(configPhp.srcPath + '*.php', function(event){
		buildPhp(event.path);
	});
});

function buildPhp(phpSrc) {
  return gulp.src(phpSrc)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .on('error', mapError)
    //.pipe(minify()) // Minify build file
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(configPhp.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));
}
