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

function GroupDesc(props) {
  return (
    <input
      type="text"
      name="description"
      id="description"
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
      descValue: '',
      imgSrc: avatar,
      imgSize: 0,
      avatarDeleted: false,
      spinner: '',
      nameClass: 'form-control is-invalid',
      descClass: 'form-control is-invalid',
      disabledState: true,
    }
    this.handleChangeName = this.handleChangeName.bind(this);
    this.handleChangeDesc = this.handleChangeDesc.bind(this);
    this.handleChangeAvatar = this.handleChangeAvatar.bind( this );
    this.handleDeleteAvatar = this.handleDeleteAvatar.bind( this );
    this.fileInput = React.createRef();
  }

  handleChangeName(event) {
    let regexp = /[\s]{1,}/g
    if (event.target.value.length <= 6 || regexp.test(event.target.value)) {
      this.setState({ nameValue: event.target.value, nameClass: 'form-control is-invalid' })
    } else {
      this.setState({ nameValue: event.target.value, nameClass: 'form-control', disabledState: false })
    }
  }

  handleChangeDesc(event) {
    if (event.target.value.length <= 6) {
      this.setState({ descValue: event.target.value, descClass: 'form-control is-invalid' })
    } else {
      this.setState({ descValue: event.target.value, descClass: 'form-control', disabledState: false })
    }
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
      } );
    };
    reader.readAsDataURL( file );
  }

  handleDeleteAvatar () {
    this.setState( {
      imgSrc: defaultAvatar,
      avatarDeleted: true,
    } )
  }

  render() {
    const imageSizeMax = 10485760
    const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'
    const disabledState = this.state.nameClass == 'form-control is-invalid' ? true : this.state.descClass == 'form-control is-invalid' ? true : false
    const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"
    const disableDelete = this.state.imgSrc != '/storage/default/default-group.svg' ? "btn btn-outline-danger btn-sm" : "btn btn-outline-danger btn-sm disabled"

    const aStyle = {
      backgroundImage: 'url(' + this.state.imgSrc + ')',
    };

    return (
      <div className="form-group">
        <div className="form-group">
          <label htmlFor="name">Nom du groupe:</label>
          <GroupName value={this.state.nameValue} onChange={this.handleChangeName} className={this.state.nameClass} />
          <div className="invalid-feedback">Choisissez un nom d'au moins 6 caractères sans espaces (-_. autorisés).</div>
        </div>
        <div className="form-group">
          <label htmlFor="desc">Description du groupe:</label>
          <GroupDesc value={this.state.descValue} onChange={this.handleChangeDesc} className={this.state.descClass} />
          <div className="invalid-feedback">Choisissez une description d'au moins 6 caractères.</div>
        </div>
        <div id="divAvatar" className="form-group">
          <p>Avatar:</p>
          <div className="form-group">
            <label id="avatarLabel" htmlFor="avatar" className="btn btn-outline-secondary avatar" style={aStyle}></label>
            <br />
            <a id="btnDeleteAvatar" className={disableDelete} onClick={this.handleDeleteAvatar}>Effacer l'image</a>
            <input id="avatar" name="avatar" className={imageClass} type="file" accept=".JPG, .PNG, .GIF" ref={this.fileInput} onChange={this.handleChangeAvatar} />
            <div className="invalid-feedback">L'image doit être aux formats jpg, png ou gif et avoir une taille max de 10Mo.</div>
          </div>
          <input id="avatarDeleted" type="text" className="disabled" name="avatarDeleted" value={this.state.avatarDeleted} />
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
