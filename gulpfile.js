var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var cleanCss = require('gulp-clean-css');
var sassGlob = require('gulp-sass-glob');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var prefix = require('gulp-autoprefixer');
var flatten = require('gulp-flatten');

var path = {
  source: 'src/',
  assets: 'app/assets/'
}

gulp.task('styles', function (done) {
  let styles = gulp.src([path.source + '/styles/app.scss'])
    .pipe(plumber())
    .pipe(sassGlob())
    .pipe(sass({ errLogToConsole: true }))
    .pipe(prefix({ browsers: ['ie >= 10', 'ff >= 30', 'chrome >= 34', 'safari >= 7', 'opera >= 23', 'ios >= 7', 'android >= 4.4'] }))

  styles.pipe(concat('styles.min.css'))
    .pipe(cleanCss({ compatibility: 'ie8' }))
    .pipe(gulp.dest(path.assets))

  done();
})

gulp.task('scripts', function (done) {
  let scripts = gulp.src([path.source + '/scripts/*.js'])
    .pipe(plumber())
    .pipe(uglify())

  scripts.pipe(concat('scripts.min.js'))
    .pipe(gulp.dest(path.assets))

  done();
})

gulp.task('images', function () {
  gulp.src([path.source + '/images/**/*'])
    .pipe(gulp.dest(path.assets + '/images/'));
})

gulp.task('fonts', function () {
  gulp.src([path.source + '/fonts/**/*.{woff,woff2}'])
    .pipe(flatten())
    .pipe(gulp.dest(path.assets + '/fonts/'));
})

gulp.task('watch', function () {
  gulp.watch('./src/**/*', gulp.series('styles', 'scripts'));
})

gulp.task('default', gulp.parallel('styles', 'scripts', 'images', 'fonts', 'watch'));
