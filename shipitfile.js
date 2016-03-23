var config = require( 'wp-theme-config' )();

module.exports = function ( shipit ) {
	shipit.initConfig({
		aws: {
			servers: 's3',
			bucket: 'audiotheme-packages'
		},
		demo: {
			servers: 'deploy@demo.audiotheme.com',
			deployRoot: '/usr/share/nginx/html/demo.audiotheme.com/deploy/themes/',
			publicPath: '/usr/share/nginx/html/demo.audiotheme.com/public/wp-content/themes/'
		},
		production: {
			servers: 'serverpilot@audiotheme.com',
			releasesPath: '/srv/users/serverpilot/apps/audiotheme/shared/downloads/'
		},
		staging: {
			servers: 'deploy@staging.cedaro.com',
			deployRoot: '/srv/www/staging.cedaro.com/deploy/themes/',
			publicPath: '/srv/www/staging.cedaro.com/public/wp-content/themes/'
		}
	});

	shipit.task( 'deploy', function() {
		return config.loadShipitTask( 'deploy' )( shipit );
	});

	shipit.task( 'release', function( callback ) {
		return config.loadShipitTask( 'release' )( shipit );
	});
};
