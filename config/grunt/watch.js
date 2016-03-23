module.exports = {
	css: {
		options: {
			debounceDelay: 500,
			livereload: true,
		},
		files: [ '<%= paths.source %>/style.css' ],
		tasks: [ 'pixrem:theme', 'postcss:theme', 'wpcss:theme', 'cssjanus:theme' ]
	},
	less: {
		files: [
			'<%= paths.less %>/*.less',
			'<%= paths.less %>/**/*.less'
		],
		tasks: [ 'less:theme' ]
	}
};
