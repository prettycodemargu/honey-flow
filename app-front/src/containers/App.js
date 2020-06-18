import React from 'react';
import '../App.css';
import {BrowserRouter as Router, Switch, Route, Link} from "react-router-dom";
import MainPage from "./MainPage";
import PlanPage from "./PlanPage";
import CategoriesPage from "./CategoriesPage";
import SourcesPage from "./SourcesPage";


class App extends React.Component{

    state = {
        dashboardId : 1
    }

    render() {
        return (
            <Router>
                <div>
                    <nav>
                        <ul>
                            <li>
                                <Link to="/">Главная</Link>
                            </li>
                            <li>
                                <Link to={ "/categories/" + this.state.dashboardId }>Категории</Link>
                            </li>
                            <li>
                                <Link to={ "/sources/" + this.state.dashboardId }>Источники дохода</Link>
                            </li>
                        </ul>
                    </nav>

                    <Switch>
                        <Route path="/plan/:id">
                            <PlanPage />
                        </Route>
                        <Route path="/categories/:dashboard_id">
                            <CategoriesPage />
                        </Route>
                        <Route path="/sources/:dashboard_id">
                            <SourcesPage />
                        </Route>
                        <Route path="/">
                            <MainPage dashboardId={this.state.dashboardId}/>
                        </Route>
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
