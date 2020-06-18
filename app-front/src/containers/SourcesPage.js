import React from 'react';
import {Button, Card, CardBody, CardTitle, Col, Container, Row} from "reactstrap";
import {withRouter} from "react-router";
import ListItems from '../components/ListItems';
import CreateItem from "../components/CreateItem";
import Entity from "../services/api/Entity";
import EntitySelection from "../services/api/EntitySelection";


class SourcesPage extends React.Component {

    state = {
        sources : []
    }

    componentDidMount() {

        EntitySelection.getSelection('Sources', this.props.match.params.dashboard_id)
            .then((result) => {
                this.setState({
                    sources: result.sources
                });
            });
    }

    saveSource = (newSource) => {
        newSource['dashboard_id'] = this.props.match.params.dashboard_id;

        Entity.add('Source', newSource)
            .then((id) => {
                newSource.id = id;
            });

        this.setState({
            sources: [newSource, ...this.state.sources]
        });
    }

    render() {
        return (
            <Card>
                <CardTitle className="list-page-title">Источники дохода</CardTitle>
                <CardBody>
                    <CreateItem fields={['source_name']} saveItem={this.saveSource} />
                    <ListItems
                        items={this.state.sources}
                        fields={['source_name']}
                        deleteMethod={this.deleteSource}
                    />
                </CardBody>
            </Card>
        );
    }

    deleteSource = (sourceToDelete) => {
        if (sourceToDelete.in_use) {
            alert('Источник дохода используется');
            return;
        }

        let sources = this.state.sources.slice();

        let index = sources.findIndex((source) => {
            return source.id === sourceToDelete.id;
        });

        sources.splice(index, 1);

        this.setState({
            sources : [...sources]
        });

        Entity.deleteEntity('Source', sourceToDelete.id);
    }
}

export default withRouter(SourcesPage);