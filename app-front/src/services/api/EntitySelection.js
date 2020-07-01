import * as Constants from "../../constants";

class EntitySelection {

    static getSelection(selectionName, id) {

        const requestOptions = {
            method : "GET",
            headers : {"Content-Type" : "application/json"}
        };

        return fetch(Constants.API_URL_ENTITY_SELECTION + '/' + selectionName + '?id=' + id,
            requestOptions)
            .then((response) => response.json())
            .then((response) => {
                return response;
            });
    }
}

export default EntitySelection;