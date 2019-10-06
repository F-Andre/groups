import React, { Component } from 'react';
import ReactDOM from 'react-dom';

function CommentText(props) {
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
  constructor(props) {
    super(props)
    this.state = {
      commentValue: '',
      spinner: '',
    }
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount() {
    window.addEventListener('message', (e) => {
      if (typeof e.data == "string") {
        const text = e.data;
        this.setState({
          commentValue: text,
          modified: true
        })
      }
    })
  }

  handleSubmit() {
    this.setState({ spinner: <i class="fas fa-spinner fa-spin"></i> })
  }

  render() {
    const disabledState = this.state.commentValue.length <= 3 ? true : false
    const submitClass = !disabledState ? "btn btn-primary btn-sm float-right" : "btn btn-secondary btn-sm float-right disabled"
    const contenuClass = this.state.commentValue.length > 10 ? 'form-control' : this.state.commentValue.length == 0 ? 'form-control' : 'form-control is-invalid'

    return (
      <div className="form-group">
        <div className="form-group mt-2">
          <textarea className={contenuClass} name="comment" id="comment" value={this.state.commentValue} hidden />
          <iframe id="comment_iframe" className="commentIframe" src="/comment_iframe.html"></iframe>
        </div>
        <button type="submit" onClick={this.handleSubmit} className={submitClass} disabled={disabledState}>Envoyer {this.state.spinner}</button>
      </div>
    )
  }
}

if (document.getElementsByClassName('commentForm')) {
  const form = document.getElementsByClassName('commentForm');
  for (let i in form) {
    form[i].nodeType == 1 ? ReactDOM.render(<CommentForm />, form[i]) : '';
  }
}
