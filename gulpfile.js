const gulp = require('gulp');
const concat = require('gulp-concat');
const cleanCss = require('gulp-clean-css');
const sassGlob = require('gulp-sass-glob');
const plumber = require('gulp-plumber');
const prefix = require('gulp-autoprefixer');
const flatten = require('gulp-flatten');
const rename = require('gulp-rename');
const sass = require('gulp-sass')(require('sass'));

var path = {
  source: 'src/',
  assets: 'build/assets/'
}

gulp.task('styles', (done) => {
  const styles = gulp.src([path.source + '/styles/app.scss'])
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

gulp.task('fonts', (done) => {
  gulp.src([path.source + '/fonts/**/*.{woff,woff2}'])
    .pipe(flatten())
    .pipe(gulp.dest(path.assets + '/fonts/'));

  done();
})

gulp.task('watch', () => {
  gulp.watch('./src/**/*', gulp.series('styles'));
})

gulp.task('default', gulp.parallel('styles', 'fonts', 'watch'));