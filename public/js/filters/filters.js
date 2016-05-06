angular.module('tikoApp').filter('formatEmptyItems1d', function() {
    return function(items) {
        var i = 0;
        angular.forEach(items, function(col) {
            if (col == null || col == undefined) {
                items[i] = '-';
            }
            ++i;
        });
        return items;
    }
});

angular.module('tikoApp').filter('formatEmptyItems2d', function() {
    return function(items) {
        var i = 0;
        angular.forEach(items, function(row) {
            var j = 0;
            angular.forEach(row, function(col) {
                if (col == null || col == undefined) {
                    items[i][j] = '-';
                }
                ++j;
            });
            ++i;
        });
        return items;
    }
});