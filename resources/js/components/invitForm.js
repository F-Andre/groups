import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class InvitForm extends Component {
  constructor(props) {
    super(props)
    this.state = {
      mailValue: '',
      emailsValue: '',
      mailClass: 'form-control',
      submitMailDisabled: true,
      submitDisabled: true,
      emailRegex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
      spinner: '',
    }
    this.handleChangeMail = this.handleChangeMail.bind(this);
    this.handleSubmitMail = this.handleSubmitMail.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleChangeMail(event) {
    if (this.state.emailRegex.test(event.target.value)) {
      this.setState({ mailValue: event.target.value, mailClass: 'form-control', submitMailDisabled: false })
    } else {
      this.setState({ mailValue: event.target.value, mailClass: 'form-control is-invalid', submitMailDisabled: true })
    }
  }

  handleSubmitMail(e) {
    e.preventDefault()
    this.setState({
      emailsValue: this.state.emailsValue += this.state.mailValue + ', ',
      mailValue: '',
      submitDisabled: false,
    })
  }

  handleSubmit() {
    this.setState({ spinner: <i class="fas fa-spinner fa-spin"></i> })
  }

  render() {

    const submitMailClass = this.state.submitMailDisabled ? 'btn btn-primary btn-sm ml-2 disabled' : 'btn btn-primary btn-sm ml-2'

    return (
      <div className="form-group">
        <form className="inline-form" id="addMailForm">
          <div className="form-group">
            <input form="addMailForm" type="email" name="mail" className="ml-2 mb-2" value={this.state.mailValue} max='80' placeholder="Entrez une adresse e-mail" onChange={this.handleChangeMail} />
            <button form="addMailForm" type="submit" onClick={this.handleSubmitMail} className={submitMailClass} disabled={this.state.submitMailDisabled}>Ajouter</button>
          </div>
        </form>
        <div className="form-group">
          <label htmlFor="emails">Liste des e-mails:</label>
          <div className="card">
            <div className="card-body">
            {this.state.emailsValue}
            </div>
          </div>
          <input form="invitMailForm" type="text" className="ml-2 form-control" name="emails" id="emails" placeholder="Aucune adresse" value={this.state.emailsValue} hidden readOnly />
        </div>
        <button form="invitMailForm" type="submit" onClick={this.handleSubmit} className="btn btn-success" disabled={this.state.submitDisabled}>Envoyer {this.state.spinner}</button>
      </div>
    )
  }
}

if (document.getElementById('invitForm')) {
  ReactDOM.render(<InvitForm />, document.getElementById('invitForm'))
}
