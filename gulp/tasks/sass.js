// Dans ce fichier, on va créer la task pour build les fichiers scss.
// La configuration est similaire à celle du build pour les fichiers js

var autoprefixer = require('gulp-autoprefixer'); // Auto prefixer for css
var gulp         = require('gulp');              // Base gulp package
var minify       = require('gulp-minify-css');   // Minify CSS
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sass         = require('gulp-sass');         // Used to build sass files
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files

var mapError     = require('../error');
var config       = require('../config');

var configSass = {
  srcPath    : './src/scss/',         // Les fichiers à watch
  outputDir : config.outputDir + '/assets/css',     // Le dossier ou le build sera généré
  node_path : './node_modules/'
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('sassAll', function() {
  return buildSass(configSass.srcPath + '*.scss');
});

gulp.task('watchSASS', ['sassAll'], function(){
	gulp.watch(configSass.srcPath + '*.scss', function(event){
		buildSass(event.path);
	});
  gulp.watch(configSass.srcPath + '**/*.scss', function(event){
		buildSass(configSass.srcPath + '*.scss');
	});
});

function buildSass(sassSrc) {
  console.log('compile '+ sassSrc + '...');
  return gulp.src(sassSrc)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sass({
                style: 'compressed',
                includePaths: [
                  configSass.srcPath,
                  'C:/wamp/TaleAStory/node_modules/bootstrap-sass/assets/stylesheets'
                ]}))
    .on('error', mapError)
    .pipe(rename(function (path) {
		    path.extname = ".min.css";
	  }))
    .pipe(autoprefixer())                 // Auto prefix css rules for each browsers
    .pipe(minify({processImport: false})) // Minify build file
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(configSass.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));
}
