import * as Constants from "../../constants.js";

class Entity {

     static add(entityName, data) {

        const requestOptions = {
            method : "POST",
            headers : {"Content-Type" : "application/json"},
            body : JSON.stringify(data)
        };

       return fetch(Constants.API_URL_ENTITY + '/' + entityName, requestOptions)
            .then((response) => response.json())
            .then((response) => {
                return response.id;
            });
    }

    static deleteEntity(entityName, id) {
        const requestOptions = {
            method : "DELETE",
            headers : {"Content-Type" : "application/json"}
        };

        return fetch(Constants.API_URL_ENTITY + '/' + entityName + '/' + id, requestOptions);
    }

}

export default Entity;