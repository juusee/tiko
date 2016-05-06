angular.module('tikoApp').controller('UusiAsiakasController',
    ['$scope', '$http', function($scope, $http) {

        $scope.uusiAsiakasForm = {};

        $scope.submitAsiakasForm = function() {
            if ($scope.uusi_asiakas_form.$valid) {
                $http({
                    method: 'POST',
                    url: '/tikoAngular/ajax/createAsiakas',
                    data: $.param($scope.uusiAsiakasForm),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
                }).then(function successCallback(response) {
                    console.log(response);
                    $scope.uusiAsiakasFormMessage = response.data.message;
                }, function errorCallback(response) {
                    console.log("ERROR: " + response);
                });
            }
        };
    }]);
