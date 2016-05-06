angular.module('tikoApp').config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {

	$routeProvider
		.when('/', {
			templateUrl: '/tikoAngular/public/views/tyosuoritukset.html',
			controller: 'TyosuorituksetController'
		})

		.when('/laskut', {
			templateUrl: '/tikoAngular/public/views/laskut.html',
			controller: 'LaskutController'
		})

		.when('/tyosuoritukset/:tsnro', {
			templateUrl: '/tikoAngular/public/views/tyosuoritus.html',
			controller: 'TyosuoritusController'
		})

		.when('/uusiTyosuoritus', {
			templateUrl: '/tikoAngular/public/views/uusiTyosuoritus.html',
			controller: 'UusiTyosuoritusController'
		})

		.when('/uusiAsiakas', {
			templateUrl: '/tikoAngular/public/views/uusiAsiakas.html',
			controller: 'UusiAsiakasController'
		})

		.when('/uusiLasku', {
			templateUrl: '/tikoAngular/public/views/uusiLasku.html',
			controller: 'UusiLaskuController'
		})

		.otherwise({
			redirectTo: '/'
		});

	$locationProvider.html5Mode(true);

}]);