import React from "react";
import {Button, Container, Col, Row} from "reactstrap";

const ListItems = (props) => {
    if (!props.items || !props.items.length) {
        return ('');
    }

    return (
        <Container className="container-list-page">
            {props.items.map((item) => {
                return (
                    <Row key={item.id}>
                        {props.fields.map((field) => {
                            return (
                                <Col
                                    className="col-list-page"
                                    key={item[field]}>
                                    {item[field]}
                                </Col>
                            )
                        })}
                        <Col className="col-2">
                            <Button onClick={(e) => props.deleteMethod(item)}>
                                x
                            </Button>
                        </Col>
                    </Row>
                )
            })}
        </Container>
    );
}

export default ListItems;



