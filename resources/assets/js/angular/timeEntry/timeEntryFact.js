myApp.factory('timeEntry', ['$http', function($http) {
    var timeEntry = {};

    timeEntry.getEntries = function() {
        return $http.get(baseUrl + 'api/time-report');
    }

    /*timeEntry.getUserList = function() {
        return $http.get(baseUrl + 'api/get-user-list');
    }*/

    timeEntry.getSearchResult = function(filterParams) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/time-report-filter',
            method: 'POST',
            data: filterParams
        });
    }

    timeEntry.getTimeSheetEntryByDate = function() {
        return $http.get(baseUrl + 'api/get-timeentry-by-date');
    }

    timeEntry.getEntriesForEstimate = function(estimateId) {
        return $http.get(baseUrl + 'api/get-timeentry-for-estimate/' + estimateId);
    }

    timeEntry.getBackDateEntries = function() {
        return $http.get(baseUrl + 'api/get-backdate-entries');
    }

    timeEntry.getBackDateEntriesById = function(id) {
        return $http.get(baseUrl + 'api/get-backdate-entry/' + id);
    }

    timeEntry.saveBackDateEntry = function(entryData) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/allow-backdate-entry',
            method: 'POST',
            data: entryData
        });
    }

    timeEntry.getRequestBackDateEntries = function() {
        return $http.get(baseUrl + 'api/get-request-backdate-entries');
    }

    timeEntry.getRequestBackDateEntriesById = function(id) {
        return $http.get(baseUrl + 'api/get-request-backdate-entries-by-id/' + id);
    }

    timeEntry.saveRequestBackDateEntry = function(entryData) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/allow-request-backdate-entry',
            method: 'POST',
            data: entryData
        });
    }
    timeEntry.deleteBackDate = function(id) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/delete-backdate',
            method: 'POST',
            data: {
                id: id
            }
        });
    }

    timeEntry.deleteBackDateRequest = function(id) {
        return $http({
            headers: {
                'Content-Type': 'application/json'
            },
            url: baseUrl + 'api/delete-request-backdate',
            method: 'POST',
            data: {
                id: id
            }
        });
    }

    return timeEntry;
}]);
