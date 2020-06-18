import * as Constants from "../../constants.js";

class EntityCategory {

    static addCategoryToDashboard(dashboardId, data) {

        const requestOptions = {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        };

        return fetch(Constants.API_URL_ENTITY + '/Category' +
            '?method=addCategoryToDashboard&dashboard_id=' + dashboardId, requestOptions)
            .then((response) => response.json())
            .then((response) => {
                let id = 0;
                if (response.success) {
                    id = response.result_data.id;
                }
                return id;
            });
    }

    static deleteCategoryFromDashboard (dashboard_id, category_id) {
        const requestOptions = {
            method : "POST",
            headers : {"Content-Type" : "application/json"}
        };

        fetch(Constants.API_URL_ENTITY + '/Category/' + category_id +
            '?method=deleteCategoryFromDashboard&dashboard_id=' + dashboard_id,
            requestOptions)
            .then((response) => response.json())
            .then((response) => {
                return !!response.success;
            });
    }
}

export default EntityCategory;