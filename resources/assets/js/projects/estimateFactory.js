myApp.factory('estimateFactory', ['$http', function($http) {
    var estimateFactory = {};

    estimateFactory.getEstimateById = function(id) {
        return $http.get(baseUrl + 'api/get-estimate-by-id/' + id);
    }

    estimateFactory.updateEstimate = function(estimateData) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/update-estimate-by-id',
            method: 'POST',
            data: estimateData
        });
    }

    return estimateFactory;
}])
