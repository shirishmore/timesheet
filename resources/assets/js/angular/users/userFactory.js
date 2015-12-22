myApp.factory('userFactory', ['$http', '$cookies',
    function($http, $cookies) {
        var userFactory = {};

        userFactory.logoutUser = function() {
            $cookies.remove('userObj');
            return $http.get(baseUrl + 'logout');
        }

        userFactory.getUserList = function() {
            return $http.get(baseUrl + 'api/get-user-list');
        }

        userFactory.getUserObj = function() {
            return $http.get(baseUrl + 'api/get-user_data');
        }

        userFactory.getUserListByRole = function(roleId) {
            /*Code for loading users by role id*/
        }

        return userFactory;
    }
]);
