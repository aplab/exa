'use strict';
const gulp = require('gulp'),
    livereload = require('gulp-livereload'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    cssnano = require('gulp-cssnano'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    header = require('gulp-header'),
    replace = require('gulp-replace'),
    imagemin = require('gulp-imagemin'),
    sourcemaps = require('gulp-sourcemaps'),
    md5 = require('gulp-md5-plus'),
    gutil = require('gulp-util'),
    debug = require('gulp-debug'),
    newer = require('gulp-newer'),
    remember = require('gulp-remember'),
    order = require('gulp-order'),
    cached = require('gulp-cached'),
    touch = require('gulp-touch'),
    plumber = require('gulp-plumber')
;
var pkg = require('./package.json');
var dest_dir = '../../../public/static';

gulp.task('scss-dev', function () {
    return gulp.src([
        './stylesheet/main.scss',
        './admin_modules/AplAdminMenu/AplAdminMenu.scss',
        './admin_modules/AplDataTable/AplDataTable.scss',
        './admin_modules/AplInstanceEditor/AplInstanceEditor.scss',
        './admin_modules/AplActionMenu/AplActionMenu.scss',
        './admin_modules/AplAdminToolbar/AplAdminToolbar.scss',
        './admin_modules/AplAdminDialog/AplAdminDialog.scss',
        './admin_modules/AplAdminFileUploader/AplAdminFileUploader.scss',
        './admin_modules/AplAdminImageHistory/AplAdminImageHistory.scss',
        './admin_modules/Scrollable/CapsuleUiScrollable.scss'
    ])
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(replace('../fonts/', ''))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(cssnano({discardUnused: {fontFace: false},zindex: false}))
        .pipe(concat('build.css'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(dest_dir))
        .pipe(touch());
});

gulp.task('cke', function () {
    return gulp.src(
        [
            './node_modules/ckeditor/**/*.*',
            // '!./node_modules/ckeditor/samples/**/*.*',
            '!./node_modules/ckeditor/bower.json',
            '!./node_modules/ckeditor/composer.json',
            '!./node_modules/ckeditor/README.md',
            '!./node_modules/ckeditor/CHANGES.md'
        ]/*, {since: gulp.lastRun('fonts')}*/)
        .pipe(gulp.dest('./' + dest_dir + '/ckeditor'));
});

gulp.task('css-dev', function () {
    return gulp.src([
        // './node_modules/opensans-webkit/src/css/open-sans.css',
        './node_modules/roboto-fontface/css/roboto/roboto-fontface.css',
        './node_modules/bootstrap/dist/css/bootstrap.css',
        './node_modules/@fortawesome/fontawesome-free/css/all.css',
        './node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css'
    ]/**, {since: gulp.lastRun('css')}*/)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass())
        // .pipe(replace('../../fonts/OpenSans', 'fonts/OpenSans'))
        .pipe(replace('../../fonts/roboto', 'fonts/roboto'))
        .pipe(replace('../webfonts/fa-', 'webfonts/fa-'))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(cssnano({discardUnused: {fontFace: false},zindex: false}))
        .pipe(concat('vendor.css'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(dest_dir))
        .pipe(touch());
});

gulp.task('scripts-dev', function () {
    return gulp.src(
        [
            './node_modules/jquery/dist/jquery.js',
            './node_modules/jquery-mousewheel/jquery.mousewheel.js',
            './node_modules/js-cookie/src/js.cookie.js',
            './node_modules/popper.js/dist/umd/popper.js',
            './node_modules/bootstrap/dist/js/bootstrap.js',
            './node_modules/screenfull/dist/screenfull.js',
            './node_modules/clipboard/dist/clipboard.min.js',
            './node_modules/moment/min/moment.min.js',
            './node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js',
            './admin_modules/AplAdminMenu/AplAdminMenu.js',
            './admin_modules/AplDataTable/AplDataTable.js',
            './admin_modules/AplInstanceEditor/AplInstanceEditor.js',
            './admin_modules/AplActionMenu/AplActionMenu.js',
            './admin_modules/AplAdminToolbar/AplAdminToolbar.js',
            './admin_modules/Scrollable/CapsuleUiScrollable.js',
            './admin_modules/AplAdminDialog/AplAdminDialog.js',
            './admin_modules/AplAdminFileUploader/AplAdminFileUploader.js',
            './admin_modules/AplAdminImageHistory/AplAdminImageHistory.js',
            './js/main.js'
        ])
        .pipe(plumber())
        .pipe(cached('scripts-dev'))
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(remember('scripts-dev'))
        .pipe(concat('build.js'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(dest_dir));
});

gulp.task('fonts', function () {
    return gulp.src(
        [
            // './node_modules/opensans-webkit/**/*.{eot,svg,ttf,woff,woff2}',
            './node_modules/roboto-fontface/**/*.{eot,svg,ttf,woff,woff2}',
            './node_modules/@fortawesome/fontawesome-free/**/*.{eot,ttf,woff,woff2}'
        ], {since: gulp.lastRun('fonts')})
        .pipe(plumber())
        .pipe(gulp.dest('./' + dest_dir));
});

// gulp.task('img', function () {
//     var imgSrc = './site/**/*.{jpg,png,gif,svg}',
//         imgDst = '../public/capsule/static';
//     return gulp.src(imgSrc, {since: gulp.lastRun('img')})
//         .pipe(plumber())
//         .pipe(newer(imgDst))
//         .pipe(gulp.dest(imgDst));
// });

gulp.task('watch', function () {
    gulp.watch('./stylesheet/*.scss', gulp.series('scss-dev'));
    gulp.watch('./stylesheet/*.css', gulp.series('css-dev'));
    gulp.watch('./js/*.js', gulp.series('scripts-dev'));
    gulp.watch('./admin_modules/**/*.js', gulp.series('scripts-dev'));
    gulp.watch('./admin_modules/**/*.scss', gulp.series('scss-dev'));
});

gulp.task('default', gulp.series('scss-dev', 'css-dev', 'scripts-dev', /*'img',*/ 'fonts'));
gulp.task('dev', gulp.series('default', 'watch'));
