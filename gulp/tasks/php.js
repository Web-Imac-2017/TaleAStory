// Dans ce fichier, on va créer la task pour build les fichiers scss.
// La configuration est similaire à celle du build pour les fichiers js

var gulp         = require('gulp');              // Base gulp package
//var minify       = require('@aquafadas/gulp-php-minify');   // Minify PhP
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files

var mapError     = require('../error');
var config       = require('../config');
var jsonminify = require('gulp-jsonminify');

var configPhp = {
  srcPath    : './src/php/',         // Les fichiers à watch
  outputDir : config.outputDir + '/php',     // Le dossier ou le build sera généré
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('phpAll', function() {
  buildJSON(configPhp.srcPath + '*.json')
  return buildPhp(configPhp.srcPath + '*.php');
});

gulp.task('watchPHP', ['phpAll'], function(){
	gulp.watch(configPhp.srcPath + '*.php', function(event){
		buildPhp(event.path);
	});
  gulp.watch(configPhp.srcPath + '*.json', function(event){
		buildJSON(event.path);
	});
});

function buildPhp(phpSrc) {
  return gulp.src(phpSrc)
    .on('error', mapError)
    .pipe(gulp.dest(configPhp.outputDir));
}

function buildJSON(jsonSrc){
  return gulp.src(jsonSrc)
        .pipe(jsonminify())
        .pipe(gulp.dest(configPhp.outputDir));
}
