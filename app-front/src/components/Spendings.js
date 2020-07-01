import React from 'react';
import {Button, Container, Row, Col} from "reactstrap";
import { Accordion, AccordionItem, AccordionItemButton, AccordionItemHeading, AccordionItemPanel } from "react-accessible-accordion";


class Spendings extends React.Component{
    constructor(props) {
        super(props);

        this.state = {
            name : "",
            amount : "",
            newSpendings : [],
            spendingGroup : {}
        }
    }

    updateInput(key, value) {
        this.setState({
           [key] : value
        });
    }

    /**
     *  todo в спец компонент
     *
     * @param key
     * @param value
     */
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

    defaultStorage = 1;

    saveSpending() {
        let spending = {
            dashboard_id : this.props.dashboardId,
            storage_id : this.defaultStorage,
            category_id : this.props.spendingGroup.category_id,
            amount : this.state.amount,
            spending_name : this.state.name
        };

        const request = {
            method : 'POST',
            headers :  { 'Content-type' : 'application/json' },
            body: JSON.stringify(spending)
        };

        let newSpending;

        fetch('http://honey-flow.local/api/Entity/Spending', request)
            .then(response => response.json())
            .then(response => {
                newSpending = {
                    name: this.state.name.slice(),
                    amount: this.state.amount.slice(),
                    id: response.result_data.id
                };

                this.setState({
                    name : "",
                    amount : "",
                    newSpendings : [newSpending, ...this.state.newSpendings]
                })
            });
    }


    componentDidMount() {
        this.setState({
            spendingGroup : {...this.props.spendingGroup}
        });
    }

    render() {
        return (
            <div className="spending-card">
                <div className="card-title" key={this.props.spendingGroup.category_id}>
                    {this.props.spendingGroup.category_name}
                </div>
                <div className="card-body">
                    <form>
                        <input
                            type="text"
                            className="spending-input-name"
                            placeholder="Название"
                            value={this.state.name}
                            onChange={(e) => {this.updateInput("name", e.target.value)}}
                        />
                        <input
                            type="text"
                            className="spending-input-sum"
                            placeholder="Сумма"
                            value={this.state.amount}
                            onChange={(e) => {this.checkAndUpdateMoney("amount", e.target.value)}}
                            onBlur={(e) => {this.correctAndUpdateMoney("amount", e.target.value)}}
                        />
                        <Button
                            onClick={() => this.saveSpending()}
                        >
                            &#10132;
                        </Button>
                    </form>
                    <Container>
                        {this.renderNewSpendings()}
                    </Container>

                    <Accordion allowZeroExpanded={true}>
                        <AccordionItem>
                            <AccordionItemHeading>
                                <AccordionItemButton>
                                    ...
                                </AccordionItemButton>
                            </AccordionItemHeading>
                            <AccordionItemPanel>
                                <Container>
                                    {this.renderInitialSpendings()}
                                </Container>
                            </AccordionItemPanel>
                        </AccordionItem>
                    </Accordion>
                </div>
            </div>
        );
    };

    renderInitialSpendings() {
        let rows;
        rows = this.state.spendingGroup.rows ?
            this.state.spendingGroup.rows.slice() :
            this.props.spendingGroup.rows.slice();
        return (
            rows.map(row => {return (
                    <Row>
                        <Col>
                            {row.spending_name}
                        </Col>
                        <Col>
                            {row.amount}
                        </Col>
                        <Col>
                            <Button onClick={(e) => this.deleteInitialSpendingVisual(row.id)}>
                                x
                            </Button>
                        </Col>
                    </Row>
                );
            })
        );
    }

    renderNewSpendings() {
        return (
            this.state.newSpendings.map(row => {return (
                    <Row>
                        <Col>
                            {row.name}
                        </Col>
                        <Col>
                            {parseFloat(row.amount).toFixed(2)}
                        </Col>
                        <Col>
                            <Button onClick={(e) => this.deleteNewSpendingVisual(row.id)}>
                                x
                            </Button>
                        </Col>
                    </Row>
                );
            })
        )
    }

    deleteInitialSpendingVisual(spendingId) {
        let spendingGroup = {...this.state.spendingGroup};

        let index = spendingGroup.rows.findIndex((row) => {
           return row.id === spendingId;
        });

        spendingGroup.rows.splice(index, 1);

        this.setState({
            spendingGroup : {...spendingGroup}
        });

        this.deleteSpending(spendingId);
    }

    deleteNewSpendingVisual(spendingId) {
        let newSpendings = this.state.newSpendings.splice();

        let index = newSpendings.findIndex((spending) => {
            return spending.id === spendingId;
        })

        newSpendings.splice(index, 1);

        this.setState({
           newSpendings : newSpendings
        });
        this.deleteSpending(spendingId);
    }

    deleteSpending(spendingId) {
        let requestOptions = {
          method : 'POST',
          headers : { 'Content-Type' : 'application/json'}
        };
        fetch('http://honey-flow.local/api/Entity/Spending/' + spendingId + '?method=delete', requestOptions);
    }
}

export default Spendings;