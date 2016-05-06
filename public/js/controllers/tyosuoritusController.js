angular.module('tikoApp').controller('TyosuoritusController',
    ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {

    $scope.tyoForm = {};
    $scope.tarvikeForm = {};

    var getTyosuoritus = function() {
        // Hae työsuoritus
        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/getTyosuoritus/' + $routeParams.tsnro
        }).then(function successCallback(response) {
            $scope.tyosuoritus = response.data;
            getAsiakas();
        }, function errorCallback(response) {
            console.log(response);
        });
    };

    var getAsiakas = function () {
        // Hae asiakas
        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/getAsiakas/' + $scope.tyosuoritus[8]
        }).then(function successCallback(response) {
            $scope.asiakas = response.data;
        }, function errorCallback(response) {
            console.log(response);
        });
    };

    var getTarvikkeet = function() {
        // Hae kaikki tarvikkeet
        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/getTarvikkeet'
        }).then(function successCallback(response) {
            $scope.tarvikkeet = response.data;
            $scope.tarvikeForm.nimi = $scope.tarvikkeet[0][1];
        }, function errorCallback(response) {
            console.log(response);
        });
    };

    var getTyot = function() {
        // Hae kaikki työt
        $http({
            method: 'GET',
            url: '/tikoAngular/ajax/getTyot'
        }).then(function successCallback(response) {
            $scope.tyot = response.data;
            $scope.tyoForm.nimi = $scope.tyot[0][1];
        }, function errorCallback(response) {
            console.log(response);
        });
    };

    $scope.getTarvikkeidenHinta = function(row) {
        var hinta = 0;
        angular.forEach($scope.tarvikkeet, function(elem) {
            if (elem[0] == row[0])
                hinta = parseInt(elem[2]) * parseInt(row[3]) * ((100-parseInt(row[4]))/100);
        });
        return hinta;
    };

    $scope.getTarvikeAlv = function($trnro) {
        var alv = 0;
        angular.forEach($scope.tarvikkeet, function(elem) {
            if (elem[0] == $trnro)
                alv = elem[7];
        });
        return alv;
    };

    $scope.getToidenHinta = function(row) {
        var hinta = 0;
        angular.forEach($scope.tyot, function(elem) {
            if (elem[0] == row[0])
                hinta = parseInt(elem[2]) * parseInt(row[3]) * ((100-parseInt(row[4]))/100);
        });
        return hinta;
    };

    $scope.getTyoAlv = function($tynro) {
        var alv = 0;
        angular.forEach($scope.tyot, function(elem) {
            if (elem[0] == $tynro)
                alv = elem[3];
        });
        return alv;
    };

    // Hae työsuorituksessa käytetyt tarvikkeet
    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getKaytetytTarvikkeet/' + $routeParams.tsnro
    }).then(function successCallback(response) {
        $scope.kaytetytTarvikkeet = response.data;
    }, function errorCallback(response) {
        console.log(response);
    });

    // Hae työsuorituksessa käytetyt tarvikkeet
    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getTehdytTyot/' + $routeParams.tsnro
    }).then(function successCallback(response) {
        $scope.tehdytTyot = response.data;
    }, function errorCallback(response) {
        console.log(response);
    });

    // Hae työsuorituksen laskut
    $http({
        method: 'GET',
        url: '/tikoAngular/ajax/getLaskut/' + $routeParams.tsnro
    }).then(function successCallback(response) {
        $scope.laskut = response.data;
    }, function errorCallback(response) {
        console.log(response);
    });

    $scope.submitTarvikeForm = function() {
        $scope.tarvikeFormErrorMaara = "";
        var maaraYlittyi = false;
        if ($scope.tarvike_form.$valid) {
            angular.forEach($scope.tarvikkeet, function(elem) {
                if (elem[1] == $scope.tarvikeForm.nimi) {
                    if (elem[4] < $scope.tarvikeForm.maara) {
                        $scope.tarvikeFormErrorMaara = "Tarvikemäärä ylittää varastossa olevien tarvikkeiden määrän!";
                        maaraYlittyi = true;
                    }
                }
            });

            if (maaraYlittyi) {
                return;
            }

            $http({
                method: 'POST',
                url: '/tikoAngular/ajax/createKaytettyTarvike/' + $routeParams.tsnro,
                data: $.param($scope.tarvikeForm),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
            }).then(function successCallback(response) {
                $scope.tarvikeFormMessage = response.data.message;
                var found = false;
                angular.forEach($scope.kaytetytTarvikkeet, function (elem) {
                    if (elem[2] == response.data.tarvike.nimi) {
                        elem[3] = parseInt(elem[3]) + parseInt(response.data.tarvike.maara);
                        elem[4] = response.data.tarvike.alennus;
                        found = true;
                    }
                });
                if (!found) {
                    var arr = new Array(5);
                    arr[2] = response.data.tarvike.nimi;
                    arr[3] = response.data.tarvike.maara;
                    arr[4] = response.data.tarvike.alennus;
                    $scope.kaytetytTarvikkeet.unshift(arr);
                }
                getTyosuoritus();
                getTarvikkeet();
            }, function errorCallback(response) {
                console.log("ERROR: " + response);
            });
        }
    };

    $scope.submitTyoForm = function() {
        if ($scope.tyo_form.$valid) {
            $http({
                method: 'POST',
                url: '/tikoAngular/ajax/createTehtyTyo/' + $routeParams.tsnro,
                data: $.param($scope.tyoForm),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
            }).then(function successCallback(response) {
                $scope.tyoFormMessage = response.data.message;
                var found = false;
                angular.forEach($scope.tehdytTyot, function (elem) {
                    if (elem[2] == response.data.tyo.nimi) {
                        elem[3] = parseInt(elem[3]) + parseInt(response.data.tyo.tunnit);
                        elem[4] = response.data.tyo.alennus;
                        found = true;
                    }
                });
                if (!found) {
                    var arr = new Array(5);
                    arr[2] = response.data.tyo.nimi;
                    arr[3] = response.data.tyo.tunnit;
                    arr[4] = response.data.tyo.alennus;
                    $scope.tehdytTyot.unshift(arr);
                }
                getTyosuoritus();
                getTyot();
            }, function errorCallback(response) {
                console.log("ERROR: " + response);
            });
        }
    };

    getTyosuoritus();
    getTarvikkeet();
    getTyot();
}]);
