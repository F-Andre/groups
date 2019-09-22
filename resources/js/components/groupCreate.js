import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function GroupName ( props ) {
  const titleClass = props.value.length <= 6 ? 'form-control is-invalid' : 'form-control'
  return (
    <input
      type="text"
      name="name"
      id="name"
      value={props.value}
      onChange={props.onChange}
      className={titleClass}
    />
  );
}

export default class GroupForm extends Component {
  constructor( props ) {
    super( props )
    this.state = {
      nameValue: '',
      spinner: '',
    }
    this.handleChangeName = this.handleChangeName.bind( this );
  }

  handleChangeName( event ) {
    this.setState( { nameValue: event.target.value } )
  }

  render () {
    const disabledState = this.state.nameValue.length <= 6 ? true : false
    const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"

    return (
      <div className="form-group">
        <div className="form-group">
          <label htmlFor="titre">Nom du groupe:</label>
          <GroupName value={this.state.nameValue} onChange={this.handleChangeName} />
          <div className="invalid-feedback">Choisissez un nom d'au moins 6 caractères.</div>
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Créer le groupe</button>
      </div>
    )
  }
}

if ( document.getElementById( 'groupForm' ) ) {
  ReactDOM.render( <GroupForm />, document.getElementById( 'groupForm' ) );
}
