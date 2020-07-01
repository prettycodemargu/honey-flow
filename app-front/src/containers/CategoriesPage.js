import React from 'react';
import {Button, Card, CardBody, CardTitle, Col, Container, Row} from "reactstrap";
import {withRouter} from "react-router";
import ListItems from "../components/ListItems";
import CreateItem from "../components/CreateItem";
import EntitySelection from "../services/api/EntitySelection";
import EntityCategory from "../services/api/EntityCategory";


class CategoriesPage extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            newCategoryName : "",
            newCategoryDescription : "",
            newCategories : [],
            categories : []
        };

        EntitySelection.getSelection('Categories', this.props.match.params.dashboard_id)
            .then((result) => {
                this.setState({
                    categories: result.categories
                });
            });
    }

    saveCategory = (category) => {
        EntityCategory.addCategoryToDashboard(this.props.match.params.dashboard_id, category)
            .then((response) => {
                category.id = response;
            });

        this.setState({
            categories: [category, ...this.state.categories]
        });
    }

    render() {
        return (
            <Card>
                <CardTitle className="list-page-title">Категории</CardTitle>
                <CardBody>
                    <CreateItem fields={['category_name','description']} saveItem={this.saveCategory} />
                    <ListItems
                        items={this.state.categories}
                        fields={['category_name']}
                        deleteMethod={this.deleteCategory}
                    />
                </CardBody>
            </Card>
        );
    }

    deleteCategory = (categoryToDelete) => {
        if (categoryToDelete.in_use) {
            alert('Категория используется');
            return;
        }

        let categories = this.state.categories.slice();

        let index = categories.findIndex((category) => {
            return category.id === categoryToDelete.id;
        });

        categories.splice(index, 1);

        this.setState({
            categories : [...categories]
        });

        EntityCategory.deleteCategoryFromDashboard(this.props.match.params.dashboard_id, categoryToDelete.id);
    }
}

export default withRouter(CategoriesPage);