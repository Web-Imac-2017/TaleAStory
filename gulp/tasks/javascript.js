// Dans ce fichier, on définit la task pour build les fichiers js

var babelify   = require('babelify');            // Used to convert ES6 & JSX to ES5
var browserify = require('browserify');          // Providers "require" support, CommonJS
var buffer     = require('vinyl-buffer');        // Vinyl stream support
var duration   = require('gulp-duration');       // Time aspects of your gulp process
var gulp       = require('gulp');                // Base gulp package
var gutil      = require('gulp-util');           // Provides gulp utilities, including logging and beep
var notify     = require('gulp-notify');         // Provides notification to both the console and Growel
var merge      = require('utils-merge');         // Object merge tool
var rename     = require('gulp-rename');         // Rename sources
var source     = require('vinyl-source-stream'); // Vinyl stream support
var sourcemaps = require('gulp-sourcemaps');     // Provide external sourcemap files
var stringify  = require('stringify');           // Require text files like templates
var uglify     = require('gulp-uglify');         // Require text files like templates
var vueify     = require('vueify');              // Allow you to write vue files
var transform = require('vinyl-transform');
var watchify   = require('watchify');            // Watchify for source changes
var glob = require("glob");
var mapError   = require('../error');
var config       = require('../config');

// On centralise la config, vous pouvez changer librement les valeurs selon vos besoins / envies
var configJS = {
  srcPath      : './src/js/',  // Fichier principal à build
  outputDir : config.outputDir + '/assets/js', // Chemin ou va être généré le build
};

gulp.task('jsAll', function() {
	glob(configJS.srcPath + '*.js', function (er, files) {
		for(var i = 0; i<files.length; i++){
			buildJS(files[i]);
		}
	});
});

gulp.task('watchJS', ['jsAll'], function(){
	gulp.watch(configJS.srcPath + '*.js', function(event){
		buildJS(event.path);
	});
});

function buildJS(jsSrc) {
	console.log('compile '+ jsSrc + '...');
	var b = browserify({
			fullPath: true,
			debug: true,
			entries: [jsSrc],
			cache: {},
			packageCache: {}
		});
	return b
		.transform(stringify,{ appliesTo: { includeExtensions: ['.html'] }, minify: true })
		.transform(babelify, {presets: ["es2015", "react"]}) // Babel, pour l'ES6
		.transform(vueify)
		.bundle()
		.on('error', mapError)                   // Map error reporting
		.pipe(source(jsSrc))                 // Set source name
		.pipe(buffer())                          // Convert to gulp pipeline
		.pipe(rename(function (path) {
			path.dirname = ".";
			path.extname = ".min.js";
		}))
		.pipe(sourcemaps.init({loadMaps: true})) // Extract the inline sourcemaps
		.pipe(uglify())                          // Minify the build file
		.pipe(sourcemaps.write('./'))            // Set folder for sourcemaps to output to
		.pipe(gulp.dest(configJS.outputDir))       // Set the output folder
		.pipe(notify({
			onLast: true,
			message: 'Generated file: <%= file.relative %>',
		}));
}
