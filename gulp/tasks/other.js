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
var configRessources = {
  srcPath    : './ressources/',         // Les fichiers à watch
  outputDir : config.outputDir,     // Le dossier ou le build sera généré
};


gulp.task('otherAll', function() {
  moveAll(configRessources.srcPath + '**/*');
  moveAll(configRessources.srcPath + '.*');
  buildPHP(configPhp.srcPath + '**/*.php');
  return buildPHP(configPhp.srcPath + '*.php');
});

gulp.task('watchOTHER', ['otherAll'], function(){
	gulp.watch(configPhp.srcPath + '**/*.php', function(event){
		buildPHP(event.path);
	});
	gulp.watch(configPhp.srcPath + '*.php', function(event){
		buildPHP(event.path);
	});
  gulp.watch(configRessources.srcPath + '**/*', function(event){
		moveAll(event.path);
	});
  gulp.watch(configRessources.srcPath + '.*', function(event){
		moveAll(event.path);
	});
});

function buildPHP(phpSrc) {
  return gulp.src(phpSrc)
        .pipe(gulp.dest(configPhp.outputDir));
}

function moveAll(src){
  return gulp.src(src)
        .pipe(gulp.dest(configRessources.outputDir));
}
