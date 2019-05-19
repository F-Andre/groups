import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function Name ( props ) {
  return (
    <input
      type="text"
      className="form-control col-lg-6"
      name="name"
      id="name"
      value={props.value}
      onChange={props.onChange}
    />
  );
}

function Email ( props ) {
  return (
    <input
      type="email"
      className="form-control col-lg-6"
      name="email"
      id="email"
      value={props.value}
      onChange={props.onChange}
    />
  );
}

function Notifs ( props ) {
  return (
    <input
      type="checkbox"
      value={props.value}
      checked={props.value}
      onChange={props.onChange}
    />
  );
}

export default class UserEditForm extends Component {
  constructor( props ) {
    super( props )
    notifs == 'false' ? notifs = false : notifs = true;
    this.state = {
      nameValue: name,
      emailValue: email,
      notifsValue: notifs,
      imgSrc: avatar,
      imgSize: 0,
      modified: false,
      avatarDeleted: '',
    }
    this.handleChangeName = this.handleChangeName.bind( this );
    this.handleChangeEmail = this.handleChangeEmail.bind( this );
    this.handleChangeAvatar = this.handleChangeAvatar.bind( this );
    this.handleChangeNotifs = this.handleChangeNotifs.bind( this );
    this.handleDeleteAvatar = this.handleDeleteAvatar.bind( this );
    this.fileInput = React.createRef();
  }

  handleChangeName ( event ) {
    this.setState( {
      nameValue: event.target.value,
      modified: true
    } )
  }

  handleChangeEmail ( event ) {
    this.setState( {
      emailValue: event.target.value,
      modified: true
    } )
  }

  handleChangeNotifs ( event ) {
    this.setState( {
      notifsValue: event.target.checked,
      modified: true
    } )
  }

  handleChangeAvatar () {
    const reader = new FileReader();
    let file = this.fileInput.current.files[0];
    let fileSrc = '', fileSize = file.size;
    reader.onload = ( e ) => {
      fileSrc = e.target.result;
      this.setState( {
        imgSrc: fileSrc,
        imgSize: fileSize,
        modified: true
      } );
    };
    reader.readAsDataURL( file );
  }

  handleDeleteAvatar () {
    this.setState( {
      imgSrc: defaultAvatar,
      modified: true,
      imageDeleted: true,
    } )
  }

  render () {
    const imageSizeMax = 4718592
    const disabledState = !this.state.modified ? true : this.state.imgSize > imageSizeMax ? true : false
    const submitClass = !disabledState ? "btn btn-success btn-lg" : "btn btn-success btn-lg disabled"
    const disableDelete = this.state.imgSrc != '/storage/default/default_avatar.png' ? "btn btn-outline-danger btn-sm" : "btn btn-outline-danger btn-sm disabled"
    const aStyle = {
      backgroundImage: 'url(' + this.state.imgSrc + ')',
    };
    const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'

    return (
      <div>
        <div className="form-group">
          <label htmlFor="nom">Nom:</label>
          <Name value={this.state.nameValue} onChange={this.handleChangeName} />
        </div>
        <div className="form-group">
          <label htmlFor="titre">Adresse e-mail:</label>
          <Email value={this.state.emailValue} onChange={this.handleChangeEmail} />
        </div>
        <div className="form-group">
          <label htmlFor='notif-switch'>Recevoir les notifications: </label>
          <label className="switch ml-3">
            <Notifs name="notif-switch" value={this.state.notifsValue} onChange={this.handleChangeNotifs} />
            <span className="slider round"></span>
          </label>
          <input name="notifs" type="text" value={this.state.notifsValue} hidden />
        </div>
        <div id="divAvatar" className="form-group">
          <p>Avatar:</p>
          <div className="form-group">
            <label id="avatarLabel" htmlFor="avatar" className="btn btn-outline-secondary avatar" style={aStyle}></label>
            <br />
            <a id="btnDeleteAvatar" className={disableDelete} onClick={this.handleDeleteAvatar}>Effacer l'image</a>
            <input id="avatar" name="avatar" className={imageClass} type="file" accept=".JPG, .PNG, .GIF" ref={this.fileInput} onChange={this.handleChangeAvatar} />
            <div className="invalid-feedback">L'image doit être aux formats jpg, png ou gif et avoir une taille max de 4.5Mo.</div>
          </div>
          <input id="avatarDeleted" type="text" className="disabled" name="avatarDeleted" value={this.state.imageDeleted} />
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Appliquer les modifications</button>
      </div>
    )
  }
}

if ( document.getElementById( 'userEditForm' ) ) {
  ReactDOM.render( <UserEditForm />, document.getElementById( 'userEditForm' ) );
}
