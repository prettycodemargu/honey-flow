import {Col, Container, Row} from "reactstrap";
import Plan from "../components/Plan";
import Storages from "../components/Storages";
import Spendings from "../components/Spendings";
import React from "react";
import EntitySelection from "../services/api/EntitySelection";


class MainPage extends React.Component {

    state = {
        plan : [],
        tranches : [],
        spendingsGroups : [],
        storages : [],
        currencies : []
    };

    componentDidMount() {
        EntitySelection.getSelection('Dashboard', this.props.dashboardId)
            .then((result) => {
                console.log('result', result);
                this.setState({
                    plan: result.plan,
                    tranches : result.tranches,
                    spendingsGroups : result.spendings_groups,
                    storages : result.storages,
                    currencies : result.currencies
                });
            });
    }

    render() {
        return (
            <Container>
                <Row>
                    <Col>
                        <Plan
                            plan={this.state.plan}
                            tranches={this.state.tranches}
                            currencies={this.state.currencies}
                        />
                        <Storages
                            storages={this.state.storages}
                            currencies={this.state.currencies}
                        />
                    </Col>
                    <Col>
                        {this.state.spendingsGroups.map(spendingGroup => {
                            return (
                                <Spendings
                                    key={spendingGroup.category_id}
                                    dashboardId={this.props.dashboardId}
                                    spendingGroup={spendingGroup}
                                    currencies={this.state.currencies}
                                />
                            );
                        })}
                    </Col>
                </Row>
            </Container>
        );
    }
}

export default MainPage;