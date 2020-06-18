import * as Constants from "../../constants.js";

class Entity {

     static add (entityName, data) {

        const requestOptions = {
            method : "POST",
            headers : {"Content-Type" : "application/json"},
            body : JSON.stringify(data)
        };

       return fetch(Constants.API_URL_ENTITY + '/' + entityName, requestOptions)
            .then((response) => response.json())
            .then((response) => {
                let id = 0;
                if (response.success) {
                    id = response.result_data.id;
                }
                return id;
            });
    }

    static deleteEntity (entityName, id) {
        const requestOptions = {
            method : "POST",
            headers : {"Content-Type" : "application/json"}
        };

        fetch(Constants.API_URL_ENTITY + '/' + entityName + '/' + id + '?method=delete', requestOptions)
            .then((response) => response.json())
            .then((response) => {
                if (response.success) {
                    return true;
                }
            });

        return false;
    }

}

export default Entity;