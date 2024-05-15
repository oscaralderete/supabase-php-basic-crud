const gulp = require('gulp'),
	browserSync = require('browser-sync').create(),
	reload = browserSync.reload;

/* start browserSync */
function watch() {
	browserSync.init({
		proxy: 'http://localhost:666/Supabase',
		open: 'external'
	})
	gulp.watch(['views/**/*.php', 'public/js/*.*', 'public/css/*.css']).on('change', reload);
}

exports.watch = watch;

gulp.task('default', gulp.series(watch));
