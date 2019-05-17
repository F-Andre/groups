import React, { Component } from 'react';
import ReactDOM from 'react-dom';

function CommentText ( props ) {
  const contenuClass = 'form-control'
  return (
    <textarea
      name="comment"
      id="comment"
      value={props.value}
      onChange={props.onChange}
      className={contenuClass}
      hidden
    />
  );
}

export default class CommentForm extends Component {
  constructor( props ) {
    super( props )
    this.state = {
      commentValue: '',
      spinner: '',
    }
    this.handleChangeText = this.handleChangeText.bind( this );
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount () {
    window.addEventListener( 'message', ( e ) => {
      this.setState( {
        commentValue: e.data,
        modified: true
      } )
    } )
  }

  handleChangeText ( event ) {
    this.setState( { commentValue: event.target.value } )
  }

  handleSubmit ( event ) {
      this.setState({ spinner: <i class="fas fa-spinner fa-spin"></i>})
  }

  render () {
    const disabledState = this.state.commentValue.length <= 3 ? true : false
    const submitClass = !disabledState ? "btn btn-primary float-right" : "btn btn-secondary float-right disabled"

    return (
      <div className="form-group">
        <div className="form-group mt-2">
            <label htmlFor="comment">Ajouter un commentaire</label>
            <CommentText value={this.state.commentValue} onChange={this.handleChangeText} />
            <iframe id="comment_iframe" className="commentIframe" src="/comment_iframe.html"></iframe>
        </div>
        <button type="submit" onClick={this.handleSubmit} className={submitClass} disabled={disabledState}>Envoyer {this.state.spinner}</button>
      </div>
    )
  }
}

if ( document.getElementsByClassName( 'commentForm' ) ) {
  const form = document.getElementsByClassName( 'commentForm' );
  for (let i in form) {
    form[i].nodeType == 1 ? ReactDOM.render( <CommentForm />, form[i] ) : '';
  }
}
