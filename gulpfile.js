// Config
var enable_react = false;
var enable_livereload = false;
var enable_uglify = true;


// Include packages
var gulp       = require('gulp');
var gulpif     = require('gulp-if');
var gutil      = require('gulp-util');
var coffee     = require('gulp-coffee');
var stylus     = require('gulp-stylus');
var nib        = require('nib');
var jeet       = require('jeet');
var rupture    = require('rupture');
var cp         = require('child_process');
var uglify     = require('gulp-uglify');
var react      = require('gulp-react');
var elixir     = require('laravel-elixir');
var livereload = require('laravel-elixir-livereload');


// GO!
console.log("\u001b[2J\u001b[0;0H");

if (enable_livereload) {
  elixir(function(mix) {
     mix.livereload([
      'app/**/*',
      'public/**/*',
      'public/vendor/**/*',
      'resources/views/**/*',
      'resources/lang/**/*',
    ]);
  });
}

gulp.task('stylus', function () {
  gulp.src('./resources/assets/stylus/*.styl')
    .pipe(stylus({
      use: [nib(), jeet(), rupture()],
      compress: true
    }))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('coffee', function() {
  gulp.src('./resources/assets/coffee/**/*')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulpif(enable_react, react()))
    .pipe(gulpif(enable_uglify, uglify()))
    .pipe(gulp.dest('./public/js'));
});

gulp.task('watch', function () {
  gulp.watch('./resources/assets/stylus/**/*', ['stylus']);
  gulp.watch('./resources/assets/coffee/**/*', ['coffee']);
});

gulp.task('default', ['coffee', 'stylus', 'watch']);
