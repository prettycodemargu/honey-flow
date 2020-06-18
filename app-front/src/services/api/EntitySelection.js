import * as Constants from "../../constants";

class EntitySelection {

    static getSelection(selectionName, id) {

        const requestOptions = {
            method : "GET",
            headers : {"Content-Type" : "application/json"}
        };

        return fetch(Constants.API_URL_ENTITY_SELECTION + '/' + selectionName + '?method=getSelection&id=' + id,
            requestOptions)
            .then((response) => response.json())
            .then((response) => {
                let result = [];
                if (response.success) {
                    result = response.result_data;
                }
                return result;
            });
    }
}

export default EntitySelection;