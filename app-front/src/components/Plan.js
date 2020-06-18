import React from 'react';
import { Container, Row, Col } from 'reactstrap';
import { Link } from "react-router-dom";


function Plan(props) {

    function renderTranches() {
        if (props.tranches.rows) {
            return (
                props.tranches.rows.map(row => {
                        return (
                            <Row key={row.id}>
                                <Col>{row.category_name}</Col><Col>{row.amount}</Col>
                            </Row>
                        )
                    }
                )
            )
        }
    }

    if (props.plan) {
        return (
            <div className="billet">
                <h3 className="billet-title">План</h3>
                <div className="small"><Link to={'/plan/' + props.plan.id}>ред.</Link></div>
                <Container>
                    {renderTranches()}
                    <Row className="total" key='plan-total'>
                        <Col>Итого</Col>
                        <Col>
                            {props.tranches.total ? props.tranches.total.toFixed(2) : '0.00'}
                        </Col>
                    </Row>
                </Container>
            </div>
        );
    } else {
        return (
            <div>
                <h3>План</h3>
                Нет плана на текущий период
            </div>
        );
    }
}

export default Plan;