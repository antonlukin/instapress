var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var cleanCss = require('gulp-clean-css');
var sassGlob = require('gulp-sass-glob');
var plumber = require('gulp-plumber');
var prefix = require('gulp-autoprefixer');
var flatten = require('gulp-flatten');
var rename = require('gulp-rename');

var path = {
  source: 'src/',
  assets: 'build/assets/'
}

gulp.task('styles', function (done) {
  let styles = gulp.src([path.source + '/styles/app.scss'])
    .pipe(plumber())
    .pipe(sassGlob({
      allowEmpty: true
    }))
    .pipe(sass({
      errLogToConsole: true
    }))
    .pipe(prefix())

  styles.pipe(rename('styles.css'))
    .pipe(gulp.dest(path.assets))

  styles.pipe(concat('styles.min.css'))
    .pipe(cleanCss())
    .pipe(gulp.dest(path.assets))

  done();
})

gulp.task('fonts', function () {
  gulp.src([path.source + '/fonts/**/*.{woff,woff2}'])
    .pipe(flatten())
    .pipe(gulp.dest(path.assets + '/fonts/'));
})

gulp.task('watch', function () {
  gulp.watch('./src/**/*', gulp.series('styles'));
})

gulp.task('default', gulp.parallel('styles', 'fonts', 'watch'));