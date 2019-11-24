import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function Name (props) {
  return (
    <input
      type="text"
      className="form-control"
      name="name"
      id="name"
      value={props.value}
      readOnly
    />
  );
}

function Desc (props) {
  return (
    <input
      type="text"
      className="form-control"
      name="description"
      id="description"
      value={props.value}
      onChange={props.onChange}
      placeholder='Ajouter une petite description du groupe, ou pas'
    />
  );
}

export default class GroupEditForm extends Component {
  constructor (props) {
    super(props)
    this.state = {
      nameValue: name,
      descValue: desc,
      imgSrc: avatar,
      imgSize: 0,
      modified: false,
      avatarDeleted: '',
      masked: Boolean(masked),
    }
    this.handleChangeDesc = this.handleChangeDesc.bind(this);
    this.handleChangeMasked = this.handleChangeMasked.bind(this);

    this.handleChangeAvatar = this.handleChangeAvatar.bind(this);
    this.handleDeleteAvatar = this.handleDeleteAvatar.bind(this);
    this.fileInput = React.createRef();
  }

  handleChangeDesc (event) {
    this.setState({
      descValue: event.target.value,
      modified: true
    })
  }

  handleChangeMasked (event) {
    this.setState({
      masked: event.target.checked,
      modified: true
    })
  }

  handleChangeAvatar () {
    const reader = new FileReader();
    let file = this.fileInput.current.files[0];
    let fileSrc = '', fileSize = file.size;
    reader.onload = (e) => {
      fileSrc = e.target.result;
      this.setState({
        imgSrc: fileSrc,
        imgSize: fileSize,
        modified: true
      });
    };
    reader.readAsDataURL(file);
  }

  handleDeleteAvatar () {
    this.setState({
      imgSrc: defaultAvatar,
      modified: true,
      imageDeleted: true,
    })
  }

  render () {
    console.log(this.state.masked)
    const imageSizeMax = 20971520
    const disabledState = !this.state.modified ? true : this.state.imgSize > imageSizeMax ? true : false
    const submitClass = !disabledState ? "btn btn-success btn-lg" : "btn btn-success btn-lg disabled"
    const disableDelete = this.state.imgSrc != '/storage/default/default-group.svg' ? "btn btn-outline-danger btn-sm" : "btn btn-outline-danger btn-sm disabled"
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
          <label htmlFor="titre">Description:</label>
          <Desc value={this.state.descValue} onChange={this.handleChangeDesc} />
        </div>
        <div id="divAvatar" className="form-group">
          <p>Avatar:</p>
          <div className="form-group">
            <label id="avatarLabel" htmlFor="avatar" className="btn btn-outline-secondary avatar" style={aStyle}></label>
            <br />
            <a id="btnDeleteAvatar" className={disableDelete} onClick={this.handleDeleteAvatar}>Effacer l'image</a>
            <input id="avatar" name="avatar" className={imageClass} type="file" accept=".JPG, .PNG, .SVG" ref={this.fileInput} onChange={this.handleChangeAvatar} />
            <div className="invalid-feedback">L'image doit être aux formats jpg, png ou svg at avoir une taille max de 20Mo.</div>
          </div>
          <input id="avatarDeleted" type="text" className="disabled" name="avatarDeleted" value={this.state.imageDeleted} readOnly />
        </div>
        <div className="form-group">
          <label htmlFor='masked-switch'>Groupe masqué: </label>
          <label className="switch ml-3">
            <input type="checkbox" id="masked-switch" checked={this.state.masked} onChange={this.handleChangeMasked} />
            <span className="slider round"></span>
          </label>
          <input name="masked" type="text" value={this.state.masked} hidden readOnly />
        </div>
        <LoadModal />
        <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Appliquer les modifications</button>
      </div>
    )
  }
}

if (document.getElementById('groupEditForm')) {
  ReactDOM.render(<GroupEditForm />, document.getElementById('groupEditForm'));
}
