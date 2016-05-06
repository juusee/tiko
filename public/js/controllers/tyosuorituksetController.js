angular.module('tikoApp').controller('TyosuorituksetController', ['$scope', '$http', function($scope, $http) {

    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getTyosuoritukset'
    }).then(function successCallback(response) {
        $scope.tyosuoritukset = response.data;
    }, function errorCallback(response) {
        console.log(response);
    });
}]);