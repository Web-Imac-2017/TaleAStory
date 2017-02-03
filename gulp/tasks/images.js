var gulp         = require('gulp');              // Base gulp package
var imageResize = require('gulp-image-resize');
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files
var rename     = require('gulp-rename');         // Rename sources

var mapError     = require('../error');

var configImage = {
  srcPath    : './images/',         // Les fichiers à watch
  outputDir : '../Sites/RangerPower/assets/images',     // Le dossier ou le build sera généré
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('imageAll', function() {
  return buildImage(configImage.srcPath + '*');
});

gulp.task('watchIMAGE', ['imageAll'], function(){
	gulp.watch(configImage.srcPath + '*', function(event){
		buildImage(event.path);
	});
});

function buildImage(imageSrc) {
  console.log('compile '+ imageSrc + '...');
  gulp.src(imageSrc)
    .on('error', mapError)
    .pipe(imageResize({
      height : 100,
      upscale : false
    }))
    .pipe(rename(function (path) {
			path.basename += "_tiny";
		}))
    .pipe(gulp.dest(configImage.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));

  gulp.src(imageSrc)
    .on('error', mapError)
    .pipe(imageResize({
      height : 500,
      upscale : false
    }))
    .pipe(rename(function (path) {
			path.basename += "_medium";
		}))
    .pipe(gulp.dest(configImage.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));

    return gulp.src(imageSrc)
      .on('error', mapError)
      .pipe(imageResize({
        height : 1000,
        upscale : false
      }))
      .pipe(rename(function (path) {
  			path.basename += "_large";
  		}))
      .pipe(gulp.dest(configImage.outputDir))
      .pipe(notify({
        onLast: true,
        message: 'Generated file: <%= file.relative %>',
      }));
}
