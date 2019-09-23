import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function GroupName(props) {
  return (
    <input
      type="text"
      name="name"
      id="name"
      value={props.value}
      onChange={props.onChange}
      className={props.className}
    />
  );
}

export default class GroupForm extends Component {
  constructor(props) {
    super(props)
    this.state = {
      nameValue: '',
      spinner: '',
      nameClass: 'form-control is-invalid',
    }
    this.handleChangeName = this.handleChangeName.bind(this);
  }

  handleChangeName(event) {
    let regexp = /[\s]{1,}/g
    if (event.target.value.length <= 6 || regexp.test(event.target.value)) {
      console.log('zehzuierg')
      this.setState({ nameValue: event.target.value, nameClass: 'form-control is-invalid' })
    } else {
      this.setState({ nameValue: event.target.value, nameClass: 'form-control' })
    }
    
  }

  render() {
    const disabledState = this.state.nameClass == 'form-control is-invalid' ? true : false
    const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"

    return (
      <div className="form-group">
        <div className="form-group">
          <label htmlFor="titre">Nom du groupe:</label>
          <GroupName value={this.state.nameValue} onChange={this.handleChangeName} className={this.state.nameClass} />
          <div className="invalid-feedback">Choisissez un nom d'au moins 6 caractères sans espaces.</div>
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Créer le groupe</button>
      </div>
    )
  }
}

if (document.getElementById('groupForm')) {
  ReactDOM.render(<GroupForm />, document.getElementById('groupForm'));
}
