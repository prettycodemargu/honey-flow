import * as Constants from "../../constants.js";

class EntityCategory {
    static addCategoryToDashboard(dashboardId, data) {
        const requestOptions = {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        };

        return fetch(Constants.API_URL_ENTITY + '/Category' +
            '?dashboard_id=' + dashboardId, requestOptions)
            .then((response) => response.json())
            .then((response) => {
                return response.id;
            });
    }

    static deleteCategoryFromDashboard (dashboard_id, category_id) {
        const requestOptions = {
            method : "DELETE",
            headers : {"Content-Type" : "application/json"}
        };

        return fetch(Constants.API_URL_ENTITY + '/Category/' + category_id +
            '?dashboard_id=' + dashboard_id,
            requestOptions);
    }
}

export default EntityCategory;