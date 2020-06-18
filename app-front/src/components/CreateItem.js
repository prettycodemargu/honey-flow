import React from "react";

class CreateItem extends React.Component {

    state = {
        input : {}
    }

    updateInput(field, value) {
        let input = {...this.state.input};
        input[field] = value;
        this.setState({
            input : {...input}
        });
    }

    saveItem = () => {
        this.props.saveItem(this.state.input);
        let input = {};
        this.props.fields.map((field) => {
            input[field] = "";
        });
        this.setState({input : input});
    }

    render() {
        return (
          <div className="create-item">
              {this.props.fields.map((field) => {
                  return (
                      <input
                          key={field}
                          type="text"
                          onChange={(e) => this.updateInput(field, e.target.value)}
                          value={(typeof(this.state.input[field]) === 'undefined') ? "" : this.state.input[field]}
                      />
                  );
              })}
              <button
                  onClick={this.saveItem}
              >
                  &#10132;
              </button>
          </div>
        );
    }
}

export default CreateItem;