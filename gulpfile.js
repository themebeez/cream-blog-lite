// Gulp file to compile Cream blog theme resources

const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const replace = require('gulp-replace');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const rtlcss = require('gulp-rtlcss');
const rename = require('gulp-rename');


/*
===========================================================
=
= Change these constants according to your need
=
====================================================
*/

// 1# Script files path
const scriptpath = {

    script_src: [

        'assets/src/js/plugins/*.js',
        'assets/src/js/custom/*.js'
    ],

    script_dist: "assets/dist/js/",
}
const output_js_file_name = "bundle.js"; // what would you like to name your minified bundled js file

// 2# SASS/SCSS file path
const sasspath = {

    sass_src: "./assets/src/scss/**/*.scss",
    sass_dist: "assets/dist/css/",
}
const compiled_sass_css_file_name = "main.css"; // what would you like to name your compiled CSS file


/*
===========================================================
=
= Define task (Almost no chnages required)
=
====================================================
*/

gulp.task('scriptsTask',  function() {
    return gulp.src(scriptpath.script_src)
        .pipe(concat(output_js_file_name))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest(scriptpath.script_dist));
});

gulp.task('sassTask', function() {
    var onError = function(err) {
        notify.onError({
            title: "Gulp",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
            sound: "Beep"
        })(err);
        this.emit('end');
    };
    return gulp.src(sasspath.sass_src)
        .pipe(sourcemaps.init()) // initialize sourcemaps first
        .pipe(plumber({ errorHandler: onError }))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(postcss([autoprefixer('last 2 version'), cssnano()])) // PostCSS plugins
        .pipe(concat(compiled_sass_css_file_name))
        .pipe(sourcemaps.write('.')) // write sourcemaps file in current directory
        .pipe(gulp.dest(sasspath.sass_dist)); // put final CSS in dist folder
});


// just hit the command "gulp" it will run the following tasks...
gulp.task('default', gulp.series('scriptsTask', 'sassTask', (done) => {

    gulp.watch(scriptpath.script_src, gulp.series('scriptsTask'));
    gulp.watch(sasspath.sass_src, gulp.series('sassTask'));
    done();
}));