// Dans ce fichier, on va créer la task pour build les fichiers scss.
// La configuration est similaire à celle du build pour les fichiers js

var gulp         = require('gulp');              // Base gulp package
var minify       = require('gulp-htmlmin');   // Minify Html
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files

var mapError     = require('../error');

var configHtml = {
  srcPath    : './pages/',         // Les fichiers à watch
  outputDir : '../www/RangerPower/',     // Le dossier ou le build sera généré
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('htmlAll', function() {
  return buildHtml(configHtml.srcPath + '*.html');
});

gulp.task('watchHTML', ['htmlAll'], function(){
	gulp.watch(configHtml.srcPath + '*.html', function(event){
		buildHtml(event.path);
	});
});

function buildHtml(htmlSrc) {
  console.log('compile '+ htmlSrc + '...');
  return gulp.src(htmlSrc)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .on('error', mapError)
    .pipe(minify({collapseWhitespace: true})) // Minify build file
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(configHtml.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));
}
