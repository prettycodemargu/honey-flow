import React from 'react';
import {Col, Container, Row} from "reactstrap";


function Storages(props) {

    function renderStorage() {
        if (props.storages.rows)
        return (props.storages.rows.map(row => {
            return (
                <Row key={row.id}>
                    <Col>{row.storage_name}</Col><Col>{row.amount}</Col>
                </Row>
            );
        }))
    }

    return(
        <div className="billet">
            <h3 className="billet-title">Кошельки</h3>
            <Container>
                {renderStorage()}
                <Row className="total" key="storages-total">
                    <Col>Итого</Col>
                    <Col>
                        {props.storages.total ? props.storages.total.toFixed(2) : '0.00'}
                    </Col>
                </Row>
            </Container>
        </div>
    );

}

export default Storages;