/*jshint node:true */

module.exports = function( grunt ) {
	'use strict';

	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	grunt.initConfig({
		audiotheme: {
			options: {
				type: 'theme'
			},
			wporg: {
				options: {
					tasks: [
						'jshint',
						'pixrem',
						'autoprefixer',
						'wpcss',
						'cssjanus',
						'clean:build',
						'copy:build',
						'addtextdomain:build',
						'makepot:build',
						'compress:build',
						'clean:build'
					]
				}
			}
		},

		/**
		 * Check JavaScript for errors and warnings.
		 */
		jshint: {
			theme: [
				'Gruntfile.js'
			]
		}
	});

	/**
	 * Default task.
	 */
	grunt.registerTask( 'default', [ 'jshint', 'pixrem', 'autoprefixer', 'wpcss', 'cssjanus' ] );

};
