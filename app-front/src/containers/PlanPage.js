import React from "react";
import {Link, withRouter} from "react-router-dom";
import {Container, Col, Row, CardBody, CardTitle} from "reactstrap";
import { Card, Button } from "reactstrap";
import Entity from '../services/api/Entity.js';
import EntitySelection from "../services/api/EntitySelection";

class PlanPage extends React.Component {

    state = {
        plan_id : "",
        plan : [],
        tranches : [],
        categoriesAvailable : [],
        amount : "",
        categoryId : ""
    };

    addTranche() {
        let tranche = {
            "plan_id" : this.state.plan_id,
            "dashboard_id" : this.state.plan.dashboard_id,
            "category_id" : this.state.categoryId,
            "amount" : this.state.amount,
            "currency_digital_code" : 643
        };

        Entity.add('Tranche', tranche);

        let categoriesAvailable = this.state.categoriesAvailable.slice();
        let index = categoriesAvailable.findIndex((item) => {
            return item.id === tranche.category_id;
        });

        tranche.category_name = categoriesAvailable[index].category_name;

        categoriesAvailable.splice(index, 1);

        this.setState({
            amount : "",
            categoryId : "",
            categoriesAvailable : categoriesAvailable,
            tranches : [tranche, ...this.state.tranches]
        });
    }

    updateInput(key, value) {
        this.setState({
            [key] : value
        });
    }

    checkAndUpdateMoney(key, value) {
        if (!value) {
            value = "0";
        }
        let money = value.replace(/[^0-9.]+/g, '');
        this.updateInput(key, money);
    }

    correctAndUpdateMoney(key, value) {
        if (!value) {
            value = "0";
        }
        let money = parseFloat(value);
        money = money.toFixed(2);
        this.updateInput(key, money);
    }

    componentDidMount() {
        EntitySelection.getSelection('Plan', this.props.match.params.id)
            .then((result) => {
                this.setState({
                    plan : result.plan,
                    plan_id : this.props.match.params.id,
                    tranches : result.tranches,
                    categoriesAvailable : result.categories_available
                });
            });
    }

    render() {
        return (
            <Card>
                <CardTitle className="list-page-title">План</CardTitle>
                <CardBody>
                    <Container>
                        <Row>
                            <Col>
                                <div className="form-element">
                                    <select
                                        onChange={(e) => {this.updateInput('categoryId', e.target.value)}}
                                    >
                                        <option disabled selected>--</option>
                                        {this.state.categoriesAvailable.map(item => {
                                            return(
                                                <option value={item.id}>
                                                    {item.category_name}
                                                </option>
                                            );
                                        })}
                                    </select>
                                </div>
                                <div className="form-element">
                                    <input
                                        type="text"
                                        placeholder="Введите сумму"
                                        value={this.state.amount}
                                        onChange={(e) => {this.checkAndUpdateMoney(
                                            "amount",
                                            e.target.value
                                        )}}
                                        onBlur={(e) => {this.correctAndUpdateMoney(
                                            "amount",
                                            e.target.value
                                        )}}
                                    />
                                </div>
                                <div className="form-element">
                                    <Button
                                        id='send-button'
                                        disabled={!this.state.categoryId || !this.state.amount || this.state.amount === '0.00'}
                                        onClick={() => this.addTranche()}
                                    >
                                        &#10132;
                                    </Button>
                                </div>
                            </Col>
                        </Row>
                    </Container>
                    <Container className="container-list-page">
                        { this.state.tranches.map(tranche => {
                             return (
                                 <Row key={tranche.id}>
                                     <Col className="col-list-page ">
                                        {tranche.category_name}
                                     </Col>
                                     <Col className="col-list-page ">
                                         {tranche.amount}
                                     </Col>
                                     <Col className="col-2">
                                         <Button onClick={(e) => this.deleteTranche(tranche)}>
                                         x
                                         </Button>
                                     </Col>
                                 </Row>
                             );
                        })}
                    </Container>
                </CardBody>
            </Card>
        );
    }

    deleteTranche(trancheToDelete) {
        if (trancheToDelete.has_spendings) {
            alert('Нельзя удалить категорию, по которой уже записаны траты. Удалите траты.');
            return;
        }

        let tranches = this.state.tranches.slice();

        let index = tranches.findIndex((tranche) => {
            return tranche.id === trancheToDelete.id;
        });

        tranches.splice(index, 1);

        this.setState({
           tranches : [...tranches]
        });

        Entity.deleteEntity('Tranche', trancheToDelete.id);
    }
}

export default withRouter(PlanPage);