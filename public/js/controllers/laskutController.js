angular.module('tikoApp').controller('LaskutController', ['$scope', '$http', function($scope, $http) {

    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getLaskut'
    }).then(function successCallback(response) {
        $scope.laskut = response.data;
    }, function errorCallback(response) {
        console.log(response);
    });

    $scope.tarkistaLaskut = function() {
        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/tarkistaLaskut'
        }).then(function successCallback(response) {
            $scope.tarkistaLaskutMessage = response.data.message;
            $scope.laskut = response.data.laskut;
        }, function errorCallback(response) {
            console.log(response);
        });
    }

    $scope.maksaLasku = function($id) {
        $http({
            method: 'POST',
            url: '/tikoAngular/ajax/maksaLasku/' + $id
        }).then(function successCallback(response) {

        }, function errorCallback(response) {
            console.log(response);
        });
    }
}]);