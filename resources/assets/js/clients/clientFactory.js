myApp.factory('clientFactory', ['$http', function($http) {
    var clientFactory = {};

    clientFactory.getClientList = function() {
        return $http.get(baseUrl + 'api/get-client-list');
    }

    return clientFactory;
}]);
