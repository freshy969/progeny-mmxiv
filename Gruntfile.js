/*jshint node:true */

module.exports = function(grunt) {
	'use strict';

	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),
		version: '<%= pkg.version %>',

		/**
		 * Watch sources files and compile when they're changed.
		 */
		watch: {
			css: {
				options: {
					livereload: true,
				},
				files: ['style.css'],
				tasks: ['csscomb']
			}
		},

		/**
		 * Replace version numbers when building a release.
		 */
		'string-replace': {
			build: {
				options: {
					replacements: [{
						pattern: /Version: .+/,
						replacement: 'Version: <%= version %>'
					}]
				},
				files: {
					'./': 'style.css'
				}
			}
		},

		/**
		 * Archive the theme in the /release directory, excluding development
		 * and build related files.
		 *
		 * The zip archive will be named: audiotheme-fourteen-{{version}}.zip
		 */
		compress: {
			build: {
				options: {
					archive: 'release/<%= pkg.slug %>-<%= version %>.zip'
				},
				files: [
					{
						src: [
							'**',
							'!node_modules/**',
							'!release/**',
							'!.csscomb.json',
							'!Gruntfile.js',
							'!package.json'
						],
						dest: '<%= pkg.slug %>/'
					}
				]
			}
		}

	});

	/**
	 * Default task.
	 */
	grunt.registerTask( 'default', [ 'csscomb', 'watch' ]);

	/**
	 * Build a release.
	 *
	 * Bumps the version numbers in styles.css. Defaults to the
	 * version set in package.json, but a specific version number can be passed
	 * as the first argument. Ex: grunt release:1.2.3
	 *
	 * The project is then zipped into an archive in the release directory,
	 * excluding unnecessary source files in the process.
	 */
	grunt.registerTask( 'build', function( arg1 ) {
		var pkg = grunt.file.readJSON( 'package.json' ),
			version = 0 === arguments.length ? pkg.version : arg1;

		grunt.config.set( 'version', version );
		grunt.task.run( 'string-replace:build' );
		grunt.task.run( 'compress:build' );
	});

	/**
	 * @link https://github.com/csscomb/csscomb.js/pull/95
	 */
	grunt.registerTask( 'csscomb', function() {
		var command = 'node_modules/.bin/csscomb',
			done = this.async();

		require( 'csscomb' );

		command += 'win32' === require( 'os' ).platform() ? '.cmd' : '';

		grunt.util.spawn({
			cmd: command,
			args: [
				'style.css'
			],
			opts: { stdio: 'inherit' }
		}, done );
	});
};