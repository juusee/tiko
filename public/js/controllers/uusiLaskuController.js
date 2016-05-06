angular.module('tikoApp').controller('UusiLaskuController',
    ['$scope', '$http', function($scope, $http) {

        $scope.uusiLaskuForm = {};

        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/getTyosuoritukset'
        }).then(function successCallback(response) {
            $scope.tyosuoritukset = response.data;
        }, function errorCallback(response) {
            console.log(response);
        });

        $scope.submitLaskuForm = function() {
            if ($scope.uusi_lasku_form.$valid) {
                $http({
                    method: 'POST',
                    url: '/tikoAngular/ajax/createLasku',
                    data: $.param($scope.uusiLaskuForm),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
                }).then(function successCallback(response) {
                    console.log(response);
                    $scope.uusiLaskuFormMessage = response.data.message;
                    console.log($scope.uusiLaskuFormMessage);
                }, function errorCallback(response) {
                    console.log("ERROR: " + response);
                });
            }
        };
    }]);