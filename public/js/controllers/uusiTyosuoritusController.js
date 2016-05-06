angular.module('tikoApp').controller('UusiTyosuoritusController',
    ['$scope', '$http', function($scope, $http) {

    $scope.uusiTyosuoritusForm = {};

    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getAsiakkaat'
    }).then(function successCallback(response) {
        $scope.asiakkaat = response.data;
        $scope.uusiTyosuoritusForm.anro = $scope.asiakkaat[0][1] + " " + $scope.asiakkaat[0][2];
    }, function errorCallback(response) {
        console.log("ERROR: " + response);
    });

    $scope.submitTyosuoritusForm = function() {
        if ($scope.uusi_tyosuoritus_form.$valid) {
            $http({
                method: 'POST',
                url: '/tikoAngular/ajax/createTyosuoritus',
                data: $.param($scope.uusiTyosuoritusForm),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
            }).then(function successCallback(response) {
                $scope.uusiTyosuoritusFormMessage = response.data.message;
            }, function errorCallback(response) {
                console.log("ERROR: " + response);
            });
        }
    };
}]);